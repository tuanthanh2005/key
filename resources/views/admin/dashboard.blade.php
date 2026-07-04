@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Tổng Quan')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')

<!-- SLEEK PAGINATION STYLES -->
<style>
.pagination {
    margin-bottom: 0;
    gap: 4px;
}
.pagination .page-item .page-link {
    border-radius: 6px !important;
    padding: 4px 10px;
    font-size: 12px;
    color: var(--admin-muted);
    border-color: var(--admin-border);
}
.pagination .page-item.active .page-link {
    background-color: #2563eb;
    border-color: #2563eb;
    color: #fff;
}
</style>

<!-- DATE RANGE FILTER BAR -->
<div class="admin-card mb-4 p-3">
    <form action="{{ route('admin.dashboard') }}" method="GET" class="row g-3 align-items-center">
        <div class="col-auto">
            <span class="fw-700 text-dark"><i class="bi bi-funnel-fill text-primary me-1"></i>Lọc Doanh Thu:</span>
        </div>
        <div class="col-auto">
            <div class="d-flex align-items-center gap-2">
                <label class="mb-0 text-muted" style="font-size:13px;">Từ ngày:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm" style="border-radius:8px; width:160px;">
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex align-items-center gap-2">
                <label class="mb-0 text-muted" style="font-size:13px;">Đến ngày:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm" style="border-radius:8px; width:160px;">
            </div>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3 fw-600">Áp Dụng</button>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">Mặc Định (Tuần Này)</a>
        </div>
        @if(session('warning'))
        <div class="col-12 mt-2">
            <div class="alert alert-warning py-2 px-3 mb-0" style="font-size:12.5px; border-radius:8px;">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('warning') }}
            </div>
        </div>
        @endif
    </form>
</div>

<!-- STAT CARDS -->
<div class="row g-4 mb-4">
    @php
    $cards = [
        ['label'=>'Tổng Doanh Thu','value'=>number_format($stats['total_revenue']).'đ','icon'=>'bi-currency-exchange','color'=>'#2563eb','bg'=>'#dbeafe','change'=>'Doanh thu tích lũy','up'=>true],
        ['label'=>'Tổng Đơn Hàng','value'=>number_format($stats['total_orders']),'icon'=>'bi-bag-check-fill','color'=>'#16a34a','bg'=>'#dcfce7','change'=>'Tổng cộng trong DB','up'=>true],
        ['label'=>'Đơn Chờ Xử Lý','value'=>number_format($stats['pending_orders']),'icon'=>'bi-clock-fill','color'=>'#d97706','bg'=>'#fef3c7','change'=>'Cần duyệt cấp key','up'=>false],
        ['label'=>'Khách Hàng','value'=>number_format($stats['total_users']),'icon'=>'bi-people-fill','color'=>'#7c3aed','bg'=>'#ede9fe','change'=>'Người dùng đăng ký','up'=>true],
    ];
    @endphp
    @foreach($cards as $card)
    <div class="col-6 col-lg-3">
        <div class="admin-stat-card" style="--card-color:{{ $card['color'] }}">
            <div class="stat-icon" style="background:{{ $card['bg'] }};color:{{ $card['color'] }}">
                <i class="bi {{ $card['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-label">{{ $card['label'] }}</div>
                <div class="stat-value" style="font-size: 20px;">{{ $card['value'] }}</div>
                <div class="stat-change {{ $card['up'] ? 'up' : 'down' }}">
                    <i class="bi bi-info-circle-fill"></i>
                    {{ $card['change'] }}
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- CHARTS ROW -->
<div class="row g-4 mb-4">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-graph-up-arrow text-primary"></i>
                    Biểu Đồ Doanh Thu & Đơn Hàng ({{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }})
                </div>
            </div>
            <div class="admin-card-body">
                <div class="chart-container" style="height:280px">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Brand Donut -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-pie-chart-fill text-primary"></i>
                    Doanh Thu Theo Hãng (Paid)
                </div>
            </div>
            <div class="admin-card-body">
                <div class="chart-container d-flex justify-content-center" style="height:160px">
                    <canvas id="brandChart"></canvas>
                </div>
                <div class="mt-3" style="max-height: 180px; overflow-y: auto;">
                    @foreach($brandStats as $brand)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div style="width:10px;height:10px;border-radius:50%;background:{{ $brand['color'] }};flex-shrink:0"></div>
                        <div class="flex-grow-1 text-truncate" style="font-size:12.5px;color:var(--admin-muted)">{{ $brand['brand'] }}</div>
                        <div class="text-end" style="font-size:12.5px;font-weight:700">
                            {{ number_format($brand['revenue']) }}đ <small class="text-muted">({{ $brand['orders'] }} đơn)</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT ORDERS & USERS -->
