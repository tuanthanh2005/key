<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Dashboard tổng quan
     */
    public function dashboard(Request $request)
    {
        // 1. Lọc doanh thu theo ngày
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$startDate || !$endDate) {
            $startDate = date('Y-m-d', strtotime('monday this week'));
            $endDate = date('Y-m-d', strtotime('sunday this week'));
        }

        // Validate max range từ settings
        $maxDays = (int) Setting::get('dashboard_max_days', 60);
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $diff = $start->diff($end)->days;

        if ($diff > $maxDays) {
            $adjustedStartDate = (clone $end)->modify('-' . $maxDays . ' days')->format('Y-m-d');
            return redirect()->route('admin.dashboard', [
                'start_date' => $adjustedStartDate,
                'end_date' => $endDate
            ])->with('warning', 'Hệ thống giới hạn hiển thị doanh thu tối đa là 2 tháng. Đã tự động điều chỉnh khoảng ngày.');
        }

        // Thống kê tổng quan
        $stats = [
            'total_users'    => User::where('role', 'user')->count(),
            'total_orders'   => Order::count(),
            'total_revenue'  => Order::where('payment_status', 'paid')->sum('total'),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
        ];

        // Doanh thu chi tiết theo ngày trong khoảng lựa chọn
        $ordersQuery = Order::where('payment_status', 'paid')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->selectRaw('DATE(created_at) as date, SUM(total) as revenue, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Xây dựng mảng dữ liệu đầy đủ các ngày trong khoảng lọc (để biểu đồ không bị khuyết)
        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $revenueData = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $revenueData[$dateStr] = [
                'month' => $date->format('d/m'), // Dùng format ngày/tháng để làm label trục X
                'revenue' => 0,
                'orders' => 0
            ];
        }

        foreach ($ordersQuery as $o) {
            if (isset($revenueData[$o->date])) {
                $revenueData[$o->date]['revenue'] = (float) $o->revenue;
                $revenueData[$o->date]['orders'] = (int) $o->count;
            }
        }

        $revenueChartData = array_values($revenueData);

        // 2. Đơn hàng phân trang (lấy từ settings)
        $ordersPerPage = (int) Setting::get('dashboard_orders_per_page', 10);
        $recentOrders = Order::latest()->paginate($ordersPerPage)->withQueryString();

        // 3. Khách hàng mới nhất (lấy từ settings)
        $recentUsersCount = (int) Setting::get('dashboard_recent_users_count', 5);
        $recentUsers = User::where('role', 'user')->latest()->take($recentUsersCount)->get();

        // 4. Doanh thu theo thương hiệu từ DB
        $brandStats = $this->getBrandStats();

        // 5. Top sản phẩm bán chạy từ DB (lấy từ settings)
        $topProductsCount = (int) Setting::get('dashboard_top_products_count', 5);
        $topProducts = Order::where('payment_status', 'paid')
            ->selectRaw('product_name, SUM(quantity) as sold_count, SUM(total) as revenue')
            ->groupBy('product_name')
            ->orderBy('sold_count', 'desc')
            ->take($topProductsCount)
            ->get();



        return view('admin.dashboard', compact(
            'stats', 'revenueChartData', 'recentOrders', 'recentUsers', 'brandStats', 'topProducts', 'startDate', 'endDate'
        ));
    }

    private function getBrandStats(): array
    {
        // Lấy màu từ cột color trong products thay vì hardcode
        $brands = Order::where('payment_status', 'paid')
            ->selectRaw('brand, COUNT(*) as count, SUM(total) as revenue')
            ->groupBy('brand')
            ->get();

        // Lấy map brand => color từ bảng products
        $productColors = Product::select('brand', 'color')
            ->distinct()
            ->whereNotNull('color')
            ->get()
            ->pluck('color', 'brand')
            ->toArray();

        $defaultColors = ['#2563eb','#10b981','#f59e0b','#8b5cf6','#ef4444','#0ea5e9','#6d28d9','#da3940'];
        $colorIndex    = 0;
        $brandStatsMap = [];

        foreach ($brands as $br) {
            $key   = strtolower($br->brand);
            $color = $productColors[$br->brand] ?? $defaultColors[$colorIndex % count($defaultColors)];
            $colorIndex++;
            $brandStatsMap[$key] = [
                'brand'   => $br->brand,
                'orders'  => $br->count,
                'revenue' => (float) $br->revenue,
                'color'   => $color,
            ];
        }

        return array_values($brandStatsMap);
    }

}
