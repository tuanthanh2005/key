@extends('layouts.app')

@section('title', 'Liên Hệ Hỗ Trợ - ' . ($settings['store_name'] ?? 'VPNStore'))
@section('meta_description', 'Liên hệ với chúng tôi để nhận hỗ trợ mua key VPN, Proxy chính hãng và bảo hành tài khoản 24/7.')

@section('content')

<section class="section animate-on-scroll">
    <div class="container" style="max-width:960px;">
        <div style="text-align:center; margin-bottom:48px;">
            <span class="badge" style="background:rgba(124,58,237,0.1); color:var(--primary-light); padding:6px 16px; border-radius:var(--radius-full); font-size:0.8rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; display:inline-block; margin-bottom:12px;">Liên Hệ</span>
            <h1 style="font-size:2.2rem; font-weight:800; color:var(--text-primary); margin-bottom:12px;">Liên Hệ Với Chúng Tôi</h1>
            <p style="color:var(--text-secondary); max-width:600px; margin:0 auto; font-size:0.95rem;">Bạn cần hỗ trợ kỹ thuật, thắc mắc về sản phẩm hay yêu cầu bảo hành? Chúng tôi luôn sẵn sàng phục vụ bạn 24/7.</p>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1.2fr; gap:40px; align-items:stretch;" class="contact-layout-wrap">
            {{-- Contact Information --}}
            <div style="display:flex; flex-direction:column; gap:20px;">
                <div class="card" style="padding:24px; flex:1; display:flex; flex-direction:column; justify-content:center;">
                    <h3 style="font-size:1.1rem; font-weight:700; margin-bottom:20px; color:var(--text-primary); display:flex; align-items:center; gap:8px;">
                        <i class="bi bi-info-circle-fill" style="color:var(--primary-light);"></i> Thông Tin Hỗ Trợ
                    </h3>
                    
                    <div style="display:flex; flex-direction:column; gap:24px;">
                        @if(!empty($settings['contact_email']) || !empty($settings['zalo_support']) || !empty($settings['telegram_support']))
                            @if(!empty($settings['contact_email']) || empty($settings['contact_email']))
                            <div style="display:flex; gap:16px; align-items:flex-start;">
                                <div style="width:40px; height:40px; border-radius:50%; background:rgba(124,58,237,0.1); color:var(--primary-light); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase;">Email hỗ trợ</div>
                                    <div style="font-weight:600; color:var(--text-primary); margin-top:2px;">{{ $settings['contact_email'] ?? 'tetuongmmovn@gmail.com' }}</div>
                                </div>
                            </div>
                            @endif

                            <div style="display:flex; gap:16px; align-items:flex-start;">
                                <div style="width:40px; height:40px; border-radius:50%; background:#0088cc1e; color:#0088cc; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="bi bi-telegram"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase;">Kênh Telegram</div>
                                    <div style="font-weight:600; color:var(--text-primary); margin-top:2px;">
                                        <a href="{{ $settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'] ?? 'specademy', '@') }}" target="_blank" style="color:#0088cc; text-decoration:none;">
                                            {{ '@' . ltrim($settings['telegram_support'] ?? 'specademy', '@') }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; gap:16px; align-items:flex-start;">
                                <div style="width:40px; height:40px; border-radius:50%; background:#25d3661e; color:#25d366; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </div>
                                <div>
                                    <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase;">Zalo Hỗ Trợ</div>
                                    <div style="font-weight:600; color:var(--text-primary); margin-top:2px; display:flex; flex-direction:column; gap:4px;">
                                        <a href="{{ $settings['zalo_url_1'] ?? 'https://zalo.me/' . ($settings['zalo_support'] ?? '0708910952') }}" target="_blank" style="color:#25d366; text-decoration:none;">
                                            Zalo 1: {{ $settings['zalo_support'] ?? '0708910952' }}
                                        </a>
                                        @if(!empty($settings['zalo_support_2']))
                                        <a href="{{ $settings['zalo_url_2'] ?? 'https://zalo.me/' . $settings['zalo_support_2'] }}" target="_blank" style="color:#25d366; text-decoration:none;">
                                            Zalo 2: {{ $settings['zalo_support_2'] }}
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card" style="padding:20px; background:rgba(124,58,237,0.05); border-color:var(--primary-light);">
                    <h4 style="font-size:0.9rem; font-weight:700; color:var(--primary-light); margin-bottom:8px;"><i class="bi bi-shield-fill-check"></i> Cam Kết Hỗ Trợ 24/7</h4>
                    <p style="font-size:0.8rem; color:var(--text-secondary); line-height:1.5; margin:0;">Mọi thắc mắc về cài đặt hoặc bảo hành key VPN đều được nhân viên kỹ thuật phản hồi trực tuyến nhanh chóng.</p>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="card" style="padding:32px;">
                <h3 style="font-size:1.2rem; font-weight:700; margin-bottom:20px; color:var(--text-primary);">Gửi Tin Nhắn Hỗ Trợ</h3>
                <form action="#" method="POST" id="contactForm">
                    @csrf
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:16px;">
                        <div>
                            <label style="display:block; font-size:0.8rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px;">Họ Tên</label>
                            <input type="text" placeholder="Nguyễn Văn A" required style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:var(--radius-md); background:var(--bg-input); color:var(--text-primary); outline:none;">
                        </div>
                        <div>
                            <label style="display:block; font-size:0.8rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px;">Email</label>
                            <input type="email" placeholder="example@gmail.com" required style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:var(--radius-md); background:var(--bg-input); color:var(--text-primary); outline:none;">
                        </div>
                    </div>

                    <div style="margin-bottom:16px;">
                        <label style="display:block; font-size:0.8rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px;">Chủ Đề</label>
                        <select required style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:var(--radius-md); background:var(--bg-input); color:var(--text-primary); outline:none;">
                            <option value="">-- Chọn vấn đề cần hỗ trợ --</option>
                            <option value="buy">Tư vấn mua tài khoản</option>
                            <option value="warranty">Bảo hành tài khoản</option>
                            <option value="other">Ý kiến đóng góp khác</option>
                        </select>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:0.8rem; font-weight:600; color:var(--text-secondary); margin-bottom:6px;">Nội Dung Tin Nhắn</label>
                        <textarea placeholder="Nhập tin nhắn chi tiết tại đây..." rows="5" required style="width:100%; padding:10px 14px; border:1px solid var(--border); border-radius:var(--radius-md); background:var(--bg-input); color:var(--text-primary); outline:none; resize:vertical;"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full btn-lg">Gửi Liên Hệ <i class="bi bi-send-fill" style="margin-left:6px;"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
const contactForm = document.getElementById('contactForm');
if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';
            btn.disabled = true;
        }
        setTimeout(() => {
            showToast('Tin nhắn đã được gửi thành công! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.', 'success');
            this.reset();
            if (btn) { btn.innerHTML = 'Gửi Liên Hệ <i class="bi bi-send-fill" style="margin-left:6px;"></i>'; btn.disabled = false; }
        }, 1500);
    });
}
</script>
@endsection
