@extends('admin.layouts.admin')

@section('title', 'Chi Tiết Liên Hệ')
@section('page_title', 'Chi Tiết Liên Hệ')
@section('breadcrumb', 'Admin / Liên Hệ / Chi Tiết')

@section('content')

@php
    $storeName = $settings['store_name'] ?? 'VPNStore';
    $defaultSubject = 'Re: [' . $contact->subject . '] — Phản hồi từ ' . $storeName;
    $defaultContent = "Chào {$contact->name},\n\nCảm ơn bạn đã liên hệ với {$storeName}. Chúng tôi đã nhận được tin nhắn của bạn về vấn đề: \"{$contact->subject}\".\n\n[Nhập nội dung phản hồi của bạn tại đây]\n\nTrân trọng,\nĐội ngũ hỗ trợ {$storeName}";
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm fw-600 px-3" style="border-radius:8px;">
        <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
    </a>
    <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm fw-600 px-3" style="border-radius:8px;">
            <i class="bi bi-trash3-fill me-1"></i> Xóa thư này
        </button>
    </form>
</div>

<div class="row g-4">
    <!-- Left Column: Sender Message Info -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4" style="border-radius:15px; overflow:hidden;">
            <div class="card-header py-3 px-4 bg-light border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-envelope-open-fill text-primary" style="font-size:16px;"></i>
                <span class="fw-700" style="font-size:14px;">Thông Tin Người Gửi</span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div class="row border-bottom pb-2">
                        <div class="col-sm-4 text-muted fw-600" style="font-size:13px;">Họ tên khách:</div>
                        <div class="col-sm-8 fw-700 text-dark">{{ $contact->name }}</div>
                    </div>
                    <div class="row border-bottom pb-2">
                        <div class="col-sm-4 text-muted fw-600" style="font-size:13px;">Email:</div>
                        <div class="col-sm-8">
                            <a href="mailto:{{ $contact->email }}" class="fw-600 text-primary text-decoration-none">
                                <i class="bi bi-envelope me-1"></i>{{ $contact->email }}
                            </a>
                        </div>
                    </div>
                    <div class="row border-bottom pb-2">
                        <div class="col-sm-4 text-muted fw-600" style="font-size:13px;">Chủ đề hỗ trợ:</div>
                        <div class="col-sm-8 fw-700 text-danger">{{ $contact->subject }}</div>
                    </div>
                    <div class="row border-bottom pb-2">
                        <div class="col-sm-4 text-muted fw-600" style="font-size:13px;">Thời gian nhận:</div>
                        <div class="col-sm-8 text-muted fw-500">{{ $contact->created_at->format('H:i:s \n\g\à\y d/m/Y') }} ({{ $contact->created_at->diffForHumans() }})</div>
                    </div>
                    <div>
                        <div class="text-muted fw-600 mb-2" style="font-size:13px;">Nội dung tin nhắn:</div>
                        <div class="p-3 bg-light rounded text-dark border" style="font-size:13.5px; white-space: pre-wrap; line-height: 1.6; border-radius:10px;">{{ $contact->message }}</div>
                    </div>
                </div>
            </div>
        </div>

        @if($contact->replied_at)
        <!-- Previous Reply History -->
        <div class="card border-0 shadow-sm" style="border-radius:15px; overflow:hidden; border-left: 4px solid var(--admin-success) !important;">
            <div class="card-header py-3 px-4 bg-success-subtle text-success border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-check2-circle" style="font-size:16px;"></i>
                <span class="fw-700" style="font-size:14px;">Lịch Sử Phản Hồi</span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex flex-column gap-3">
                    <div>
                        <div class="text-muted small fw-600">Đã gửi phản hồi lúc:</div>
                        <div class="fw-600 text-dark" style="font-size:13px;">
                            {{ $contact->replied_at->format('H:i:s d/m/Y') }} ({{ $contact->replied_at->diffForHumans() }})
                        </div>
                    </div>
                    <div>
                        <div class="text-muted small fw-600">Tiêu đề email đã gửi:</div>
                        <div class="fw-700 text-dark border-bottom pb-2" style="font-size:13.5px;">{{ $contact->reply_subject }}</div>
                    </div>
                    <div>
                        <div class="text-muted small fw-600 mb-1">Nội dung đã gửi:</div>
                        <div class="p-3 bg-success-subtle text-dark border border-success-subtle rounded" style="font-size:13px; white-space: pre-wrap; line-height: 1.5; border-radius:10px;">{{ $contact->reply_content }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Right Column: Reply Email Form -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:15px; overflow:hidden;">
            <div class="card-header py-3 px-4 bg-light border-bottom d-flex align-items-center gap-2">
                <i class="bi bi-reply-fill text-primary" style="font-size:18px;"></i>
                <span class="fw-700" style="font-size:14px;">Gửi Email Phản Hồi Cho Khách</span>
            </div>
            <div class="card-body p-4">
                @if($contact->replied_at)
                    <div class="alert alert-info border-0 shadow-sm mb-4" style="border-radius:10px; font-size:13px;">
                        <i class="bi bi-info-circle-fill me-2"></i> Bạn đã phản hồi thư này. Bạn vẫn có thể gửi thêm email phản hồi mới bằng form dưới đây.
                    </div>
                @endif

                <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST" id="replyForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px;">Email nhận:</label>
                        <input type="text" class="form-control bg-light" value="{{ $contact->email }}" readonly style="border-radius:8px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-600" style="font-size:13px;">Tiêu đề email:</label>
                        <input type="text" name="subject" class="form-control" value="{{ old('subject', $defaultSubject) }}" required style="border-radius:8px;" placeholder="Nhập tiêu đề thư...">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-600" style="font-size:13px;">Nội dung email:</label>
                        <textarea name="content" class="form-control" rows="12" required style="border-radius:8px; font-family: inherit; font-size:13.5px; line-height: 1.6;" placeholder="Nhập nội dung thư phản hồi...">{{ old('content', $defaultContent) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-700 py-2.5 d-flex align-items-center justify-content-center gap-2" style="border-radius:8px;">
                        <i class="bi bi-send-fill"></i> Gửi Email Phản Hồi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
    const replyForm = document.getElementById('replyForm');
    if (replyForm) {
        replyForm.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang gửi email...';
                btn.disabled = true;
            }
        });
    }
</script>
@endsection
