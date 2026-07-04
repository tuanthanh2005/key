@extends('admin.layouts.admin')

@section('title', 'Quản Lý Sản Phẩm VPN')
@section('page_title', 'Quản Lý Sản Phẩm VPN')
@section('breadcrumb', 'Admin / Sản Phẩm VPN')

@section('content')

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="admin-stat-card p-3" style="--card-color:#2563eb">
            <div class="stat-icon" style="background:#dbeafe;color:#2563eb;width:42px;height:42px;font-size:20px;border-radius:10px">
                <i class="bi bi-shield-fill-check"></i>
            </div>
            <div>
                <div class="stat-label">Tổng Sản Phẩm</div>
                <div class="stat-value" style="font-size:22px">{{ $stats['total'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-stat-card p-3" style="--card-color:#16a34a">
            <div class="stat-icon" style="background:#dcfce7;color:#16a34a;width:42px;height:42px;font-size:20px;border-radius:10px">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div>
                <div class="stat-label">Đang Bán</div>
                <div class="stat-value" style="font-size:22px">{{ $stats['active'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-stat-card p-3" style="--card-color:#d97706">
            <div class="stat-icon" style="background:#fef3c7;color:#d97706;width:42px;height:42px;font-size:20px;border-radius:10px">
                <i class="bi bi-bag-check-fill"></i>
            </div>
            <div>
                <div class="stat-label">Đã Bán</div>
                <div class="stat-value" style="font-size:22px">{{ number_format($stats['total_sold']) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filter + Add -->
<div class="admin-card mb-4">
    <div class="admin-card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <div class="admin-search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Tên sản phẩm, thương hiệu..." value="{{ request('search') }}" style="width:100%">
                </div>
            </div>
            <div class="col-md-3">
                <select name="brand" class="form-select form-select-sm" style="border-radius:10px">
                    <option value="">Tất cả thương hiệu</option>
                    @foreach(['NordVPN','ExpressVPN','Surfshark','HMA VPN','CyberGhost','PureVPN','ProtonVPN','IPVanish'] as $b)
                    <option value="{{ $b }}" {{ request('brand')==$b?'selected':'' }}>{{ $b }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 flex-grow-1" style="font-size:13px">
                    <i class="bi bi-funnel me-1"></i>Lọc
                </button>
                <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm rounded-pill px-3" style="font-size:13px;white-space:nowrap">
                    <i class="bi bi-plus-lg me-1"></i>Thêm Sản Phẩm
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="bi bi-grid-3x3-gap-fill text-primary"></i>
            Danh Sách Sản Phẩm VPN
        </div>
    </div>
    <div class="table-responsive">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Sản Phẩm</th>
                    <th>Thương Hiệu</th>
                    <th>Gói</th>
                    <th>Giá Bán</th>
                    <th>Giá Gốc</th>
                    <th>Đã Bán</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $prod)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if(!empty($prod->image_path))
                                <img src="{{ asset($prod->image_path) }}" alt="{{ $prod->name }}" style="width:42px;height:42px;object-fit:contain;border-radius:10px;border:1px solid var(--gray-200);flex-shrink:0;">
                            @else
                                <div style="width:42px;height:42px;background:{{ $prod->color }}18;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;color:{{ $prod->color }};flex-shrink:0">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                            @endif
                            <div class="fw-700" style="font-size:13.5px">{{ $prod->name }}</div>
                        </div>
                    </td>
                    <td>
                        <span class="brand-pill" style="background:{{ $prod->color }}15;color:{{ $prod->color }};border:1px solid {{ $prod->color }}35">
                            {{ $prod->brand }}
                        </span>
                    </td>
                    <td style="font-size:12.5px;color:var(--admin-muted);font-weight:600">
                        {{ match($prod->plan){'1month'=>'1 Tháng','6month'=>'6 Tháng','1year'=>'1 Năm','2year'=>'2 Năm',default=>$prod->plan} }}
                    </td>
                    <td><span class="fw-800 text-primary">{{ number_format($prod->price) }}đ</span></td>
                    <td><span style="text-decoration:line-through;color:var(--admin-muted);font-size:12.5px">{{ $prod->old_price ? number_format($prod->old_price).'đ' : '-' }}</span></td>
                    <td>
                        <span class="fw-600">{{ number_format($prod->sold) }}</span>
                        <span style="font-size:11px;color:var(--admin-muted)"> đơn</span>
                    </td>
                    <td>
                        @if($prod->status === 'active')
                            @if($prod->stock <= 0)
                                <span class="admin-badge admin-badge-danger">Hết Hàng</span>
                            @else
                                <span class="admin-badge admin-badge-success">Đang Bán</span>
                            @endif
                        @else
                            <span class="admin-badge admin-badge-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 align-items-center">
                            <a href="{{ route('admin.products.edit', $prod->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;font-size:12px;padding:4px 10px">
                                <i class="bi bi-pencil me-1"></i>Sửa
                            </a>
                            <form action="{{ route('admin.products.destroy', $prod->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa gói VPN này không?');" style="display:inline-block;margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;font-size:12px;padding:4px 10px">
                                    <i class="bi bi-trash me-1"></i>Xóa
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Không có sản phẩm nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
