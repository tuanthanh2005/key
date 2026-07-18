@extends('admin.layouts.admin')
@section('title', 'Quản Lý Hạn Khách Hàng')
@section('page_title', 'Hạn Sử Dụng Khách Hàng')
@section('breadcrumb', 'Admin / Hạn Khách Hàng')

@section('content')

@if(session('success'))
<div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px; font-size:13.5px">
    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
</div>
@endif

<!-- STATISTICS CARDS -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.subscriptions.index', ['status' => 'all']) }}" class="text-decoration-none">
            <div class="admin-card text-white" style="background: linear-gradient(135deg, #6366f1, #4f46e5); border: none; transition: transform 0.2s; cursor: pointer;">
                <div class="admin-card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase mb-1">Tổng Số Hạn</div>
                        <div class="h2 mb-0 fw-bold">{{ $stats['total'] }}</div>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-people-fill"></i></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.subscriptions.index', ['status' => 'active']) }}" class="text-decoration-none">
            <div class="admin-card text-white" style="background: linear-gradient(135deg, #10b981, #059669); border: none; transition: transform 0.2s; cursor: pointer;">
                <div class="admin-card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase mb-1">Đang Hoạt Động</div>
                        <div class="h2 mb-0 fw-bold">{{ $stats['active'] }}</div>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-shield-fill-check"></i></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.subscriptions.index', ['status' => 'expiring_soon']) }}" class="text-decoration-none">
            <div class="admin-card text-white" style="background: linear-gradient(135deg, #f59e0b, #d97706); border: none; transition: transform 0.2s; cursor: pointer;">
                <div class="admin-card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase mb-1">Gần Hết Hạn (<=7 ngày)</div>
                        <div class="h2 mb-0 fw-bold">{{ $stats['expiring_soon'] }}</div>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-exclamation-triangle-fill"></i></div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <a href="{{ route('admin.subscriptions.index', ['status' => 'expired']) }}" class="text-decoration-none">
            <div class="admin-card text-white" style="background: linear-gradient(135deg, #f43f5e, #e11d48); border: none; transition: transform 0.2s; cursor: pointer;">
                <div class="admin-card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <div class="text-white-50 small fw-bold text-uppercase mb-1">Đã Hết Hạn</div>
                        <div class="h2 mb-0 fw-bold">{{ $stats['expired'] }}</div>
                    </div>
                    <div class="fs-1 text-white-50"><i class="bi bi-clock-history"></i></div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- FILTER & SEARCH PANEL -->
