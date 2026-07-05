@extends('layouts.app')

@section('title', 'Liên Hệ - VPNStore')

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
                <li class="breadcrumb-item active">Liên Hệ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-header text-center">
    <div class="container">
        <span class="section-label mb-3 d-inline-block">📞 Hỗ Trợ 24/7</span>
        <h1 class="section-title mb-2">Liên Hệ Với Chúng Tôi</h1>
        <p class="section-subtitle mx-auto">Chúng tôi luôn sẵn sàng hỗ trợ bạn mọi lúc mọi nơi</p>
    </div>
</div>

<section class="section" style="background:var(--gray-50)">
    <div class="container">
        <!-- Contact Cards -->
        <div class="row g-4 mb-5">
            @php
            $contacts = [
                ['icon'=>'bi-telegram','color'=>'#1565c0','bg'=>'#e3f2fd','label'=>'Telegram','value'=>'@specademy','action'=>'Nhắn Tin Ngay','link'=>'https://t.me/specademy','desc'=>'Phản hồi nhanh nhất, trong 5 phút'],
                ['icon'=>'bi-chat-dots-fill','color'=>'#7b1fa2','bg'=>'#f3e5f5','label'=>'Zalo 1','value'=>'0708910952','action'=>'Chat Zalo 1','link'=>'https://zalo.me/0708910952','desc'=>'Nhắn tin Zalo trực tiếp'],
                ['icon'=>'bi-chat-dots-fill','color'=>'#ab47bc','bg'=>'#f3e5f5','label'=>'Zalo 2','value'=>'0569012134','action'=>'Chat Zalo 2','link'=>'https://zalo.me/0569012134','desc'=>'Nhắn tin Zalo trực tiếp'],
                ['icon'=>'bi-envelope-fill','color'=>'#2e7d32','bg'=>'#e8f5e9','label'=>'Email','value'=>'tetuongmmovn@gmail.com','action'=>'Gửi Email','link'=>'mailto:tetuongmmovn@gmail.com','desc'=>'Phản hồi trong vòng 24 giờ'],
            ];
            @endphp
            @foreach($contacts as $ct)
            <div class="col-lg-3 col-md-6">
                <div class="contact-card">
                    <div class="contact-icon" style="background:{{ $ct['bg'] }};color:{{ $ct['color'] }}">
                        <i class="bi {{ $ct['icon'] }}" style="font-size:28px"></i>
                    </div>
                    <div class="fw-700 mb-1" style="font-size:15px;color:var(--gray-900)">{{ $ct['label'] }}</div>
                    <div class="fw-800 mb-1" style="color:{{ $ct['color'] }};font-size:14px">{{ $ct['value'] }}</div>
                    <div class="text-muted mb-3" style="font-size:12.5px">{{ $ct['desc'] }}</div>
                    <a href="{{ $ct['link'] }}" class="btn btn-sm rounded-pill px-4 fw-600"
                       style="background:{{ $ct['color'] }}15;color:{{ $ct['color'] }};border:1.5px solid {{ $ct['color'] }}40">
                        {{ $ct['action'] }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="bg-white border rounded-4 p-3 p-md-5" style="border-color:var(--gray-200)!important">
                    <h2 class="fw-800 mb-2 font-poppins" style="font-size:22px">Gửi Tin Nhắn</h2>
                    <p class="text-muted mb-4" style="font-size:14px">Điền thông tin và chúng tôi sẽ phản hồi sớm nhất</p>

                    <form id="contactForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và Tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" placeholder="Nguyễn Văn A" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" placeholder="email@gmail.com" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số Điện Thoại</label>
                                <input type="tel" class="form-control" placeholder="0909 999 999">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Chủ Đề</label>
                                <select class="form-select">
                                    <option>Hỏi về sản phẩm VPN</option>
                                    <option>Hỗ trợ kích hoạt</option>
                                    <option>Báo lỗi / Khiếu nại</option>
                                    <option>Yêu cầu hoàn tiền</option>
                                    <option>Hợp tác kinh doanh</option>
                                    <option>Khác</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Nội Dung <span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="5" placeholder="Mô tả chi tiết vấn đề hoặc câu hỏi của bạn..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary fw-700 px-5 py-3 rounded-pill" style="font-size:15px">
                                    <i class="bi bi-send me-2"></i>Gửi Tin Nhắn
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Panel -->
            <div class="col-lg-5">
                <!-- FAQ Quick -->
                <div class="bg-white border rounded-4 p-4 mb-4" style="border-color:var(--gray-200)!important">
                    <h5 class="fw-800 mb-3 font-poppins" style="font-size:16px">Câu Hỏi Thường Gặp</h5>
                    @php
                    $quickFaqs = [
                        ['q'=>'Bao lâu nhận được key?','a'=>'1–30 phút sau khi xác nhận thanh toán'],
                        ['q'=>'Có hỗ trợ cài đặt không?','a'=>'Có, miễn phí qua Telegram/Zalo/Email'],
                        ['q'=>'Key lỗi có được đổi không?','a'=>'Có, đổi key mới hoặc hoàn tiền 100%'],
                        ['q'=>'Có thể thanh toán bằng gì?','a'=>'CK ngân hàng, Momo, ZaloPay, Crypto'],
                    ];
                    @endphp
                    @foreach($quickFaqs as $fq)
                    <div class="mb-3 pb-3 border-bottom" style="border-color:var(--gray-100)!important">
                        <div class="fw-700 mb-1" style="font-size:13.5px;color:var(--gray-800)">
                            <i class="bi bi-question-circle-fill text-primary me-2"></i>{{ $fq['q'] }}
                        </div>
                        <div class="text-muted ps-4" style="font-size:13px">{{ $fq['a'] }}</div>
                    </div>
                    @endforeach
                    <div class="mb-0 pb-0">
                        <div class="fw-700 mb-1" style="font-size:13.5px;color:var(--gray-800)">
                            <i class="bi bi-question-circle-fill text-primary me-2"></i>Chính sách bảo hành?
                        </div>
                        <div class="text-muted ps-4" style="font-size:13px">Bảo hành 30 ngày, đổi trả nếu lỗi từ shop</div>
                    </div>
                </div>

                <!-- Working Hours -->
                <div class="bg-white border rounded-4 p-4" style="border-color:var(--gray-200)!important">
                    <h5 class="fw-800 mb-3 font-poppins" style="font-size:16px">
                        <i class="bi bi-clock-fill text-primary me-2"></i>Giờ Hỗ Trợ
                    </h5>
                    @foreach([['Telegram / Zalo','24/7 — Mọi lúc mọi nơi','text-success'],['Email','08:00 – 23:00 hàng ngày',''],['Hotline','08:00 – 24:00 hàng ngày',''],['Xử lý Key','Tự động 24/7','text-success']] as [$channel,$hours,$cls])
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom" style="border-color:var(--gray-100)!important">
                        <span class="fw-600" style="font-size:13.5px;color:var(--gray-700)">{{ $channel }}</span>
                        <span class="fw-700 {{ $cls ?: '' }}" style="font-size:13px">{{ $hours }}</span>
                    </div>
                    @endforeach
                    <div class="mt-3 p-3 rounded-2 text-center" style="background:var(--success-light);border:1px solid #bbf7d0">
                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                        <span class="fw-700 text-success" style="font-size:13.5px">Online ngay bây giờ!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
