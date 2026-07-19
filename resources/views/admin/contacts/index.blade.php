@extends('admin.layouts.admin')

@section('title', 'Tiếp Nhận Email Liên Hệ')
@section('page_title', 'Tiếp Nhận Email Liên Hệ')
@section('breadcrumb', 'Admin / Tiếp Nhận Email')

@section('content')

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(37,99,235,0.1);color:#2563eb;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-envelope-fill" style="font-size:20px;"></i>
                </div>
                <div>
                    <div style="font-size:13px;color:var(--admin-muted);font-weight:600;">Tổng số email</div>
                    <div style="font-size:22px;font-weight:800;color:var(--admin-text);margin-top:2px;">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(245,158,11,0.1);color:#f59e0b;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-clock-history" style="font-size:20px;"></i>
                </div>
                <div>
                    <div style="font-size:13px;color:var(--admin-muted);font-weight:600;">Chưa phản hồi</div>
                    <div style="font-size:22px;font-weight:800;color:var(--admin-text);margin-top:2px;">{{ $stats['pending'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4" style="border-radius:16px;">
            <div class="d-flex align-items-center gap-3">
                <div style="width:48px;height:48px;border-radius:12px;background:rgba(16,185,129,0.1);color:#10b981;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-send-check-fill" style="font-size:20px;"></i>
                </div>
                <div>
                    <div style="font-size:13px;color:var(--admin-muted);font-weight:600;">Đã phản hồi</div>
                    <div style="font-size:22px;font-weight:800;color:var(--admin-text);margin-top:2px;">{{ $stats['replied'] }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="admin-card border-0 shadow-sm" style="border-radius:15px;">
    <div class="admin-card-header border-bottom py-3 px-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="background:#fafafa;">
        <div class="admin-card-title m-0 d-flex align-items-center gap-2" style="font-size:14.5px;">
            <i class="bi bi-envelope-paper-fill text-primary" style="font-size:18px;"></i>
            Danh Sách Liên Hệ
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <!-- Filter & Search Form -->
            <form action="{{ route('admin.contacts.index') }}" method="GET" class="d-flex flex-wrap gap-2 align-items-center">
                <select name="status" class="form-select form-select-sm" style="border-radius:8px; width: 150px;" onchange="this.form.submit()">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chưa phản hồi</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
                </select>

                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Tìm kiếm liên hệ..." style="border-radius:8px; width: 200px;">
                <button type="submit" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
                    <i class="bi bi-search"></i>
                </button>
                @if(request('status') || request('search'))
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-light" style="border-radius:8px;" title="Reset bộ lọc">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size:13.5px;">
                <thead>
                    <tr class="table-light" style="font-weight:700;">
                        <th class="ps-4 py-3" style="width: 50px;">ID</th>
                        <th class="py-3" style="width: 180px;">Khách Hàng</th>
                        <th class="py-3" style="width: 180px;">Email</th>
                        <th class="py-3">Chủ Đề & Tin Nhắn</th>
                        <th class="py-3" style="width: 130px;">Trạng Thái</th>
                        <th class="py-3" style="width: 150px;">Ngày Gửi</th>
                        <th class="py-3 text-end pe-4" style="width: 140px;">Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                    <tr>
                        <td class="ps-4 text-muted fw-600">{{ $contact->id }}</td>
                        <td>
                            <div class="fw-700 text-dark">{{ $contact->name }}</div>
                        </td>
                        <td>
                            <a href="mailto:{{ $contact->email }}" class="text-decoration-none text-primary fw-500">
                                <i class="bi bi-envelope me-1"></i>{{ $contact->email }}
                            </a>
                        </td>
                        <td>
                            <div class="fw-700 text-dark">{{ $contact->subject }}</div>
                            <div class="text-muted text-truncate" style="max-width: 350px; font-size:12px;">
                                {{ $contact->message }}
                            </div>
                        </td>
                        <td>
                            @if($contact->replied_at)
                                <span class="badge bg-success-subtle text-success fw-700" style="font-size:11px; border-radius:6px; padding: 4px 8px;" title="Đã trả lời lúc {{ $contact->replied_at->format('H:i d/m/Y') }}">
                                    <i class="bi bi-check2-all me-1"></i>Đã Phản Hồi
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning fw-700" style="font-size:11px; border-radius:6px; padding: 4px 8px;">
                                    <i class="bi bi-clock me-1"></i>Chờ Trả Lời
                                </span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end pe-4">
                            <div class="d-inline-flex gap-2">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius:6px; padding: 2px 8px;" title="Đọc & phản hồi">
                                    <i class="bi bi-eye-fill me-1"></i>Xem
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:6px; padding: 2px 8px;" title="Xóa">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-envelope-x-fill d-block mb-3" style="font-size:32px; color: var(--admin-muted);"></i>
                            <div class="fw-600">Không tìm thấy tin nhắn liên hệ nào.</div>
                            <div class="small">Hộp thư trống hoặc bộ lọc không khớp.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($contacts->hasPages())
    <div class="admin-card-footer border-top py-3 px-4">
        {{ $contacts->links() }}
    </div>
    @endif
</div>

@endsection
