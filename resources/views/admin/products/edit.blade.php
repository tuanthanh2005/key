@extends('admin.layouts.admin')

@section('title', 'Sửa Sản Phẩm VPN')
@section('page_title', 'Sửa Gói: ' . $product->name)
@section('breadcrumb', 'Admin / Sản Phẩm VPN / Sửa Gói')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-pencil-fill text-primary"></i>
                    Chỉnh Sửa Gói VPN
                </div>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-600 text-dark">Thương Hiệu VPN <span class="text-danger">*</span></label>
                            <select name="brand" class="form-select" required style="border-radius: 10px;">
                                @foreach(['NordVPN', 'ExpressVPN', 'Surfshark', 'HMA VPN', 'CyberGhost', 'ProtonVPN', 'PureVPN', 'IPVanish'] as $brand)
                                    <option value="{{ $brand }}" {{ $product->brand == $brand ? 'selected' : '' }}>{{ $brand }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600 text-dark">Gán Danh Mục Hệ Thống</label>
                            <select name="category_id" class="form-select" style="border-radius: 10px;">
                                <option value="">-- Không gán --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-600 text-dark">Gói Thời Gian <span class="text-danger">*</span></label>
                            <input type="text" name="plan" class="form-control" value="{{ $product->plan }}" placeholder="Ví dụ: 1month, 7day, 2year" required style="border-radius: 10px;">
                            <small class="text-muted" style="font-size:10.5px;">Cú pháp: [số][day/month/year]. Ví dụ: 7day, 15day, 3month...</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Tên Gói Hiển Thị <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Giá Bán (đ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control" value="{{ intval($product->price) }}" required style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Giá Gốc (đ)</label>
                            <input type="number" name="old_price" class="form-control" value="{{ $product->old_price ? intval($product->old_price) : '' }}" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Số Lượng Key Sẵn Có</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="number" name="stock" id="productStock" class="form-control" value="{{ $product->stock }}" style="border-radius: 10px; width:120px;" {{ $product->stock <= 0 ? 'readonly' : '' }}>
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="stockToggle" name="is_out_of_stock" value="1" {{ $product->stock <= 0 ? 'checked' : '' }}>
                                    <label class="form-check-label fw-700 text-danger" for="stockToggle" style="font-size:13px">Bật HẾT HÀNG</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Trạng Thái Kinh Doanh</label>
                            <select name="status" class="form-select" style="border-radius: 10px;">
                                <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Đang Bán (Active)</option>
                                <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Tạm Ẩn (Inactive)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Tính Năng Nổi Bật <small class="text-muted">(Phân cách bằng dấu phẩy)</small></label>
                            <input type="text" name="features" class="form-control" value="{{ is_array($product->features) ? implode(', ', $product->features) : '' }}" placeholder="Ví dụ: 6 thiết bị, 5400+ máy chủ, No-log" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Điểm Đánh Giá (Ảo) <small class="text-muted">(Từ 1.0 đến 5.0)</small></label>
                            <input type="number" step="0.1" min="1" max="5" name="rating" class="form-control" value="{{ $product->rating }}" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600 text-dark">Số Lượt Đánh Giá (Ảo)</label>
                            <input type="number" min="0" name="reviews" class="form-control" value="{{ $product->reviews }}" style="border-radius: 10px;">
                        </div>
                        <!-- Technical Specs -->
                        <div class="col-12 mt-3">
                            <hr class="my-2">
                            <h6 class="fw-800 text-primary mb-3"><i class="bi bi-info-circle me-1"></i>Thông Số Kỹ Thuật (Hiển thị chi tiết)</h6>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Máy chủ</label>
                            <input type="text" name="servers" class="form-control" value="{{ $product->servers }}" placeholder="Ví dụ: 3,000+ máy chủ" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Quốc gia</label>
                            <input type="text" name="countries" class="form-control" value="{{ $product->countries }}" placeholder="Ví dụ: 94+ quốc gia" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Thiết bị đồng thời</label>
                            <input type="text" name="devices" class="form-control" value="{{ $product->devices }}" placeholder="Ví dụ: 5 thiết bị" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Tốc độ</label>
                            <input type="text" name="speed" class="form-control" value="{{ $product->speed }}" placeholder="Ví dụ: Siêu Nhanh" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Giao thức</label>
                            <input type="text" name="protocol" class="form-control" value="{{ $product->protocol }}" placeholder="Ví dụ: Lightway" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Trụ sở</label>
                            <input type="text" name="headquarter" class="form-control" value="{{ $product->headquarter }}" placeholder="Ví dụ: British Virgin Islands" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Thành lập</label>
                            <input type="text" name="founded" class="form-control" value="{{ $product->founded }}" placeholder="Ví dụ: 2009" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-600 text-dark">Chính sách hoàn tiền</label>
                            <input type="text" name="refund" class="form-control" value="{{ $product->refund }}" placeholder="Ví dụ: 30 ngày" style="border-radius: 10px;">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Mô Tả Sản Phẩm / Thương Hiệu</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="Mô tả tóm tắt dịch vụ VPN..." style="border-radius: 10px;">{{ $product->description }}</textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-600 text-dark">Hình Ảnh Sản Phẩm</label>
                            <input type="file" name="image" id="imageInput" class="form-control" accept="image/*" style="border-radius: 10px;">
                            
                            <div class="mt-3 text-start {{ !empty($product->image_path) ? '' : 'd-none' }}" id="imagePreviewContainer">
                                <div class="text-muted small mb-1">Ảnh hiện tại / ảnh mới chọn:</div>
                                <img id="imagePreview" src="{{ !empty($product->image_path) ? asset($product->image_path) : '#' }}" alt="Preview" style="max-height: 120px; border-radius: 8px; border: 1px solid var(--gray-200);">
                            </div>
                        </div>
                        <div class="col-12 mt-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">
                                <i class="bi bi-save me-2"></i>Lưu Thay Đổi
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