<div class="row g-4">
    <!-- Recent Orders (Paginated 10) -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-receipt text-primary"></i>
                    Danh Sách Đơn Hàng Mới Nhất
                </div>
            </div>
            @if($recentOrders->isEmpty())
            <div class="admin-card-body text-center py-5">
                <i class="bi bi-bag-x" style="font-size:48px;color:var(--admin-border)"></i>
                <p class="mt-3 text-muted" style="font-size:14px">Chưa có đơn hàng nào</p>
                <p class="text-muted" style="font-size:12.5px">Đơn hàng sẽ xuất hiện ở đây sau khi khách mua</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách Hàng</th>
                            <th>Sản Phẩm</th>
                            <th>Tổng</th>
                            <th>Trạng Thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                        <tr>
                            <td><span class="fw-700 text-primary">#{{ $order->order_code }}</span></td>
                            <td>
                                <div class="fw-600">{{ $order->customer_name }}</div>
                                <div class="text-muted" style="font-size:12px">{{ $order->customer_email }}</div>
                            </td>
                            <td>
                                <div style="font-size:13px">{{ $order->product_name }}</div>
                                <div class="text-muted" style="font-size:11.5px">{{ $order->brand }}</div>
                            </td>
                            <td><span class="fw-700">{{ number_format($order->total) }}đ</span></td>
                            <td>
                                <span class="admin-badge admin-badge-{{ $order->getStatusBadge() }}">
                                    {{ $order->getStatusLabel() }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:12px">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="admin-card-footer d-flex justify-content-between align-items-center py-2 px-3 border-top" style="border-color:var(--admin-border)!important">
                <div class="text-muted" style="font-size:12px;">
                    Hiển thị {{ $recentOrders->firstItem() }}-{{ $recentOrders->lastItem() }} của {{ $recentOrders->total() }} đơn
                </div>
                <div>
                    {{ $recentOrders->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column (Top Products & Newest Users) -->
    <div class="col-lg-4">
        <!-- Top Selling Products -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-shield-fill-check text-primary"></i>
                    Top Sản Phẩm Mua Nhiều
                </div>
            </div>
            <div class="admin-card-body pt-2">
                @if($topProducts->isEmpty())
                <div class="text-center py-4">
                    <p class="text-muted mb-0" style="font-size:13px;">Chưa có dữ liệu bán hàng</p>
                </div>
                @else
                @foreach($topProducts as $idx => $prod)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-600 text-truncate" style="font-size:13px; max-width: 70%;" title="{{ $prod->product_name }}">{{ $prod->product_name }}</span>
                        <span style="font-size:12px;color:var(--admin-muted)">{{ $prod->sold_count }} đã bán</span>
                    </div>
                    @php 
                        $maxSold = $topProducts->max('sold_count') ?: 1; 
                        $pct = round(($prod->sold_count / $maxSold) * 100);
                        $colors = ['#2563eb', '#10b981', '#f59e0b', '#7c3aed', '#ef4444'];
                        $color = $colors[$idx % count($colors)];
                    @endphp
                    <div class="admin-progress">
                        <div class="admin-progress-fill" style="width:{{ $pct }}%;background:{{ $color }}"></div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <!-- 5 Newest Registered Users -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-person-plus-fill text-primary"></i>
                    Khách Hàng Mới Nhất
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3" style="font-size:12px">Tất Cả</a>
            </div>
            <div class="admin-card-body pt-2">
                @if($recentUsers->isEmpty())
                <p class="text-muted text-center py-3" style="font-size:13px">Chưa có khách hàng</p>
                @else
                @foreach($recentUsers as $user)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width:38px;height:38px;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:14px;flex-shrink:0">
                        {{ $user->getAvatarInitial() }}
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-600" style="font-size:13.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $user->name }}</div>
                        <div class="text-muted" style="font-size:11.5px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $user->email }}</div>
                    </div>
                    <span class="admin-badge {{ $user->status === 'active' ? 'admin-badge-success' : 'admin-badge-danger' }}" style="font-size:10.5px">
                        {{ $user->status === 'active' ? 'Active' : 'Banned' }}
                    </span>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
// Dynamic Date Range Revenue Chart
const revenueChartData = @json($revenueChartData);
const labels  = revenueChartData.map(d => d.month);
const revenue = revenueChartData.map(d => d.revenue);
const orders  = revenueChartData.map(d => d.orders);

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            {
                label: 'Doanh Thu (đ)',
                data: revenue,
                backgroundColor: 'rgba(37,99,235,.12)',
                borderColor: '#2563eb',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                yAxisID: 'y',
            },
            {
                label: 'Đơn Hàng',
                data: orders,
                type: 'line',
                borderColor: '#7c3aed',
                backgroundColor: 'transparent',
                tension: .4,
                pointBackgroundColor: '#7c3aed',
                pointRadius: 4,
                yAxisID: 'y2',
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: true, position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.datasetIndex === 0) {
                            label += context.raw.toLocaleString('vi-VN') + 'đ';
                        } else {
                            label += context.raw + ' đơn';
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: {
                grid: { color: '#f1f5f9' },
                ticks: {
                    callback: v => v >= 1000000 ? (v / 1000000) + 'M' : (v >= 1000 ? (v / 1000) + 'K' : v),
                    font: { size: 10 }
                }
            },
            y2: {
                position: 'right',
                grid: { display: false },
                ticks: {
                    stepSize: 1,
                    font: { size: 10 }
                }
            }
        }
    }
});

// Brand Donut Chart (Dynamic Database Revenue mapping)
const brandStats = @json($brandStats);
// Filter out brands with zero revenue to avoid chart crowding
const activeBrands = brandStats.filter(b => b.revenue > 0);
const chartBrands = activeBrands.length > 0 ? activeBrands : brandStats;

new Chart(document.getElementById('brandChart'), {
    type: 'doughnut',
    data: {
        labels: chartBrands.map(b => b.brand),
        datasets: [{
            data: chartBrands.map(b => b.revenue),
            backgroundColor: chartBrands.map(b => b.color),
            borderWidth: 0,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const brand = chartBrands[context.dataIndex];
                        return brand.brand + ': ' + brand.revenue.toLocaleString('vi-VN') + 'đ (' + brand.orders + ' đơn)';
                    }
                }
            }
        }
    }
});
</script>
@endsection
