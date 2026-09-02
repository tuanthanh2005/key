@extends('admin.layouts.admin')
@section('title', 'Quản Lý Sản Phẩm')
@section('page_title', 'Quản Lý Sản Phẩm')

@section('topbar_actions')
<a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Thêm Thương Hiệu & Gói Mới</a>
@endsection

@section('content')

{{-- Filters --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:16px;">
    <form method="GET" action="{{ route('admin.products.index') }}" style="display:flex; gap:10px; align-items:center; flex:1; max-width:480px;">
        <div class="search-bar" style="width:100%; max-width:320px; border:1px solid var(--border); border-radius:var(--radius); padding:6px 12px; background:var(--bg-input); display:flex; align-items:center; gap:8px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-muted);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="q" placeholder="Tìm kiếm sản phẩm, thương hiệu..." value="{{ request('q') }}" style="border:none; background:none; outline:none; color:var(--text-primary); font-size:0.85rem; width:100%;">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Tìm kiếm</button>
    </form>
    
    <div style="display:flex; gap:10px;">
        <a href="{{ route('admin.products.index') }}" class="btn btn-ghost btn-sm" style="border: 1px solid var(--border); color: var(--text-secondary);"><i class="bi bi-arrow-clockwise" style="margin-right:4px;"></i> Làm mới</a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Thêm sản phẩm mới</a>
    </div>
</div>

<div class="table-wrapper" style="background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-lg); overflow:hidden;">
    <table class="table" style="width:100%; border-collapse:collapse; text-align:left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--border); background:rgba(0,0,0,0.02);">
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Thương Hiệu & Sản Phẩm</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Các Gói Thời Hạn & Giá</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700;">Trạng Thái</th>
                <th style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase; padding:16px; font-weight:700; text-align:center; width:140px;">Thao Tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groupedProducts as $brandName => $plans)
            @php
                $firstProd = $plans->first();
                $isBrandActive = $plans->contains('is_active', true);
            @endphp
            <tr style="border-bottom: 1px solid var(--border); transition: background 0.2s;" onmouseover="this.style.background='rgba(0,0,0,0.01)'" onmouseout="this.style.background='none'">
                <td style="padding:16px; vertical-align:top;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:48px; height:48px; border-radius:10px; overflow:hidden; background:var(--bg-base); flex-shrink:0; display:flex; align-items:center; justify-content:center; border: 1px solid var(--border); padding:4px;">
                            @if($firstProd->image_url)
                                <img src="{{ $firstProd->image_url }}" style="width:100%; height:100%; object-fit:contain;">
                            @else
                                <span style="font-size:1.4rem; color: #7c3aed;"><i class="bi bi-shield-lock-fill"></i></span>
                            @endif
                        </div>
                        <div>
                            <div style="font-weight:700; font-size:0.95rem; color:var(--text-primary);">{{ $brandName }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">
                                Slug: <code style="color:var(--primary-light);">{{ $firstProd->slug }}</code>
                                @if($firstProd->category)
                                    • <span class="badge bg-light text-dark border">{{ $firstProd->category->name }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
                <td style="padding:16px; vertical-align:top;">
                    <div style="display:flex; flex-wrap:wrap; gap:8px; align-items:center;">
                        @foreach($plans as $plan)
                        <div style="display:inline-flex; align-items:center; gap:6px; padding:4px 10px; background:var(--bg-base); border:1px solid var(--border); border-radius:8px; font-size:0.8rem;">
                            <span style="font-weight:700; color:var(--text-primary);">{{ $plan->duration ?: ($plan->plan ?: 'Gói') }}:</span>
                            <span style="font-weight:800; color:var(--primary-light); font-family:var(--font-mono);">{{ number_format($plan->price, 0, ',', '.') }}đ</span>
                            @if($plan->is_popular)
                                <span style="font-size:0.65rem; background:rgba(245,158,11,0.15); color:#f59e0b; padding:1px 5px; border-radius:4px; font-weight:700;">Nổi Bật</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </td>
                <td style="padding:16px; vertical-align:top;">
                    <div style="display:flex; flex-direction:column; gap:6px; align-items:flex-start;">
                        @if($isBrandActive)
                            <span style="padding: 3px 10px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2);">
                                ● ACTIVE
                            </span>
                        @else
                            <span style="padding: 3px 10px; border-radius: 9999px; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2);">
                                ● INACTIVE
                            </span>
                        @endif

                        <form action="{{ route('admin.products.toggle', $firstProd->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            <select onchange="this.form.submit()" style="background: var(--bg-input); border: 1px solid var(--border); color: var(--text-secondary); font-size: 0.72rem; padding: 4px 16px 4px 8px; border-radius: 6px; cursor: pointer; outline:none;">
                                <option value="1" {{ $isBrandActive ? 'selected' : '' }}>Active tất cả</option>
                                <option value="0" {{ !$isBrandActive ? 'selected' : '' }}>Inactive tất cả</option>
                            </select>
                        </form>
                    </div>
                </td>
                <td style="padding:16px; text-align:center; vertical-align:top;">
                    <div style="display:flex; justify-content:center; gap:8px;">
                        <a href="{{ route('admin.products.edit', $firstProd->id) }}" class="btn btn-ghost btn-sm" style="padding:6px 10px; color:var(--primary-light); border:1px solid var(--border);" title="Sửa tất cả các gói thuộc {{ $brandName }}">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <a href="{{ route('admin.licenses.index', ['product_id' => $firstProd->id]) }}" class="btn btn-ghost btn-sm" style="padding:6px 10px; color:var(--warning); border:1px solid var(--border);" title="Quản lý Kho Key / Tài khoản">
                            <i class="bi bi-key-fill"></i>
                        </a>
                        <form action="{{ route('admin.products.destroy', $firstProd->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tất cả các gói thuộc {{ $brandName }} không?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="delete_all" value="1">
                            <button type="submit" class="btn btn-ghost btn-sm text-danger" style="padding:6px 10px; border:1px solid var(--border);" title="Xóa thương hiệu & tất cả các gói">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center; padding:48px; color:var(--text-muted);">
                    <i class="bi bi-inbox text-muted" style="font-size: 2.5rem; display:block; margin-bottom:12px;"></i>
                    Chưa có sản phẩm nào được tạo. Bấm <strong>+ Thêm Sản Phẩm Mới</strong> để bắt đầu.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection