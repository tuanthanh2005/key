<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();

        if ($request->search) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('order_code', 'like', "%$q%")
                   ->orWhere('customer_name', 'like', "%$q%")
                   ->orWhere('customer_email', 'like', "%$q%")
                   ->orWhere('product_name', 'like', "%$q%");
            });
        }

        if ($request->status) {
            $query->where('order_status', $request->status);
        }

        if ($request->payment) {
            $query->where('payment_status', $request->payment);
        }

        if ($request->brand) {
            $query->where('brand', $request->brand);
        }

        $orders = $query->paginate(15)->withQueryString();

        $stats = [
            'total'      => Order::count(),
            'pending'    => Order::where('order_status', 'pending')->count(),
            'completed'  => Order::where('order_status', 'completed')->count(),
            'revenue'    => Order::where('payment_status', 'paid')->sum('total'),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status'   => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'license_key'    => 'nullable|string',
        ]);

        $order->update([
            'order_status'   => $request->order_status,
            'payment_status' => $request->payment_status,
            'license_key'    => $request->license_key,
        ]);

        return back()->with('success', 'Cập nhật đơn hàng #' . $order->order_code . ' thành công!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng!');
    }
}
