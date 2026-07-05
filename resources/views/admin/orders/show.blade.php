@extends('admin.layouts.admin')

@section('title', 'Chi Tiết Đơn Hàng')
@section('page_title', 'Chi Tiết Đơn Hàng #' . $order->order_code)
@section('breadcrumb', 'Admin / Đơn Hàng / #' . $order->order_code)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <!-- Order Info -->
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-receipt text-primary"></i>
                    Thông Tin Đơn Hàng
                </div>
                <span class="admin-badge admin-badge-{{ $order->getStatusBadge() }} fs-6">
                    {{ $order->getStatusLabel() }}
                </span>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Mã Đơn Hàng</div>
                            <div class="fw-800 text-primary font-poppins" style="font-size:22px">#{{ $order->order_code }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Ngày Đặt</div>
                            <div class="fw-700" style="font-size:15px">{{ $order->created_at->format('H:i — d/m/Y') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Sản Phẩm</div>
                            <div class="fw-700">{{ $order->product_name }}</div>
                            <div class="text-muted" style="font-size:12.5px">{{ $order->brand }} · {{ ucfirst($order->plan) }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background:#f8fafc;border:1px solid var(--admin-border)">
                            <div class="text-muted mb-1" style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Thanh Toán</div>
                            <div class="fw-700">{{ $order->getPaymentMethodLabel() }}</div>
                            <span class="admin-badge admin-badge-{{ $order->getPaymentBadge() }}" style="font-size:11px">
                                {{ match($order->payment_status){'pending'=>'Chờ TT','paid'=>'Đã TT','failed'=>'Thất Bại','refunded'=>'Hoàn Tiền',default=>$order->payment_status} }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="mt-4 p-3 rounded-3" style="background:linear-gradient(135deg,#dbeafe,#ede9fe);border:1px solid #bfdbfe">
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px">
                        <span>Tạm tính ({{ $order->quantity }}x)</span>
                        <span class="fw-600">{{ number_format($order->price) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" style="font-size:13.5px">
                        <span>Giảm giá</span>
                        <span class="fw-600 text-success">-{{ number_format($order->discount) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between fw-800 border-top pt-2 mt-2" style="font-size:17px">
                        <span>Tổng Cộng</span>
                        <span class="text-primary">{{ number_format($order->total) }}đ</span>
                    </div>
                </div>

                <!-- License Key đã giao -->
                @if($order->license_key)
                <div class="mt-4 p-3 rounded-3" style="background:#dcfce7;border:1px solid #bbf7d0">
                    <div class="fw-700 text-success mb-2" style="font-size:13px">
                        <i class="bi bi-key-fill me-2"></i>Key VPN Đã Giao
                    </div>
                    <code style="font-size:13px;color:#166534;word-break:break-all">{{ $order->license_key }}</code>
                </div>
                @endif
            </div>
        </div>

        <!-- Update Status Form -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-pencil-fill text-primary"></i>
                    Cập Nhật Đơn Hàng
                </div>
            </div>
            <div class="admin-card-body">
                <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:12.5px">Trạng Thái Đơn</label>
                            <select name="order_status" class="form-select" style="border-radius:10px">
                                @foreach(['pending'=>'Chờ Xử Lý','processing'=>'Đang Xử Lý','completed'=>'Hoàn Tất','cancelled'=>'Đã Hủy'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ $order->order_status==$val?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-600" style="font-size:12.5px">Trạng Thái Thanh Toán</label>
                            <select name="payment_status" class="form-select" style="border-radius:10px">
                                @foreach(['pending'=>'Chờ Thanh Toán','paid'=>'Đã Thanh Toán','failed'=>'Thất Bại','refunded'=>'Hoàn Tiền'] as $val=>$lbl)
                                <option value="{{ $val }}" {{ $order->payment_status==$val?'selected':'' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-600" style="font-size:12.5px">Key VPN (lưu vào đơn)</label>
                            <textarea name="license_key" class="form-control" rows="3" placeholder="Nhập key VPN để lưu vào đơn hàng..." style="border-radius:10px;font-family:monospace">{{ $order->license_key }}</textarea>
                        </div>
                        <div class="col-12">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600">
                                    <i class="bi bi-check-circle me-2"></i>Cập Nhật Đơn Hàng
                                </button>
                                <button type="button" onclick="openSendKeyModal()" class="btn btn-success rounded-pill px-4 fw-600">
                                    <i class="bi bi-send-fill me-2"></i>Lưu & Gửi Key Cho Khách
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="col-lg-4">
        <div class="admin-card mb-4">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-person-fill text-primary"></i>
                    Thông Tin Khách Hàng
                </div>
            </div>
            <div class="admin-card-body">
                <!-- Avatar -->
                <div class="text-center mb-4">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,#2563eb,#7c3aed);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:26px;margin:0 auto 10px">
                        {{ strtoupper(substr($order->customer_name,0,1)) }}
                    </div>
                    <div class="fw-800" style="font-size:16px">{{ $order->customer_name }}</div>
                    <div class="text-muted" style="font-size:12.5px">{{ $order->customer_email }}</div>
                </div>

                <!-- Info rows -->
                @foreach([
                    ['bi-envelope','Email', $order->customer_email],
                    ['bi-telephone','Điện thoại', $order->customer_phone ?? '—'],
                    ['bi-chat-dots','Ghi chú', $order->note ?? 'Không có']
                ] as [$icon,$label,$val])
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom" style="border-color:var(--admin-border)!important">
                    <i class="bi {{ $icon }} text-primary mt-1"></i>
                    <div>
                        <div style="font-size:11.5px;color:var(--admin-muted);font-weight:600">{{ $label }}</div>
                        <div style="font-size:13.5px;font-weight:600;word-break:break-all">{{ $val }}</div>
                    </div>
                </div>
                @endforeach

                <!-- ===== ACTION BUTTONS ===== -->
                <div class="d-grid gap-2 mt-3">

                    {{-- Gửi Email Key: mở modal --}}
                    <button type="button" onclick="openSendKeyModal()"
                        class="btn rounded-pill btn-sm fw-600"
                        style="background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;border:none;padding:10px">
                        <i class="bi bi-envelope-paper-fill me-2"></i>Gửi Email Key Cho Khách
                    </button>

                    {{-- Nhắn Zalo: deep link zalo.me --}}
                    @if($order->customer_phone)
                    @php $phone = preg_replace('/[^0-9]/', '', $order->customer_phone); @endphp
                    <a href="https://zalo.me/{{ $phone }}" target="_blank"
                        class="btn btn-outline-success rounded-pill btn-sm fw-600"
                        style="padding:9px">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 24 24" style="vertical-align:-2px">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12.017 24c6.621 0 11.985-5.367 11.985-11.987C24 5.367 18.635.001 12.017.001z"/>
                        </svg>
                        Nhắn Zalo Khách
                    </a>

                    {{-- Gọi điện thoại --}}
                    <a href="tel:{{ $order->customer_phone }}"
                        class="btn btn-outline-secondary rounded-pill btn-sm fw-600"
                        style="padding:9px">
                        <i class="bi bi-telephone-fill me-2"></i>Gọi: {{ $order->customer_phone }}
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Thao Tác Nhanh -->
        <div class="admin-card">
            <div class="admin-card-header">
                <div class="admin-card-title">
                    <i class="bi bi-arrow-left-right text-primary"></i>
                    Thao Tác Nhanh
                </div>
            </div>
            <div class="admin-card-body d-grid gap-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm fw-600">
                    <i class="bi bi-arrow-left me-2"></i>Về Danh Sách
                </a>
                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Xóa đơn hàng #{{ $order->order_code }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger rounded-pill btn-sm fw-600 w-100">
                        <i class="bi bi-trash me-2"></i>Xóa Đơn Hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- =========================================================================
     MODAL: NHẬP KEY → PREVIEW TEMPLATE → GỬI QUA SMTP TRỰC TIẾP
     ========================================================================= --}}
<div class="modal fade" id="sendKeyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:680px">
        <div class="modal-content" style="border-radius:20px;border:none;box-shadow:0 25px 60px rgba(0,0,0,.18);overflow:hidden">

            {{-- Header gradient --}}
            <div class="modal-header border-0" style="background:linear-gradient(135deg,#2563eb 0%,#7c3aed 100%);padding:22px 28px 18px">
                <div>
                    <h5 class="modal-title fw-800 text-white mb-1" style="font-size:17px">
                        <i class="bi bi-envelope-paper-fill me-2"></i>Gửi Key VPN Cho Khách
                    </h5>
                    <div style="color:rgba(255,255,255,.75);font-size:12.5px">
                        Nhập key → Tùy chỉnh tiêu đề & nội dung email → Gửi qua SMTP
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding:24px 28px 16px">

                {{-- Thông tin khách (readonly) --}}
                <div class="p-3 rounded-3 mb-4" style="background:#f0f4ff;border:1.5px solid #c7d7ff">
                    <div class="row g-2">
                        <div class="col-6">
                            <div style="font-size:10.5px;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Gửi tới</div>
                            <div class="fw-700 text-primary" style="font-size:13px">{{ $order->customer_email }}</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:10.5px;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Khách hàng</div>
                            <div class="fw-700" style="font-size:13px">{{ $order->customer_name }}</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:10.5px;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Sản phẩm</div>
                            <div class="fw-600" style="font-size:12.5px">{{ $order->brand }} · {{ ucfirst($order->plan) }}</div>
                        </div>
                        <div class="col-6">
                            <div style="font-size:10.5px;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.5px">Mã đơn</div>
                            <div class="fw-700 text-primary" style="font-size:12.5px">#{{ $order->order_code }}</div>
                        </div>
                    </div>
                </div>

                {{-- Nhập key / tài khoản --}}
                <div class="mb-3">
                    <label class="form-label fw-700 d-flex align-items-center gap-2 mb-2" style="font-size:13px">
                        <i class="bi bi-key-fill text-warning"></i>
                        Key / Tài Khoản VPN
                        <span class="text-danger ms-1">*</span>
                        <span class="ms-auto badge bg-warning-subtle text-warning" style="font-size:10.5px">Bắt buộc</span>
                    </label>
                    <textarea
                        id="keyInput"
                        class="form-control"
                        rows="4"
                        placeholder="Nhập key hoặc thông tin tài khoản VPN...&#10;Ví dụ:&#10;  Email: user@vpn.com&#10;  Mật khẩu: abc123&#10;  Key: XXXX-XXXX-XXXX-XXXX"
                        style="border-radius:12px;font-family:'Courier New',monospace;font-size:13.5px;border:2px solid #e5e7eb;line-height:1.6;transition:border-color .2s"
                        oninput="updateEmailPreview()"
                    >{{ $order->license_key ?? '' }}</textarea>
                    <div class="d-flex gap-2 mt-2">
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="clearKey()" style="font-size:12px">
                            <i class="bi bi-x-circle me-1"></i>Xóa hết
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="copyKey()" id="copyKeyBtn" style="font-size:12px">
                            <i class="bi bi-clipboard me-1"></i>Copy
                        </button>
                    </div>
                </div>

                {{-- Divider --}}
                <div class="d-flex align-items-center gap-3 my-3">
                    <div style="flex:1;height:1px;background:#e5e7eb"></div>
                    <span style="font-size:11.5px;color:#9ca3af;font-weight:600">NỘI DUNG EMAIL GỬI KHÁCH HÀNG</span>
                    <div style="flex:1;height:1px;background:#e5e7eb"></div>
                </div>

                {{-- Tiêu đề Email --}}
                <div class="mb-3">
                    <label class="form-label fw-700 mb-2" style="font-size:13px">Tiêu đề Email</label>
                    <input type="text" id="emailSubject" class="form-control" 
                           style="border-radius:10px;font-size:13px;border:2px solid #e5e7eb;font-weight:600;padding:8px 12px"
                           placeholder="Tiêu đề email...">
                </div>

                {{-- Nội dung Email (Có thể chỉnh sửa) --}}
                <div>
                    <label class="form-label fw-700 mb-2" style="font-size:13px">Nội dung Thư (Có thể chỉnh sửa)</label>
                    <textarea id="emailBody" class="form-control" rows="10"
                              style="border-radius:12px;font-family:'Courier New',monospace;font-size:12.5px;line-height:1.6;border:2px solid #e5e7eb;padding:12px;transition:border-color .2s"></textarea>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0" style="padding:16px 28px 22px;background:#fafafa;gap:10px">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4 fw-600" data-bs-dismiss="modal" style="font-size:13.5px">
                    Đóng
                </button>
                <button type="button" class="btn rounded-pill px-5 fw-700" id="sendEmailBtn" onclick="sendEmailNow()"
                    style="background:linear-gradient(135deg,#2563eb,#7c3aed);color:#fff;border:none;font-size:14px;letter-spacing:.3px">
                    <i class="bi bi-send-fill me-2"></i>Gửi Email Ngay
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_css')
<style>
#keyInput:focus, #emailSubject:focus, #emailBody:focus { border-color: #2563eb !important; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
</style>
@endsection

@section('extra_js')
<script>
// ─── Dữ liệu đơn hàng (server → JS) ─────────────────────────────────────────
const ORDER = {
    id:          @json($order->id),
    code:        @json($order->order_code),
    customer:    @json($order->customer_name),
    email:       @json($order->customer_email),
    brand:       @json($order->brand),
    plan:        @json(ucfirst($order->plan)),
    total:       @json(number_format($order->total)),
    storeName:   @json($settings['store_name'] ?? 'VPNStore'),
    supportTele: @json(ltrim($settings['telegram_support'] ?? 'specademy', '@')),
    supportZalo: @json($settings['zalo_support'] ?? ''),
    existingKey: @json($order->license_key ?? ''),
};

// ─── Mở modal ────────────────────────────────────────────────────────────────
function openSendKeyModal() {
    const keyInput = document.getElementById('keyInput');
    if (ORDER.existingKey && !keyInput.value) {
        keyInput.value = ORDER.existingKey;
    }
    updateEmailPreview();
    new bootstrap.Modal(document.getElementById('sendKeyModal')).show();
}

// ─── Build email template ────────────────────────────────────────────────────
function buildEmailTemplate(keyContent) {
    const key  = (keyContent || '').trim() || '[Chưa nhập key / tài khoản]';
    const line = '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━';
    const support = ORDER.supportZalo
        ? `Zalo: ${ORDER.supportZalo}  |  Telegram: @${ORDER.supportTele}`
        : `Telegram: @${ORDER.supportTele}`;

    return `Xin chào ${ORDER.customer},

Cảm ơn bạn đã tin tưởng đặt hàng tại ${ORDER.storeName}! 🎉

${line}
📦 THÔNG TIN ĐƠN HÀNG
${line}
Mã đơn hàng    : #${ORDER.code}
Sản phẩm       : ${ORDER.brand} (${ORDER.plan})
Tổng thanh toán: ${ORDER.total}đ

${line}
🔑 KEY / TÀI KHOẢN VPN CỦA BẠN
${line}
${key}

${line}
📌 HƯỚNG DẪN KÍCH HOẠT
${line}
1. Tải ứng dụng ${ORDER.brand} chính hãng về thiết bị
2. Đăng nhập bằng thông tin tài khoản ở trên
3. Kết nối VPN và sử dụng ngay!

💬 Nếu cần hỗ trợ kích hoạt miễn phí:
   ${support}

Trân trọng cảm ơn,
Đội ngũ ${ORDER.storeName}`;
}

function buildSubject() {
    return `[${ORDER.storeName}] 🔑 Key VPN ${ORDER.brand} - Đơn #${ORDER.code}`;
}

// ─── Cập nhật preview ────────────────────────────────────────────────────────
function updateEmailPreview() {
    const keyVal  = document.getElementById('keyInput').value;
    document.getElementById('emailSubject').value = buildSubject();
    document.getElementById('emailBody').value = buildEmailTemplate(keyVal);
}

// ─── Gửi email trực tiếp qua AJAX ───────────────────────────────────────────
function sendEmailNow() {
    const keyInput = document.getElementById('keyInput');
    const subjectInput = document.getElementById('emailSubject');
    const bodyInput = document.getElementById('emailBody');

    const key = keyInput.value.trim();
    const subject = subjectInput.value.trim();
    const body = bodyInput.value.trim();

    // Validate key
    if (!key) {
        keyInput.style.borderColor = '#ef4444';
        keyInput.focus();
        keyInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
        // Shake animation
        keyInput.style.animation = 'shake .3s';
        setTimeout(() => { keyInput.style.animation = ''; }, 400);
        return;
    }
    keyInput.style.borderColor = '';

    if (!subject) {
        subjectInput.style.borderColor = '#ef4444';
        subjectInput.focus();
        return;
    }
    subjectInput.style.borderColor = '';

    if (!body) {
        bodyInput.style.borderColor = '#ef4444';
        bodyInput.focus();
        return;
    }
    bodyInput.style.borderColor = '';

    const btn = document.getElementById('sendEmailBtn');
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Đang gửi...';
    btn.disabled = true;

    // Gửi email qua AJAX POST lên Laravel
    fetch(`/admin/don-hang/${ORDER.id}/gui-email`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            license_key: key,
            subject: subject,
            body: body
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw new Error(err.message || 'Lỗi gửi email.'); });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Đã gửi thành công!';
            btn.style.background = 'linear-gradient(135deg,#16a34a,#15803d)';
            setTimeout(() => {
                location.reload();
            }, 1200);
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra.');
        }
    })
    .catch(error => {
        alert(error.message);
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    });
}

// ─── Tiện ích ─────────────────────────────────────────────────────────────────
/* Escaped HTML logic removed since we now edit in standard inputs */
function clearKey() {
    document.getElementById('keyInput').value = '';
    updateEmailPreview();
    document.getElementById('keyInput').focus();
}

function copyKey() {
    const key = document.getElementById('keyInput').value;
    if (!key) return;
    navigator.clipboard.writeText(key).then(() => {
        const btn = document.getElementById('copyKeyBtn');
        const orig = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Đã copy!';
        btn.classList.replace('btn-outline-primary', 'btn-success');
        setTimeout(() => {
            btn.innerHTML = orig;
            btn.classList.replace('btn-success', 'btn-outline-primary');
        }, 1800);
    });
}

// Init
document.addEventListener('DOMContentLoaded', updateEmailPreview);
</script>
<style>
@keyframes shake {
    0%,100% { transform: translateX(0); }
    20%,60%  { transform: translateX(-6px); }
    40%,80%  { transform: translateX(6px); }
}
</style>
@endsection
