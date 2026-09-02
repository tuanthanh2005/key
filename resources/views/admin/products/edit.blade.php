@extends('admin.layouts.admin')
@section('title', 'Sửa Sản Phẩm')
@section('page_title', 'Sửa Sản Phẩm: ' . ($product->brand ?: $product->name))
@section('breadcrumb', 'Admin / Sửa Sản Phẩm')

@section('topbar_actions')
<a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">← Quay Lại</a>
@endsection

@section('content')

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-info-circle text-primary"></i> Thông Tin Thương Hiệu & Sản Phẩm</div>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">Tên Thương Hiệu / Sản Phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->brand ?: $product->name) }}" required placeholder="VD: NordVPN Premium, Surfshark, YouTube Premium">
                            @error('name')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Mã Slug (URL)</label>
                            <input type="text" name="slug" class="form-control" value="{{ old('slug', $product->slug) }}" placeholder="Mã slug (VD: nordvpn)">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh Mục</label>
                            <select name="category_id" class="form-select">
                                <option value="">— Chọn danh mục —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Loại Sản Phẩm <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="account" {{ old('type', $product->type) === 'account' ? 'selected' : '' }}>Tài Khoản</option>
                                <option value="license" {{ old('type', $product->type) === 'license' ? 'selected' : '' }}>License Key</option>
                                <option value="subscription" {{ old('type', $product->type) === 'subscription' ? 'selected' : '' }}>Subscription</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô Tả Sản Phẩm</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Mô tả chi tiết về sản phẩm...">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tính Năng (mỗi dòng một tính năng)</label>
                        <textarea name="features[]" id="features-input" class="form-control" rows="4" placeholder="Mỗi dòng là một tính năng...">{{ old('features', $product->features) ? implode("\n", old('features', $product->features)) : '' }}</textarea>
                        <div class="text-muted mt-1" style="font-size:12px;"><i class="bi bi-info-circle-fill text-primary"></i> Mỗi dòng sẽ được hiển thị thành một tính năng riêng.</div>
                    </div>
                </div>
            </div>

            {{-- MULTI-PLAN REPEATER CARD --}}
            <div class="admin-card mb-4">
                <div class="admin-card-header d-flex justify-content-between align-items-center" style="background: rgba(124,58,237,0.03); border-bottom: 1px solid rgba(124,58,237,0.15);">
                    <div class="admin-card-title text-primary"><i class="bi bi-box-seam-fill me-2"></i> Quản Lý Tất Cả Gói Thời Hạn (Plans)</div>
                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="btn-add-plan">
                        <i class="bi bi-plus-lg me-1"></i> + Thêm Gói Mới
                    </button>
                </div>
                <div class="admin-card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0" id="plans-table">
                            <thead style="background:var(--bg-base); font-size:0.75rem; text-transform:uppercase;">
                                <tr>
                                    <th style="min-width:140px;">Tên / Thời hạn gói <span class="text-danger">*</span></th>
                                    <th style="min-width:110px;">Giá bán (VNĐ) <span class="text-danger">*</span></th>
                                    <th style="min-width:100px;">Giá gốc (VNĐ)</th>
                                    <th style="min-width:90px;">Số ngày</th>
                                    <th style="min-width:80px;">Kho</th>
                                    <th style="min-width:130px;">Ghi chú gói</th>
                                    <th style="width:70px; text-align:center;">Nổi bật</th>
                                    <th style="width:40px; text-align:center;"></th>
                                </tr>
                            </thead>
                            <tbody id="plans-container">
                                <!-- Existing and new plans loaded via JS -->
                            </tbody>
                        </table>
                    </div>
                    <div class="text-muted mt-2" style="font-size:12px;">
                        <i class="bi bi-info-circle-fill text-primary"></i> <strong>Lưu ý:</strong> Chỉnh sửa giá bán, thêm gói mới hoặc bấm nút <strong>X</strong> để xóa bớt gói thời hạn thuộc thương hiệu này.
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="admin-card-header d-flex justify-content-between align-items-center">
                    <div class="admin-card-title"><i class="bi bi-cpu text-primary"></i> Thông Số Kỹ Thuật</div>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-spec">
                        <i class="bi bi-plus-lg"></i> Thêm thông số
                    </button>
                </div>
                <div class="admin-card-body">
                    <div id="specs-container" class="d-flex flex-column gap-3">
                        <!-- Dynamic specs rows -->
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-search text-primary"></i> SEO Meta</div>
                </div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title) }}" placeholder="Tiêu đề SEO (để trống = tên sản phẩm)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2" placeholder="Mô tả SEO tối đa 160 ký tự...">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-image text-primary"></i> Hình Ảnh Đại Diện</div>
                </div>
                <div class="admin-card-body">
                    <div style="border:2px dashed var(--admin-border); border-radius:12px; padding:24px; text-align:center; cursor:pointer;" onclick="document.getElementById('image-input').click()">
                        <div id="img-preview" style="{{ $product->image_url ? 'display:block;' : 'display:none;' }} margin-bottom:12px;">
                            <img id="img-preview-src" src="{{ $product->image_url }}" style="max-height:140px; border-radius:8px; margin:0 auto; display:block; object-fit:contain;">
                        </div>
                        <div style="{{ $product->image_url ? 'display:none;' : 'display:block;' }} margin-bottom:8px;" id="upload-icon"><i class="bi bi-image" style="font-size:2rem; color:var(--admin-muted);"></i></div>
                        <div style="font-size:0.85rem; color:var(--admin-muted); font-weight:600;">Click để đổi ảnh đại diện</div>
                        <div style="font-size:0.75rem; color:var(--admin-muted); margin-top:4px;">PNG, JPG, WEBP (tối đa 2MB)</div>
                        <input type="file" id="image-input" name="image" accept="image/*" style="display:none" onchange="previewImage(this)">
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-gear text-primary"></i> Cài Đặt Hiển Thị</div>
                </div>
                <div class="admin-card-body">
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Kích Hoạt (Active)</label>
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Hiển Thị Trong Danh Sách</label>
                            <input class="form-check-input" type="checkbox" name="show_in_list" value="1" {{ old('show_in_list', $product->show_in_list) ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Sản Phẩm Nổi Bật (Featured)</label>
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Sản Phẩm Bán Chạy Nhất (Popular)</label>
                            <input class="form-check-input" type="checkbox" name="is_popular" value="1" {{ old('is_popular', $product->is_popular) ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Cần Nhập Email Nâng Cấp</label>
                            <input class="form-check-input" type="checkbox" name="require_upgrade_email" value="1" {{ old('require_upgrade_email', $product->require_upgrade_email) ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold" style="border-radius:12px; font-size:1.05rem;">
                <i class="bi bi-save me-2"></i> Cập Nhật Tất Cả Gói Sản Phẩm
            </button>
        </div>
    </div>
</form>

@endsection

@section('extra_js')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('img-preview-src').src = e.target.result;
            document.getElementById('img-preview').style.display = 'block';
            document.getElementById('upload-icon').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Convert features textarea to array
document.querySelector('form').addEventListener('submit', function() {
    const ta = document.getElementById('features-input');
    if (ta) {
        const lines = ta.value.split('\n').filter(l => l.trim());
        this.querySelectorAll('input[name^="features["]').forEach(el => el.remove());
        lines.forEach((line, i) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `features[${i}]`;
            input.value = line.trim();
            this.appendChild(input);
        });
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Dynamic Plans Table Repeater
    const plansContainer = document.getElementById('plans-container');
    const btnAddPlan = document.getElementById('btn-add-plan');
    let planIndex = 0;

    function addPlanRow(id = null, label = '', price = '', originalPrice = '', durationDays = 30, stock = -1, note = '', isPopular = false) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                ${id ? `<input type="hidden" name="plans[${planIndex}][id]" value="${id}">` : ''}
                <input type="text" name="plans[${planIndex}][label]" class="form-control form-control-sm fw-bold" value="${escapeHtml(label)}" placeholder="VD: 1 Tháng / 1 Năm" required>
            </td>
            <td>
                <input type="number" name="plans[${planIndex}][price]" class="form-control form-control-sm font-monospace text-primary fw-bold" value="${price}" placeholder="79000" required min="0">
            </td>
            <td>
                <input type="number" name="plans[${planIndex}][original_price]" class="form-control form-control-sm font-monospace text-muted" value="${originalPrice || ''}" placeholder="150000" min="0">
            </td>
            <td>
                <input type="number" name="plans[${planIndex}][duration_days]" class="form-control form-control-sm" value="${durationDays || 30}" placeholder="30" min="1">
            </td>
            <td>
                <input type="number" name="plans[${planIndex}][stock]" class="form-control form-control-sm" value="${stock !== null ? stock : -1}" placeholder="-1" min="-1">
            </td>
            <td>
                <input type="text" name="plans[${planIndex}][plan_note]" class="form-control form-control-sm" value="${escapeHtml(note || '')}" placeholder="VD: Dùng chung">
            </td>
            <td class="text-center align-middle">
                <input type="checkbox" name="plans[${planIndex}][is_popular]" value="1" ${isPopular ? 'checked' : ''} style="cursor:pointer; width:16px; height:16px;">
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-plan" style="padding: 2px 6px;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        `;
        plansContainer.appendChild(tr);

        tr.querySelector('.btn-remove-plan').addEventListener('click', function() {
            tr.remove();
        });

        planIndex++;
    }

    // Populate existing plans from database
    const initialPlans = @json($brandProducts ?? []);
    if (initialPlans && initialPlans.length > 0) {
        initialPlans.forEach(p => {
            const planLabel = p.duration || p.plan || '1 Tháng';
            addPlanRow(p.id, planLabel, p.price, p.original_price, p.duration_days, p.stock, p.plan_note, p.is_popular);
        });
    } else {
        // Fallback for single product
        addPlanRow({{ $product->id }}, '{{ $product->duration ?: "1 Tháng" }}', {{ $product->price }}, {{ $product->original_price ?: "null" }}, {{ $product->duration_days ?: 30 }}, {{ $product->stock }}, '{{ $product->plan_note ?? "" }}', {{ $product->is_popular ? "true" : "false" }});
    }

    if (btnAddPlan) {
        btnAddPlan.addEventListener('click', () => addPlanRow(null, '1 Tháng', '', '', 30, -1, '', false));
    }

    // Dynamic Specs Form
    const specsContainer = document.getElementById('specs-container');
    const btnAddSpec = document.getElementById('btn-add-spec');

    function addSpecRow(name = '', value = '') {
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center spec-row';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="specs_names[]" class="form-control form-control-sm" value="${escapeHtml(name)}" placeholder="Nhãn (VD: Máy chủ)">
            </div>
            <div class="col-md-6">
                <input type="text" name="specs_values[]" class="form-control form-control-sm" value="${escapeHtml(value)}" placeholder="Giá trị (VD: 5,400+)">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-spec" style="padding: 4px 8px;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
        specsContainer.appendChild(row);

        row.querySelector('.btn-remove-spec').addEventListener('click', function() {
            row.remove();
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    const initialSpecs = @json($product->specs ?? []);
    if (initialSpecs && initialSpecs.length > 0) {
        initialSpecs.forEach(spec => addSpecRow(spec.name, spec.value));
    } else {
        const defaults = [
            { name: 'Máy chủ (Servers)', value: '{{ $product->servers ?? "" }}' },
            { name: 'Quốc gia (Countries)', value: '{{ $product->countries ?? "" }}' },
            { name: 'Thiết bị đồng thời', value: '{{ $product->devices ?? "" }}' },
            { name: 'Tốc độ kết nối', value: '{{ $product->speed ?? "" }}' },
            { name: 'Giao thức hỗ trợ', value: '{{ $product->protocol ?? "" }}' },
            { name: 'Trụ sở quốc gia', value: '{{ $product->headquarter ?? "" }}' },
            { name: 'Chính sách hoàn tiền', value: '{{ $product->refund ?? "" }}' }
        ];
        defaults.forEach(d => {
            if (d.value) {
                addSpecRow(d.name, d.value);
            }
        });
    }

    if (btnAddSpec) {
        btnAddSpec.addEventListener('click', () => addSpecRow());
    }
});
</script>
@endsection