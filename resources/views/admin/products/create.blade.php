@extends('admin.layouts.admin')

@section('title', 'Thêm Sản Phẩm VPN')
@section('page_title', 'Thêm Gói VPN Mới')
@section('breadcrumb', 'Admin / Sản Phẩm VPN / Thêm Mới')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-shield-plus text-primary"></i>
                    Tạo Gói VPN Mới
                </div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-600 text-dark">Thương Hiệu VPN <span class="text-danger">*</span></label>
                            <select name="brand" class="form-select" required style="border-radius: 10px;">
                                <option value="NordVPN">NordVPN</option>
                                <option value="ExpressVPN">ExpressVPN</option>
                                <option value="Surfshark">Surfshark</option>
                                <option value="HMA VPN">HMA VPN</option>
                                <option value="CyberGhost">CyberGhost</option>
                                <option value="ProtonVPN">ProtonVPN</option>
                                <option value="PureVPN">PureVPN</option>
                                <option value="IPVanish">IPVanish</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600 text-dark">Gán Danh Mục Hệ Thống</label>
                            <select name="category_id" class="form-select" style="border-radius: 10px;">
                                <option value="">-- Không gán --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600 text-dark">Gói Thời Gian <span class="text-danger">*</span></label>
                            <input type="text" name="plan" class="form-control" placeholder="Ví dụ: 1month, 7day, 2year" required style="border-radius: 10px;">
                            <small class="text-muted" style="font-size:10.5px;">Cú pháp: [số][day/month/year]. Ví dụ: 7day, 15day, 3month...</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Tên Gói Hiển Thị <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ví dụ: NordVPN 1 Năm Premium" required style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Giá Bán (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" placeholder="599000" required style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Giá Gốc (đ) <small class="text-muted">(Nếu có)</small></label>
                            <input type="number" name="old_price" class="form-control" placeholder="1200000" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Số Lượng Key Sẵn Có</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="number" name="stock" id="productStock" class="form-control" value="999" style="border-radius: 10px; width:120px;">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="stockToggle" name="is_out_of_stock" value="1">
                                    <label class="form-check-label fw-700 text-danger" for="stockToggle" style="font-size:13px">Bật HẾT HÀNG</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Trạng Thái Kinh Doanh</label>
                            <select name="status" class="form-select" style="border-radius: 10px;">
                                <option value="active">Đang Bán (Active)</option>
                                <option value="inactive">Tạm Ẩn (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Tính Năng Nổi Bật <small class="text-muted">(Phân cách bằng dấu phẩy)</small></label>
                            <input type="text" name="features" class="form-control" placeholder="Ví dụ: 6 thiết bị, 5400+ máy chủ, No-log" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Điểm Đánh Giá (Ảo) <small class="text-muted">(Từ 1.0 đến 5.0)</small></label>
                            <input type="number" step="0.1" min="1" max="5" name="rating" class="form-control" value="4.8" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Số Lượt Đánh Giá (Ảo)</label>
                            <input type="number" min="0" name="reviews" class="form-control" value="120" style="border-radius: 10px;">
                        </div>
                        <!-- Technical Specs -->
                        <div class="col-12 mt-3">
                            <hr class="my-2">
                            <h6 class="fw-800 text-primary mb-3"><i class="bi bi-info-circle me-1"></i>Thông Số Kỹ Thuật (Hiển thị chi tiết)</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Máy chủ</label>
                            <input type="text" name="servers" class="form-control" placeholder="Ví dụ: 3,000+ máy chủ" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Quốc gia</label>
                            <input type="text" name="countries" class="form-control" placeholder="Ví dụ: 94+ quốc gia" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Thiết bị đồng thời</label>
                            <input type="text" name="devices" class="form-control" placeholder="Ví dụ: 5 thiết bị" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Tốc độ</label>
                            <input type="text" name="speed" class="form-control" placeholder="Ví dụ: Siêu Nhanh" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Giao thức</label>
                            <input type="text" name="protocol" class="form-control" placeholder="Ví dụ: Lightway" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Trụ sở</label>
                            <input type="text" name="headquarter" class="form-control" placeholder="Ví dụ: British Virgin Islands" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Thành lập</label>
                            <input type="text" name="founded" class="form-control" placeholder="Ví dụ: 2009" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Chính sách hoàn tiền</label>
                            <input type="text" name="refund" class="form-control" placeholder="Ví dụ: 30 ngày" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Mô Tả Sản Phẩm / Thương Hiệu</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Mô tả tóm tắt dịch vụ VPN..." style="border-radius: 10px;"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Hình Ảnh Sản Phẩm</label>
                            <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" style="border-radius: 10px;">
                            <div class="mt-3 text-start d-none" id="imagePreviewContainer">
                                <div class="text-muted small mb-1">Xem trước ảnh:</div>
                                <img id="imagePreview" src="#" alt="Preview" style="max-height: 120px; border-radius: 8px; border: 1px solid var(--gray-200);">
                            </div>
                        </div>
                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">
                                <i class="bi bi-save me-2"></i>Thêm Sản Phẩm
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                Hủy
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script>
document.getElementById('imageInput')?.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('imagePreviewContainer');
            if (preview && container) {
                preview.src = e.target.result;
                container.classList.remove('d-none');
            }
        }
        reader.readAsDataURL(file);
    }
});

// Out of stock checkbox switch toggle
document.getElementById('stockToggle')?.addEventListener('change', function() {
    const stockInput = document.getElementById('productStock');
    if (stockInput) {
        if (this.checked) {
            // Save current stock in temp attribute if active, then set to 0
            if (stockInput.value > 0) {
                stockInput.dataset.prevStock = stockInput.value;
            }
            stockInput.value = 0;
            stockInput.readOnly = true;
        } else {
            // Restore previous stock or default to 999
            stockInput.value = stockInput.dataset.prevStock || 999;
            stockInput.readOnly = false;
        }
    }
});
</script>
@endsection
