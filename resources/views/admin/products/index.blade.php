@extends('admin.layouts.admin')
@section('title', 'Quản Lý Sản Phẩm')
@section('page_title', 'Quản Lý Sản Phẩm')

@section('topbar_actions')
<a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Thêm Sản Phẩm</a>
@endsection

@section('content')

{{-- Filters --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:16px;">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex; gap:10px; align-items:center; flex:1; max-width:480px;">
        <div class="search-bar" style="width:100%; max-width:320px; border:1px solid var(--border); border-radius:var(--radius); padding:6px 12px; background:var(--bg-input); display:flex; align-items:center; gap:8px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="q" placeholder="Tìm kiếm sản phẩm..." value="{{ request('q') }}" style="border:none; background:none; outline:none; color:var(--text-primary); font-size:0.85rem; width:100%;">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
    </form>
    
    <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost btn-sm" style="border: 1px solid var(--border); color: var(--text-secondary);"><i class="bi bi-arrow-clockwise" style="margin-right:4px;"></i> Làm mới</a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Thêm sản phẩm</a>
    </div>
</div>

<div class="table-wrapper" style="background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden;">
    <table class="table" style="width:100%; border-collapse:collapse; text-align:left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--border); background:rgba(0,0,0,0.02);">
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Sản phẩm</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Giá</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Trạng thái</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Tài khoản</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Đánh giá</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700; width:120px;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.01)'" onmouseout="this.style.background='none'">
                <td style="padding:16px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:40px; height:40px; border-radius:8px; overflow:hidden; background:var(--bg-base); flex-shrink:0; display:flex; align-items:center; justify-content:center; border: 1px solid var(--border);">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                                @php 
                                    $catIcons = [
                                        'vpn-premium' => 'bi bi-shield-lock-fill',
                                        'ai-code' => 'bi bi-cpu-fill',
                                        'design-software' => 'bi bi-palette-fill',
                                        'xem-phim-premium' => 'bi bi-play-btn-fill'
                                    ];
                                    $iconClass = $product->category ? ($catIcons[$product->category->slug] ?? 'bi bi-box-seam-fill') : 'bi bi-box-seam-fill';
                                @endphp
                                <span style="font-size:1.2rem; color: #3b82f6;"><i class="{{ $iconClass }}"></i></span>
                            @endif
                        </div>
                        <div>
                            <div style="font-weight:600; font-size:0.875rem; color:var(--text-primary);">{{ $product->name }}</div>
                            <div style="font-size:0.72rem; color:var(--text-muted); margin-top:2px;">ID: {{ $product->slug }}</div>
                        </div>
                    </div>
                </td>
                <td style="padding:16px;">
                    <div style="display:flex; flex-direction:column; line-height: 1.3;">
                        <span style="font-weight: 700; color: var(--text-primary); font-family: var(--font-mono); font-size: 0.9rem;">
                            {{ number_format($product->price, 0, ',', '.') }} đ
                        </span>
                        @if($product->original_price && $product->original_price > $product->price)
                        <span style="font-size: 0.75rem; color: var(--text-muted); text-decoration: line-through; font-family: var(--font-mono);">
                            {{ number_format($product->original_price, 0, ',', '.') }} đ
                        </span>
                        @endif
                    </div>
                </td>
                <td style="padding:16px;">
                    <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-start;">
                        @if($product->is_active)
                            <span style="padding: 2px 8px; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); display: inline-flex; align-items: center; gap: 4px;">
                                ● Active
                            </span>
                        @else
                            <span style="padding: 2px 8px; border-radius: 9999px; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); display: inline-flex; align-items: center; gap: 4px;">
                                ● Inactive
                            </span>
                        @endif

                        <form action="{{ route('admin.products.toggle', $product->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <select onchange="this.form.submit()" style="background: var(--bg-input); border: 1px solid var(--border); color: var(--text-secondary); font-size: 0.72rem; padding: 4px 20px 4px 8px; border-radius: 6px; cursor: pointer; outline:none;">
                                <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </form>
                    </div>
                </td>
                <td style="padding:16px;">
                    <span style="padding: 4px 10px; background: var(--bg-base); border: 1px solid var(--border); border-radius: 9999px; font-size: 0.75rem; color: var(--text-secondary); font-weight: 600; white-space: nowrap;">
                        {{ $product->available_licenses_count }} tài khoản
                    </span>
                </td>
                <td style="padding:16px;">
                    <div style="display:flex; align-items:center; gap:6px; cursor:pointer;" data-bs-toggle="modal" data-bs-target="#editRatingModal-{{ $product->id }}" title="Click để sửa nhanh đánh giá">
                        <i class="bi bi-star-fill" style="color:#eab308; font-size: 0.85rem; display:inline-flex;"></i>
                        <span style="font-weight: 600; color: var(--text-primary); font-size: 0.85rem; border-bottom: 1px dashed var(--text-muted);">{{ number_format($product->rating, 1) }}</span>
                        <span style="color: #64748b; font-size: 0.85rem;">({{ $product->reviews }})</span>
                    </div>
                </td>
                <td style="padding:16px;">
                    <div style="display:flex; gap:6px; align-items:center;">
                        <a href="{{ route('admin.licenses.index', ['product' => $product->id]) }}" class="btn btn-ghost btn-icon btn-sm" style="border: 1px solid var(--border); border-radius: 8px; color: var(--success); padding: 6px; text-decoration:none;" title="Xem License"><i class="bi bi-shield-check"></i></a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-ghost btn-icon btn-sm" style="border: 1px solid var(--border); border-radius: 8px; color: var(--info); padding: 6px; text-decoration:none;" title="Sửa"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="margin: 0; display: inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-icon btn-sm" style="border: 1px solid var(--border); border-radius: 8px; color: var(--danger); padding: 6px; background: none;" title="Xóa" onclick="return confirm('Xóa sản phẩm này?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- EDIT QUICK RATING MODAL -->
            <div class="modal fade" id="editRatingModal-{{ $product->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
                    <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 8px 30px rgba(0,0,0,0.12); background: var(--bg-surface);">
                        <div class="modal-header border-bottom py-3 px-4">
                            <h6 class="modal-title fw-bold text-dark"><i class="bi bi-star-fill text-warning me-2"></i>Cài đặt đánh giá: {{ $product->name }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('admin.products.update-rating', $product->id) }}" method="POST" style="margin:0;">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Điểm Đánh Giá (Sao) <span class="text-danger">*</span></label>
                                    <input type="number" name="rating" class="form-control" step="0.1" min="1" max="5" value="{{ $product->rating }}" required>
                                    <div class="form-text" style="font-size: 11px;">Nhập số từ 1.0 đến 5.0 (Ví dụ: 4.8)</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Số Lượt Đánh Giá <span class="text-danger">*</span></label>
                                    <input type="number" name="reviews" class="form-control" min="0" value="{{ $product->reviews }}" required>
                                    <div class="form-text" style="font-size: 11px;">Số lượng đánh giá ảo ban đầu (Ví dụ: 120)</div>
                                </div>
                            </div>
                            <div class="modal-footer border-top py-3 px-4" style="background:rgba(0,0,0,0.02);">
                                <button type="button" class="btn btn-sm btn-outline-secondary px-3" data-bs-dismiss="modal" style="border-radius:20px;">Hủy</button>
                                <button type="submit" class="btn btn-sm btn-primary px-4" style="border-radius:20px;">Lưu Cài Đặt</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:40px; color:#64748b;">
                    Chưa có sản phẩm nào. <a href="{{ route('admin.products.create') }}" style="color:#3b82f6;">Thêm ngay →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($products->hasPages())
    <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.01);">
        <div style="font-size: 13px; color: var(--text-muted);">
            Hiển thị {{ $products->firstItem() }}–{{ $products->lastItem() }} / {{ $products->total() }} sản phẩm
        </div>
        <div>
            {{ $products->links() }}
        </div>
    </div>
    @endif
</div>

@endsection