@extends('admin.layouts.admin')

@section('title', 'Quản lý Live Chat Khách Hàng')

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-chat-dots-fill text-primary me-2"></i>Live Chat Hỗ Trợ Khách Hàng</h4>
            <p class="text-muted small mb-0">Trò chuyện trực tiếp và hỗ trợ khách hàng theo thời gian thực</p>
        </div>
        <div>
            <span class="badge bg-primary fs-6" id="admin-unread-total">0 tin nhắn mới</span>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden" style="min-height: 700px; max-height: calc(100vh - 180px);">
        <div class="row g-0 h-100" style="min-height: 700px;">
            <!-- SIDEBAR: SESSIONS LIST -->
            <div class="col-md-4 col-lg-3 border-end bg-light d-flex flex-column" style="min-height: 700px;">
                <div class="p-3 border-bottom bg-white">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="search-session" class="form-control border-start-0 bg-light" placeholder="Tìm theo tên hoặc ID...">
                    </div>
                </div>

                <div class="flex-grow-1 overflow-auto" id="sessions-list" style="height: 620px;">
                    <div class="text-center p-4 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary mb-2"></div>
                        <div>Đang tải danh sách hội thoại...</div>
                    </div>
                </div>
            </div>

            <!-- CHAT AREA -->
            <div class="col-md-8 col-lg-9 d-flex flex-column bg-white" style="min-height: 700px;" id="chat-area-wrap">
                <!-- EMPTY STATE -->
                <div id="chat-empty-state" class="d-flex flex-column align-items-center justify-content-center h-100 text-muted p-5">
                    <i class="bi bi-chat-square-text text-primary display-3 opacity-50 mb-3"></i>
                    <h5>Chọn một cuộc hội thoại để bắt đầu hỗ trợ</h5>
                    <p class="small text-center max-w-md">Danh sách các hội thoại từ khách hàng sẽ hiển thị ở cột bên trái.</p>
                </div>

                <!-- ACTIVE CHAT CONTENT -->
                <div id="chat-active-box" class="d-flex flex-column h-100" style="display: none !important;">
                    <!-- HEADER -->
                    <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px;" id="active-customer-avatar">
                                K
                            </div>
                            <div>
                                <h6 class="fw-bold mb-0" id="active-customer-name">Khách hàng</h6>
                                <small class="text-muted" id="active-session-id">Session ID: ---</small>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary" onclick="refreshMessages()"><i class="bi bi-arrow-clockwise"></i> Làm mới</button>
                    </div>

                    <!-- MESSAGES BODY -->
                    <div class="flex-grow-1 p-3 overflow-auto bg-light" id="admin-chat-messages" style="height: 520px;">
                        <!-- Dynamic Messages -->
                    </div>

                    <!-- IMAGE PREVIEW ATTACHMENT -->
                    <div id="admin-image-preview-bar" class="p-2 border-top bg-white d-flex align-items-center gap-2" style="display: none !important;">
                        <div class="position-relative">
                            <img id="admin-preview-img" src="" alt="Preview" style="max-height: 60px; border-radius: 6px; border: 1px solid #ddd;">
                            <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 p-0 rounded-circle" style="width: 20px; height: 20px; line-height: 1;" onclick="clearAdminImage()">&times;</button>
                        </div>
                        <span class="small text-muted" id="admin-preview-filename"></span>
                    </div>

                    <!-- INPUT FORM -->
                    <div class="p-3 border-top bg-white">
                        <form id="admin-chat-form" onsubmit="sendAdminMessage(event)" class="d-flex gap-2 align-items-center">
                            <input type="file" id="admin-image-input" accept="image/*" class="d-none" onchange="handleAdminImageSelect(this)">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('admin-image-input').click()" title="Đính kèm hình ảnh">
                                <i class="bi bi-image"></i>
                            </button>
                            <input type="text" id="admin-chat-input" class="form-control" placeholder="Nhập tin nhắn trả lời..." autocomplete="off">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" id="admin-send-btn">
                                <i class="bi bi-send-fill me-1"></i> Gửi
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Lightbox Modal -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                <img id="lightbox-image-src" src="" class="img-fluid rounded shadow" style="max-height: 85vh;">
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentSessionId = "{{ $selectedSession ?? '' }}";
let sessionPollInterval = null;
let messagePollInterval = null;
let selectedAdminImageFile = null;

document.addEventListener('DOMContentLoaded', function() {
    loadSessions();
    sessionPollInterval = setInterval(() => {
        if (!document.hidden) loadSessions();
    }, 4000);

    if (currentSessionId) {
        selectSession(currentSessionId);
    }
});

document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        loadSessions();
        if (currentSessionId) loadMessages(currentSessionId);
    }
});

function loadSessions() {
    if (document.hidden) return;
    fetch("{{ route('admin.chat.sessions') }}")
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderSessions(data.sessions);
                document.getElementById('admin-unread-total').textContent = `${data.total_unread} tin nhắn mới`;
            }
        })
        .catch(err => console.error(err));
}