<div class="admin-card mb-4">
    <div class="admin-card-body p-3">
        <form method="GET" action="{{ route('admin.subscriptions.index') }}" class="row g-3 align-items-center">
            <input type="hidden" name="status" value="{{ $status }}">
            
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Tìm tên, email, SĐT, mã đơn..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-12 col-md-5">
                <div class="btn-group w-100" role="group">
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'all', 'search' => request('search')]) }}" class="btn btn-outline-primary btn-sm py-2 {{ $status === 'all' ? 'active' : '' }}">Tất Cả</a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'active', 'search' => request('search')]) }}" class="btn btn-outline-success btn-sm py-2 {{ $status === 'active' ? 'active' : '' }}">Đang Hoạt Động</a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'expiring_soon', 'search' => request('search')]) }}" class="btn btn-outline-warning btn-sm py-2 {{ $status === 'expiring_soon' ? 'active' : '' }}">Gần Hết Hạn</a>
                    <a href="{{ route('admin.subscriptions.index', ['status' => 'expired', 'search' => request('search')]) }}" class="btn btn-outline-danger btn-sm py-2 {{ $status === 'expired' ? 'active' : '' }}">Đã Hết Hạn</a>
                </div>
            </div>

            <div class="col-12 col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm py-2 px-3 flex-grow-1"><i class="bi bi-funnel-fill me-1"></i> Lọc Kết Quả</button>
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-outline-secondary btn-sm py-2 px-3"><i class="bi bi-arrow-counterclockwise"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- DATA TABLE -->
<div class="admin-card">
    <div class="admin-card-header d-flex justify-content-between align-items-center">
        <div class="admin-card-title">
            <i class="bi bi-table text-primary"></i> Lịch Sử Hạn Sử Dụng
        </div>
        <span class="admin-badge admin-badge-primary">Tìm thấy {{ $subscriptions->total() }} bản ghi</span>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width:120px">Mã Đơn</th>
                        <th>Khách Hàng</th>
                        <th>Sản Phẩm & Thời Hạn</th>
                        <th>Ngày Kích Hoạt</th>
                        <th>Ngày Hết Hạn</th>
                        <th>Trạng Thái</th>
                        <th class="text-end" style="width:120px">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subscriptions as $sub)
                        @php
                            $isExpired = $sub->end_date->isPast();
                            $isExpiringSoon = !$isExpired && $sub->end_date->diffInDays(now()) <= 7;
                        @endphp
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $sub->id) }}" class="fw-bold text-decoration-none text-primary">
                                    {{ $sub->order_code }}
                                </a>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $sub->customer_name }}</div>
                                <div class="text-muted" style="font-size:11px;">
                                    <i class="bi bi-envelope me-1"></i>{{ $sub->customer_email }}
                                </div>
                                @if($sub->customer_phone)
                                <div class="text-muted" style="font-size:11px;">
                                    <i class="bi bi-telephone me-1"></i>{{ $sub->customer_phone }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ $sub->product_name }}</div>
                                <div class="text-muted small">Thương hiệu: {{ $sub->brand }} / Gói: {{ $sub->plan }}</div>
                            </td>
                            <td>
                                <div>{{ $sub->start_date->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}</div>
                                <div class="text-muted small">{{ $sub->start_date->timezone('Asia/Ho_Chi_Minh')->format('H:i:s') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold {{ $isExpired ? 'text-danger' : ($isExpiringSoon ? 'text-warning' : 'text-success') }}">
                                    {{ $sub->end_date->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}
                                </div>
                                <div class="text-muted small">{{ $sub->end_date->timezone('Asia/Ho_Chi_Minh')->format('H:i:s') }}</div>
                            </td>
                            <td>
                                @if($isExpired)
                                    <span class="badge bg-danger-subtle text-danger px-2.5 py-1.5 fw-bold" style="font-size:11px; border-radius:6px;">
                                        Đã Hết Hạn (Trễ {{ intval(now()->diffInDays($sub->end_date)) }} ngày)
                                    </span>
                                @elseif($isExpiringSoon)
                                    <span class="badge bg-warning-subtle text-warning px-2.5 py-1.5 fw-bold" style="font-size:11px; border-radius:6px;">
                                        Sắp Hết Hạn (Còn {{ intval(now()->diffInDays($sub->end_date)) }} ngày)
                                    </span>
                                @else
                                    <span class="badge bg-success-subtle text-success px-2.5 py-1.5 fw-bold" style="font-size:11px; border-radius:6px;">
                                        Đang Hoạt Động (Còn {{ intval(now()->diffInDays($sub->end_date)) }} ngày)
                                    </span>
                                @endif
                            </td>
                            <td class="text-end" style="padding-right: 20px;">
                                <button type="button" class="btn btn-sm btn-outline-primary px-2.5 py-1.5 extend-btn" 
                                        data-id="{{ $sub->id }}" 
                                        data-name="{{ $sub->customer_name }}"
                                        data-product="{{ $sub->product_name }}"
                                        data-end="{{ $sub->end_date->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#extendModal"
                                        style="border-radius:8px;">
                                    <i class="bi bi-arrow-repeat me-1"></i> Gia Hạn
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-clock display-6 mb-2 d-block text-muted"></i>
                                Không tìm thấy dữ liệu hạn khách hàng phù hợp.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- PAGINATION -->
        @if($subscriptions->hasPages())
        <div class="px-4 py-3 border-top d-flex justify-content-between align-items-center" style="border-color:var(--admin-border);">
            <div>Hiển thị bản ghi {{ $subscriptions->firstItem() }} - {{ $subscriptions->lastItem() }} trên tổng số {{ $subscriptions->total() }}</div>
            <div>{{ $subscriptions->links() }}</div>
        </div>
        @endif
    </div>
</div>

<!-- EXTEND MODAL -->
<div class="modal fade" id="extendModal" tabindex="-1" aria-labelledby="extendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; overflow:hidden; background:var(--admin-surface); box-shadow:0 8px 30px rgba(0,0,0,0.12);">
            <div class="modal-header border-bottom py-3 px-4" style="border-color:var(--admin-border);">
                <h6 class="modal-title fw-bold text-dark" id="extendModalLabel"><i class="bi bi-arrow-repeat text-primary me-2"></i>Gia Hạn Dịch Vụ</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="extendForm" method="POST" style="margin:0;">
                @csrf
                @method('PUT')
                <div class="modal-body p-4" style="display:flex; flex-direction:column; gap:16px;">
                    <div style="background: var(--admin-bg); padding: 12px; border-radius: 10px; border: 1px solid var(--admin-border);">
                        <div class="text-muted small">Khách Hàng:</div>
                        <div class="fw-bold text-dark mb-2" id="extend_customer_name"></div>
                        <div class="text-muted small">Sản Phẩm:</div>
                        <div class="fw-bold text-primary mb-2" id="extend_product_name"></div>
                        <div class="text-muted small">Ngày Hết Hạn Hiện Tại:</div>
                        <div class="fw-bold text-danger" id="extend_end_date"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label fw-bold text-dark">Số Ngày Muốn Gia Hạn <span class="text-danger">*</span></label>
                        <input type="number" name="days" class="form-control" placeholder="Ví dụ: 30" min="1" required>
                        <div class="form-text" style="font-size:11.5px; color:var(--text-muted);">
                            Gia hạn sẽ cộng dồn số ngày vào ngày hết hạn hiện tại nếu thuê bao đang hoạt động, hoặc tính từ ngày hôm nay nếu thuê bao đã hết hạn.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3 px-4" style="background:var(--admin-bg); border-color:var(--admin-border);">
                    <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="border-radius:20px;">Hủy</button>
                    <button type="submit" class="btn btn-sm btn-primary px-4" style="border-radius:20px;">Xác Nhận Gia Hạn</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const extendForm = document.getElementById('extendForm');
    const extendButtons = document.querySelectorAll('.extend-btn');

    extendButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const product = this.getAttribute('data-product');
            const endDate = this.getAttribute('data-end');

            extendForm.action = `/admin/han-khach-hang/${id}/gia-han`;
            document.getElementById('extend_customer_name').textContent = name;
            document.getElementById('extend_product_name').textContent = product;
            document.getElementById('extend_end_date').textContent = endDate;
        });
    });
});
</script>
@endsection
