@extends('admin.layouts.admin')

@section('title', 'Quản Lý Coupon')
@section('page_title', 'Quản Lý Mã Giảm Giá')
@section('breadcrumb', 'Admin / Coupon')

@section('content')
<style>
.coupon-badge { font-size: 13px; font-weight: 700; font-family: monospace; letter-spacing: 1px; background: #f0f4ff; color: #2563eb; padding: 4px 10px; border-radius: 6px; border: 1.5px dashed #93c5fd; }
.coupon-type-badge { font-size: 11px; padding: 2px 8px; border-radius: 20px; font-weight: 600; }
.coupon-type-percent { background: #dcfce7; color: #16a34a; }
.coupon-type-fixed { background: #fef3c7; color: #d97706; }
</style>

<div class="row g-4">
    <!-- Add Coupon Form -->
    <div class="col-lg-4">
        <div class="admin-card" id="couponFormCard">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="bi bi-plus-circle-fill text-success"></i> Thêm Mã Mới</div>
            </div>
            <div class="admin-card-body">
                @if(session('success'))
                <div class="alert alert-success py-2 mb-3" style="border-radius:8px; font-size:13px">
                    <i class="bi bi-check-circle me-1"></i>{{ session('success') }}
                </div>
                @endif
                @if($errors->any())
                <div class="alert alert-danger py-2 mb-3" style="border-radius:8px; font-size:13px">
                    @foreach($errors->all() as $error){{ $error }}<br>@endforeach
                </div>
                @endif

                <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponCreateForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Mã Coupon <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" placeholder="VPNVN10" value="{{ old('code') }}" style="text-transform:uppercase; font-family:monospace; letter-spacing:1px" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Loại Giảm Giá <span class="text-danger">*</span></label>
                        <select name="discount_type" class="form-select" id="discountType" required>
                            <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                            <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Giá Trị Giảm <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" name="discount_value" class="form-control" placeholder="10" min="0" step="0.5" value="{{ old('discount_value') }}" required>
                            <span class="input-group-text" id="discountUnit">%</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Đơn Hàng Tối Thiểu (đ)</label>
                        <input type="number" name="min_order" class="form-control" placeholder="0" min="0" step="1000" value="{{ old('min_order', 0) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Số Lần Dùng Tối Đa</label>
                        <input type="number" name="max_uses" class="form-control" placeholder="Để trống = không giới hạn" min="1" value="{{ old('max_uses') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Hạn Sử Dụng</label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Mô Tả</label>
                        <input type="text" name="description" class="form-control" placeholder="Giảm 10% cho tất cả..." value="{{ old('description') }}">
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="active" value="1" id="activeCheck" {{ old('active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="activeCheck" style="font-size:13px">Kích Hoạt Ngay</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100 rounded-pill fw-600">
                        <i class="bi bi-plus-circle me-1"></i>Tạo Coupon
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Coupon List -->
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title"><i class="bi bi-tags-fill text-primary"></i> Danh Sách Coupon ({{ $coupons->count() }})</div>
            </div>
            @if($coupons->isEmpty())
            <div class="admin-card-body text-center py-5">
                <i class="bi bi-tag" style="font-size:48px; color:var(--admin-border)"></i>
                <p class="text-muted mt-3">Chưa có coupon nào</p>
            </div>
            @else
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Giảm</th>
                            <th>Tối Thiểu</th>
                            <th>Đã Dùng</th>
                            <th>Hết Hạn</th>
                            <th>Trạng Thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($coupons as $coupon)
                        <tr>
                            <td>
                                <span class="coupon-badge">{{ $coupon->code }}</span>
                                @if($coupon->description)
                                <div class="text-muted mt-1" style="font-size:11.5px">{{ $coupon->description }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="coupon-type-badge coupon-type-{{ $coupon->discount_type }}">
                                    @if($coupon->discount_type === 'percent')
                                        {{ $coupon->discount_value }}%
                                    @else
                                        {{ number_format($coupon->discount_value) }}đ
                                    @endif
                                </span>
                            </td>
                            <td style="font-size:12.5px">{{ $coupon->min_order > 0 ? number_format($coupon->min_order) . 'đ' : 'Không' }}</td>
                            <td style="font-size:12.5px">
                                {{ $coupon->used_count }}
                                @if($coupon->max_uses) / {{ $coupon->max_uses }} @else <span class="text-muted">/ ∞</span> @endif
                            </td>
                            <td style="font-size:12px">
                                @if($coupon->expires_at)
                                    <span class="{{ $coupon->expires_at->isPast() ? 'text-danger' : 'text-muted' }}">
                                        {{ $coupon->expires_at->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">Không hạn</span>
                                @endif
                            </td>
                            <td>
                                <span class="admin-badge admin-badge-{{ $coupon->active ? 'success' : 'secondary' }}">
                                    {{ $coupon->active ? 'Active' : 'Tắt' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                     <button class="btn btn-sm btn-outline-primary edit-coupon-btn" style="border-radius:6px; font-size:12px"
                                         data-id="{{ $coupon->id }}"
                                         data-coupon="{{ json_encode($coupon) }}">
                                         <i class="bi bi-pencil"></i>
                                     </button>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Xóa coupon {{ $coupon->code }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px; font-size:12px">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCouponModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:16px; border:none">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-700">Sửa Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editCouponForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Mã Coupon</label>
                        <input type="text" name="code" id="editCode" class="form-control" required style="text-transform:uppercase;font-family:monospace;letter-spacing:1px">
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-600" style="font-size:13px">Loại</label>
                            <select name="discount_type" id="editDiscountType" class="form-select">
                                <option value="percent">Phần trăm (%)</option>
                                <option value="fixed">Cố định (đ)</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label fw-600" style="font-size:13px">Giá Trị</label>
                            <input type="number" name="discount_value" id="editDiscountValue" class="form-control" min="0" step="0.5" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label class="form-label fw-600" style="font-size:13px">Đơn Tối Thiểu (đ)</label>
                            <input type="number" name="min_order" id="editMinOrder" class="form-control" min="0">
                        </div>
                        <div class="col">
                            <label class="form-label fw-600" style="font-size:13px">Dùng Tối Đa</label>
                            <input type="number" name="max_uses" id="editMaxUses" class="form-control" min="1" placeholder="∞">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Hạn Sử Dụng</label>
                        <input type="date" name="expires_at" id="editExpiresAt" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px">Mô Tả</label>
                        <input type="text" name="description" id="editDescription" class="form-control">
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="active" value="1" id="editActive">
                        <label class="form-check-label fw-600" for="editActive" style="font-size:13px">Kích Hoạt</label>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">Lưu Thay Đổi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
// Discount type unit label
document.getElementById('discountType').addEventListener('change', function() {
    document.getElementById('discountUnit').textContent = this.value === 'percent' ? '%' : 'đ';
});

function editCoupon(id, data) {
    const form = document.getElementById('editCouponForm');
    form.action = `/admin/coupon/${id}`;
    document.getElementById('editCode').value = data.code;
    document.getElementById('editDiscountType').value = data.discount_type;
    document.getElementById('editDiscountValue').value = data.discount_value;
    document.getElementById('editMinOrder').value = data.min_order;
    document.getElementById('editMaxUses').value = data.max_uses || '';
    document.getElementById('editExpiresAt').value = data.expires_at ? data.expires_at.substring(0, 10) : '';
    document.getElementById('editDescription').value = data.description || '';
    document.getElementById('editActive').checked = data.active;
    new bootstrap.Modal(document.getElementById('editCouponModal')).show();
}

// Bind click event for edit coupon buttons safely
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.edit-coupon-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const data = JSON.parse(this.dataset.coupon);
            editCoupon(id, data);
        });
    });
});
</script>
@endsection
