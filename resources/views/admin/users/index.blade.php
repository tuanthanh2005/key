@extends('admin.layouts.admin')

@section('title', 'Quản Lý Người Dùng')
@section('page_title', 'Quản Lý Người Dùng')
@section('breadcrumb', 'Admin / Người Dùng')

@section('content')

<!-- Stats -->
<div class="row g-3 mb-4 row-stat-cards">
    @php
    $uCards = [
        ['label'=>'Tổng Tài Khoản','value'=>$stats['total'],'icon'=>'bi-people-fill','color'=>'#2563eb','bg'=>'#dbeafe'],
        ['label'=>'Admin','value'=>$stats['admins'],'icon'=>'bi-shield-fill-check','color'=>'#7c3aed','bg'=>'#ede9fe'],
        ['label'=>'Khách Hàng','value'=>$stats['users'],'icon'=>'bi-person-fill','color'=>'#16a34a','bg'=>'#dcfce7'],
        ['label'=>'Bị Khóa','value'=>$stats['banned'],'icon'=>'bi-lock-fill','color'=>'#dc2626','bg'=>'#fee2e2'],
    ];
    @endphp
    @foreach($uCards as $c)
    <div class="col-12 col-sm-6 col-md-3">
        <div class="admin-stat-card p-3" style="--card-color:{{ $c['color'] }}">
            <div class="stat-icon" style="background:{{ $c['bg'] }};color:{{ $c['color'] }};width:42px;height:42px;font-size:20px;border-radius:10px">
                <i class="bi {{ $c['icon'] }}"></i>
            </div>
            <div>
                <div class="stat-label">{{ $c['label'] }}</div>
                <div class="stat-value" style="font-size:22px">{{ $c['value'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filters + Add -->
<div class="admin-card mb-4">
    <div class="admin-card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-5">
                <div class="admin-search-bar">
                    <i class="bi bi-search"></i>
                    <input type="text" name="search" placeholder="Tên, email, SĐT..." value="{{ request('search') }}" style="width:100%">
                </div>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select form-select-sm" style="border-radius:10px">
                    <option value="">Tất cả quyền</option>
                    <option value="user" {{ request('role')=='user'?'selected':'' }}>User</option>
                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm" style="border-radius:10px">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="banned" {{ request('status')=='banned'?'selected':'' }}>Bị khóa</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 flex-grow-1" style="font-size:13px">
                    <i class="bi bi-funnel me-1"></i>Lọc
                </button>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm rounded-pill px-3" style="font-size:13px;white-space:nowrap">
                    <i class="bi bi-person-plus me-1"></i>Thêm User
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="bi bi-list-ul text-primary"></i>
            Danh Sách Tài Khoản
            <span class="admin-badge admin-badge-secondary ms-2">{{ $users->total() ?? 0 }}</span>
        </div>
    </div>
    <div class="table-responsive">
        @if($users->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-people" style="font-size:48px;color:var(--admin-border)"></i>
            <p class="mt-3 text-muted">Không có tài khoản nào</p>
        </div>
        @else
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Người Dùng</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Quyền</th>
                    <th>Đăng Nhập</th>
                    <th>Trạng Thái</th>
                    <th>Ngày Tạo</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:38px;height:38px;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:14px;flex-shrink:0">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" style="width:38px;height:38px;border-radius:50%;object-fit:cover">
                                @else
                                    {{ $user->getAvatarInitial() }}
                                @endif
                            </div>
                            <div>
                                <div class="fw-700" style="font-size:13.5px">{{ $user->name }}</div>
                                @if($user->google_id)
                                <span style="font-size:10.5px;color:#4285F4;font-weight:600"><i class="bi bi-google me-1"></i>Google</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;color:var(--admin-muted)">{{ $user->email }}</td>
                    <td style="font-size:13px;color:var(--admin-muted)">{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->role === 'admin')
                        <span class="admin-badge admin-badge-primary"><i class="bi bi-shield-check me-1"></i>Admin</span>
                        @else
                        <span class="admin-badge admin-badge-secondary"><i class="bi bi-person me-1"></i>User</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--admin-muted)">
                        @if($user->google_id)
                        <i class="bi bi-google me-1" style="color:#4285F4"></i>Google
                        @else
                        <i class="bi bi-envelope me-1"></i>Email
                        @endif
                    </td>
                    <td>
                        @if($user->status === 'active')
                        <span class="admin-badge admin-badge-success"><i class="bi bi-check-circle-fill me-1"></i>Active</span>
                        @else
                        <span class="admin-badge admin-badge-danger"><i class="bi bi-lock-fill me-1"></i>Bị khóa</span>
                        @endif
                    </td>
                    <td style="font-size:12px;color:var(--admin-muted)">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" style="border-radius:8px;width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center" title="Sửa">
                                <i class="bi bi-pencil" style="font-size:12px"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $user->status==='active' ? 'btn-outline-warning' : 'btn-outline-success' }}" style="border-radius:8px;width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center" title="{{ $user->status==='active' ? 'Khóa' : 'Mở khóa' }}">
                                    <i class="bi {{ $user->status==='active' ? 'bi-lock' : 'bi-lock-open' }}" style="font-size:12px"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Xóa tài khoản {{ $user->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius:8px;width:30px;height:30px;padding:0;display:flex;align-items:center;justify-content:center" title="Xóa">
                                    <i class="bi bi-trash" style="font-size:12px"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    @if($users->hasPages())
    <div class="admin-card-body border-top d-flex justify-content-between align-items-center">
        <div style="font-size:13px;color:var(--admin-muted)">
            Hiển thị {{ $users->firstItem() }}–{{ $users->lastItem() }} / {{ $users->total() }} tài khoản
        </div>
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
