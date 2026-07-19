@extends('admin.layouts.admin')
@section('title', 'Thêm Sản Phẩm')
@section('page_title', 'Thêm Sản Phẩm Mới')
@section('breadcrumb', 'Admin / Thêm Sản Phẩm')

@section('topbar_actions')
<a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">← Quay Lại</a>
@endsection

@section('content')

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- Left Column --}}
        <div class="col-lg-8">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-info-circle text-primary"></i> Thông Tin Cơ Bản</div>
                </div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên Sản Phẩm <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="VD: NordVPN Premium - 1 Năm">
                        @error('name')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Danh Mục</label>
                            <select name="category_id" class="form-select">
                                <option value="">— Chọn danh mục —</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Loại Sản Phẩm <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="account" {{ old('type') === 'account' ? 'selected' : '' }}>Tài Khoản</option>
                                <option value="license" {{ old('type') === 'license' ? 'selected' : '' }}>License Key</option>
                                <option value="subscription" {{ old('type') === 'subscription' ? 'selected' : '' }}>Subscription</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Mô Tả</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tính Năng (mỗi dòng một tính năng)</label>
                        <textarea name="features[]" id="features-input" class="form-control" rows="5" placeholder="Mỗi dòng là một tính năng&#10;VD: 6000+ server toàn cầu&#10;Bảo vệ 6 thiết bị&#10;Kill Switch thông minh">{{ old('features') ? implode("\n", old('features')) : '' }}</textarea>
                        <div class="text-muted mt-1" style="font-size:12px;"><i class="bi bi-info-circle-fill text-primary"></i> Mỗi dòng sẽ được hiển thị thành một tính năng riêng</div>
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
                        <!-- Dynamic rows will be inserted here by JavaScript -->
                    </div>
                    <div class="text-muted mt-2" style="font-size:12px;">
                        <i class="bi bi-info-circle-fill text-primary"></i> Bạn có thể tự đặt tên nhãn thông số (bên trái) và giá trị tương ứng (bên phải). Kéo hoặc thêm để sắp xếp hiển thị ở frontend.
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
                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="Tiêu đề SEO (để trống = tên sản phẩm)">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="2" placeholder="Mô tả SEO tối đa 160 ký tự...">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column --}}
        <div class="col-lg-4">
            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-cash text-primary"></i> Giá & Kho</div>
                </div>
                <div class="admin-card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá Bán (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" value="{{ old('price') }}" required min="0" placeholder="49000">
                        @error('price')<div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Giá Gốc (VNĐ)</label>
                        <input type="number" name="original_price" class="form-control" value="{{ old('original_price') }}" min="0" placeholder="Để trống nếu không có giảm giá">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Thời Hạn (Nhãn Hiển Thị)</label>
                        <input type="text" name="duration" class="form-control" value="{{ old('duration') }}" placeholder="1 Tháng / 1 Năm / Lifetime">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tính Hạn Số Ngày <span class="text-danger">*</span></label>
                        <select class="form-select" id="duration_days_select" required>
                            <option value="1" {{ old('duration_days') == 1 ? 'selected' : '' }}>1 Ngày</option>
                            <option value="2" {{ old('duration_days') == 2 ? 'selected' : '' }}>2 Ngày</option>
                            <option value="3" {{ old('duration_days') == 3 ? 'selected' : '' }}>3 Ngày</option>
                            <option value="7" {{ old('duration_days') == 7 ? 'selected' : '' }}>7 Ngày (1 Tuần)</option>
                            <option value="15" {{ old('duration_days') == 15 ? 'selected' : '' }}>15 Ngày (Nửa Tháng)</option>
                            <option value="30" {{ old('duration_days', 30) == 30 ? 'selected' : '' }}>30 Ngày (1 Tháng)</option>
                            <option value="90" {{ old('duration_days') == 90 ? 'selected' : '' }}>90 Ngày (3 Tháng)</option>
                            <option value="180" {{ old('duration_days') == 180 ? 'selected' : '' }}>180 Ngày (6 Tháng)</option>
                            <option value="270" {{ old('duration_days') == 270 ? 'selected' : '' }}>270 Ngày (9 Tháng)</option>
                            <option value="365" {{ old('duration_days') == 365 ? 'selected' : '' }}>365 Ngày (1 Năm)</option>
                            <option value="730" {{ old('duration_days') == 730 ? 'selected' : '' }}>730 Ngày (2 Năm)</option>
                            <option value="1095" {{ old('duration_days') == 1095 ? 'selected' : '' }}>1095 Ngày (3 Năm)</option>
                            <option value="custom" {{ !in_array(old('duration_days', 30), [1,2,3,7,15,30,90,180,270,365,730,1095]) ? 'selected' : '' }}>Tự nhập số ngày...</option>
                        </select>
                        <div id="custom_days_container" class="mt-2" style="display: none;">
                            <input type="number" id="custom_duration_days" class="form-control" min="1" value="{{ old('duration_days', 30) }}" placeholder="Nhập số ngày (Ví dụ: 45)...">
                        </div>
                        <div class="text-muted mt-1" style="font-size:12px;">Dùng để tự động tính toán ngày kết thúc hạn dịch vụ.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Số Lượng Tồn Kho</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', 0) }}" min="-1" placeholder="-1 = không giới hạn">
                        <div class="text-muted mt-1" style="font-size:12px;">-1 = Không giới hạn</div>
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-image text-primary"></i> Hình Ảnh</div>
                </div>
                <div class="admin-card-body">
                    <div style="border:2px dashed var(--admin-border); border-radius:12px; padding:24px; text-align:center; cursor:pointer;" onclick="document.getElementById('image-input').click()">
                        <div id="img-preview" style="display:none; margin-bottom:12px;">
                            <img id="img-preview-src" style="max-height:120px; border-radius:8px; margin:0 auto; display:block;">
                        </div>
                        <div style="margin-bottom:8px;" id="upload-icon"><i class="bi bi-image" style="font-size:1.8rem; color:var(--admin-muted);"></i></div>
                        <div style="font-size:0.8rem; color:var(--admin-muted);">Click để tải lên</div>
                        <div style="font-size:0.7rem; color:var(--admin-muted); margin-top:4px;">PNG, JPG, WEBP (tối đa 2MB)</div>
                        <input type="file" id="image-input" name="image" accept="image/*" style="display:none" onchange="previewImage(this)">
                    </div>
                </div>
            </div>

            <div class="admin-card mb-4">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="bi bi-gear text-primary"></i> Cài Đặt</div>
                </div>
                <div class="admin-card-body">
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Hiển Thị (Active)</label>
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Nổi Bật (Featured)</label>
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Phổ Biến (Popular)</label>
                            <input class="form-check-input" type="checkbox" name="is_popular" value="1" {{ old('is_popular') ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                        <div class="form-check form-switch d-flex justify-content-between align-items-center p-2" style="background:var(--admin-bg); border-radius:8px; border:1px solid var(--admin-border);">
                            <label class="form-check-label fw-bold" style="cursor:pointer; margin-left:8px;">Cần Nhập Email Nâng Cấp</label>
                            <input class="form-check-input" type="checkbox" name="require_upgrade_email" value="1" {{ old('require_upgrade_email') ? 'checked' : '' }} style="cursor:pointer; margin-left:0; float:none;">
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold" style="border-radius:12px;">
                <i class="bi bi-save me-2"></i> Lưu Sản Phẩm
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
    const lines = ta.value.split('\n').filter(l => l.trim());
    
    // Clear any previously appended feature hidden inputs
    this.querySelectorAll('input[name^="features["]').forEach(el => el.remove());
    
    lines.forEach((line, i) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `features[${i}]`;
        input.value = line.trim();
        this.appendChild(input);
    });
});

