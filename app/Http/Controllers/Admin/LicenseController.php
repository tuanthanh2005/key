<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\Product;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $query = License::with(['product', 'order']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('license_key', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhereHas('order', function($o) use ($search) {
                      $o->where('order_code', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%");
                  });
            });
        }

        $licenses = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $stats = [
            'total'     => License::count(),
            'available' => License::where('is_used', false)->count(), // failed mail handovers
            'used'      => License::where('is_used', true)->count(),  // successful handovers
        ];

        // Fetch pending orders
        $pendingOrders = \App\Models\Order::with(['user'])
            ->whereIn('order_status', ['pending', 'processing'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.licenses.index', compact('licenses', 'stats', 'pendingOrders'));
    }

    public function destroy(License $license)
    {
        $license->delete();
        return back()->with('success', 'Đã xóa bản ghi lịch sử bàn giao thành công!');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'email'    => 'required|email|max:255',
            'subject'  => 'required|string|max:255',
            'content'  => 'required|string',
        ]);

        $order = \App\Models\Order::with('user')->findOrFail($request->order_id);
        $userEmail = $request->email;
        $subject = $request->subject;
        $content = $request->content;

        // Correct customer email in order record if modified by admin
        if ($order->customer_email !== $userEmail) {
            $order->update([
                'customer_email' => $userEmail
            ]);
        }

        // Try sending email via SMTP
        try {
            \Illuminate\Support\Facades\Mail::raw($content, function ($message) use ($userEmail, $subject) {
                $message->to($userEmail)
                        ->subject($subject);
            });
            $mailSent = true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("SMTP Mail sending failed: " . $e->getMessage());
            $mailSent = false;
        }

        // Find or fallback product ID for license foreign key constraints
        $product = \App\Models\Product::where('name', $order->product_name)->first();
        if (!$product) {
            $product = \App\Models\Product::where('brand', $order->brand)->first() ?: \App\Models\Product::first();
        }
        $productId = $product ? $product->id : null;

        // Create handover history record
        if ($productId) {
            License::create([
                'product_id'  => $productId,
                'order_id'    => $order->id,
                'user_id'     => $order->user_id,
                'license_key' => $content,
                'type'        => $subject, // Save the email subject inside type column
                'is_used'     => $mailSent,
                'assigned_at' => now(),
            ]);
        }

        // Update order status to completed and paid
        $order->update([
            'order_status'   => 'completed',
            'payment_status' => 'paid',
            'license_key'    => $content,
        ]);

        if ($mailSent) {
            return back()->with('success', "Đã gửi email bàn giao key và hoàn thành đơn hàng {$order->order_code} thành công!");
        } else {
            return back()->with('success', "Đơn hàng {$order->order_code} đã hoàn thành! (Cảnh báo: Không gửi được email qua SMTP, lịch sử được ghi nhận là thất bại).");
        }
    }
}