function renderSessions(sessions) {
    const container = document.getElementById('sessions-list');
    const search = document.getElementById('search-session').value.toLowerCase();

    const filtered = sessions.filter(s => 
        s.customer_name.toLowerCase().includes(search) || 
        s.session_id.toLowerCase().includes(search)
    );

    if (filtered.length === 0) {
        container.innerHTML = `<div class="text-center p-4 text-muted">Chưa có cuộc hội thoại nào.</div>`;
        return;
    }

    let html = '';
    filtered.forEach(s => {
        const isActive = s.session_id === currentSessionId ? 'active bg-primary bg-opacity-10 border-start border-primary border-4' : '';
        const unreadBadge = s.unread_count > 0 ? `<span class="badge bg-danger rounded-pill">${s.unread_count}</span>` : '';
        const initial = (s.customer_name || 'K').charAt(0).toUpperCase();

        html += `
            <div class="p-3 border-bottom session-item cursor-pointer ${isActive}" style="cursor: pointer;" onclick="selectSession('${s.session_id}')">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="d-flex align-items-center gap-2">
                        <div class="avatar bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 0.85rem;">
                            ${initial}
                        </div>
                        <strong class="text-dark text-truncate" style="max-width: 130px;">${escapeHtml(s.customer_name)}</strong>
                    </div>
                    <small class="text-muted" style="font-size: 0.75rem;">${s.last_activity}</small>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted small text-truncate mb-0" style="max-width: 170px;">${escapeHtml(s.last_message)}</p>
                    ${unreadBadge}
                </div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function selectSession(sessionId) {
    currentSessionId = sessionId;

    document.getElementById('chat-empty-state').style.setProperty('display', 'none', 'important');
    document.getElementById('chat-active-box').style.setProperty('display', 'flex', 'important');

    document.getElementById('active-session-id').textContent = `Session ID: ${sessionId}`;

    loadMessages(sessionId);

    if (messagePollInterval) clearInterval(messagePollInterval);
    messagePollInterval = setInterval(() => {
        if (!document.hidden) loadMessages(sessionId);
    }, 3000);

    loadSessions();
}

function loadMessages(sessionId) {
    if (document.hidden) return;
    fetch(`{{ url('admin/chat/messages') }}/${sessionId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                renderMessages(data.messages);
            }
        });
}

function refreshMessages() {
    if (currentSessionId) loadMessages(currentSessionId);
}

function renderMessages(messages) {
    const container = document.getElementById('admin-chat-messages');
    const isAtBottom = container.scrollHeight - container.clientHeight <= container.scrollTop + 50;

    let html = '';
    if (messages.length === 0) {
        html = `<div class="text-center text-muted p-4">Chưa có tin nhắn nào.</div>`;
    } else {
        const customerName = messages.find(m => m.sender_type === 'customer')?.sender_name || 'Khách hàng';
        document.getElementById('active-customer-name').textContent = customerName;
        document.getElementById('active-customer-avatar').textContent = customerName.charAt(0).toUpperCase();

        messages.forEach(m => {
            const isAdmin = m.sender_type === 'admin';
            const align = isAdmin ? 'justify-content-end' : 'justify-content-start';
            const bubbleBg = isAdmin ? 'bg-primary text-white' : 'bg-white text-dark shadow-sm border';

            let imgHtml = '';
            if (m.image_url) {
                imgHtml = `<div class="mt-2"><img src="${m.image_url}" onclick="openLightbox('${m.image_url}')" style="max-width: 240px; border-radius: 8px; cursor: pointer;" class="img-thumbnail"></div>`;
            }

            let msgText = m.message ? `<div>${escapeHtml(m.message)}</div>` : '';

            html += `
                <div class="d-flex ${align} mb-3">
                    <div style="max-width: 70%;">
                        <div class="small text-muted mb-1 ${isAdmin ? 'text-end' : ''}">${escapeHtml(m.sender_name)} • ${m.created_at}</div>
                        <div class="p-3 rounded-3 ${bubbleBg}">
                            ${msgText}
                            ${imgHtml}
                        </div>
                    </div>
                </div>
            `;
        });
    }

    container.innerHTML = html;
    if (isAtBottom) {
        container.scrollTop = container.scrollHeight;
    }
}

function handleAdminImageSelect(input) {
    if (input.files && input.files[0]) {
        selectedAdminImageFile = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('admin-preview-img').src = e.target.result;
            document.getElementById('admin-preview-filename').textContent = selectedAdminImageFile.name;
            document.getElementById('admin-image-preview-bar').style.setProperty('display', 'flex', 'important');
        };
        reader.readAsDataURL(selectedAdminImageFile);
    }
}

function clearAdminImage() {
    selectedAdminImageFile = null;
    document.getElementById('admin-image-input').value = '';
    document.getElementById('admin-image-preview-bar').style.setProperty('display', 'none', 'important');
}

function sendAdminMessage(e) {
    e.preventDefault();
    if (!currentSessionId) return;

    const input = document.getElementById('admin-chat-input');
    const text = input.value.trim();

    if (!text && !selectedAdminImageFile) return;

    const btn = document.getElementById('admin-send-btn');
    btn.disabled = true;

    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('session_id', currentSessionId);
    if (text) formData.append('message', text);
    if (selectedAdminImageFile) formData.append('image', selectedAdminImageFile);

    fetch("{{ route('admin.chat.send') }}", {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            input.value = '';
            clearAdminImage();
            loadMessages(currentSessionId);
            loadSessions();
        } else {
            alert(data.message || 'Lỗi gửi tin nhắn');
        }
    })
    .catch(err => {
        btn.disabled = false;
        console.error(err);
    });
}

function openLightbox(url) {
    document.getElementById('lightbox-image-src').src = url;
    const modal = new bootstrap.Modal(document.getElementById('imageLightboxModal'));
    modal.show();
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}
</script>
@endpush