// Toggle custom duration days
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('duration_days_select');
    const customContainer = document.getElementById('custom_days_container');
    const customInput = document.getElementById('custom_duration_days');

    function toggleCustom() {
        if (!select) return;
        if (select.value === 'custom') {
            customContainer.style.display = 'block';
            customInput.setAttribute('required', 'required');
            customInput.setAttribute('name', 'duration_days');
            select.removeAttribute('name');
        } else {
            customContainer.style.display = 'none';
            customInput.removeAttribute('required');
            customInput.removeAttribute('name');
            select.setAttribute('name', 'duration_days');
        }
    }

    if (select) {
        select.addEventListener('change', toggleCustom);
        toggleCustom();
    }

    // Dynamic specifications form
    const specsContainer = document.getElementById('specs-container');
    const btnAddSpec = document.getElementById('btn-add-spec');

    function addSpecRow(name = '', value = '') {
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-center spec-row';
        row.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="specs_names[]" class="form-control" value="${escapeHtml(name)}" placeholder="Nhãn (VD: Máy chủ, Tốc độ,...)">
            </div>
            <div class="col-md-6">
                <input type="text" name="specs_values[]" class="form-control" value="${escapeHtml(value)}" placeholder="Giá trị (VD: 5,400+, Rất nhanh,...)">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-spec" style="padding: 6px 10px;">
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

    // Default template specs for new product
    const defaults = [
        { name: 'Máy chủ (Servers)', value: '' },
        { name: 'Quốc gia (Countries)', value: '' },
        { name: 'Thiết bị đồng thời', value: '' },
        { name: 'Tốc độ kết nối', value: '' },
        { name: 'Giao thức hỗ trợ', value: '' },
        { name: 'Trụ sở quốc gia', value: '' },
        { name: 'Năm thành lập', value: '' },
        { name: 'Chính sách hoàn tiền', value: '' }
    ];
    defaults.forEach(d => {
        addSpecRow(d.name, d.value);
    });

    if (btnAddSpec) {
        btnAddSpec.addEventListener('click', () => addSpecRow());
    }
});
</script>
@endsection