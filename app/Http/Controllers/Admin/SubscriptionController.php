<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::whereNotNull('start_date')
            ->whereNotNull('end_date');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%");
            });
        }

        // Filter status
        $status = $request->input('status', 'all');
        $now = now();
        if ($status === 'active') {
            $query->where('end_date', '>', $now);
        } elseif ($status === 'expiring_soon') {
            $query->where('end_date', '>', $now)
                  ->where('end_date', '<=', $now->copy()->addDays(7));
        } elseif ($status === 'expired') {
            $query->where('end_date', '<=', $now);
        }

        $subscriptions = $query->orderBy('end_date', 'asc')->paginate(15)->withQueryString();

        // Calculate statistics
        $stats = [
            'total' => Order::whereNotNull('start_date')->count(),
            'active' => Order::whereNotNull('start_date')->where('end_date', '>', $now)->count(),
            'expiring_soon' => Order::whereNotNull('start_date')
                ->where('end_date', '>', $now)
                ->where('end_date', '<=', $now->copy()->addDays(7))
                ->count(),
            'expired' => Order::whereNotNull('start_date')->where('end_date', '<=', $now)->count(),
        ];

        return view('admin.subscriptions.index', compact('subscriptions', 'stats', 'status'));
    }

    public function extend(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'days' => 'required|integer|min:1',
        ]);

        $currentEndDate = Carbon::parse($order->end_date);
        $newEndDate = $currentEndDate->isPast() ? now()->addDays($request->days) : $currentEndDate->addDays($request->days);

        $order->update([
            'end_date' => $newEndDate
        ]);

        return back()->with('success', 'Đã gia hạn thành công thêm ' . $request->days . ' ngày cho khách hàng ' . $order->customer_name . '!');
    }
}
