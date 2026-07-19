@extends('layouts.app')

@section('title', 'Tra Cứu Đơn Hàng - VPNStore')

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
                <li class="breadcrumb-item active">Tra Cứu Đơn Hàng</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-header">
    <div class="container">
        <h1 class="section-title mb-1">
            <i class="bi bi-box-seam me-3 text-primary"></i>Tra Cứu Đơn Hàng
        </h1>
        <p class="text-muted mb-0">Nhập mã đơn hàng để kiểm tra trạng thái</p>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Search Form -->
            <div class="bg-white border rounded-4 p-4 mb-4" style="border-color:var(--gray-200)!important">
                <form id="orderCheckForm">
                    <label class="form-label fw-700 mb-2" style="font-size:14px">Mã Đơn Hàng</label>
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" id="orderIdInput" class="form-control border-start-0 ps-0"
                            placeholder="Ví dụ: VPN12345678"
                            value="{{ request('order','') }}"
                            style="letter-spacing:.5px">
                        <button class="btn btn-primary px-4 fw-600" type="submit">
                            <i class="bi bi-search me-1"></i>Tra Cứu
                        </button>
                    </div>
                    <div class="mt-2 d-flex gap-4">
                        <small class="text-muted"><i class="bi bi-info-circle me-1 text-primary"></i>Mã đơn hàng được gửi về email sau khi đặt hàng</small>
                    </div>
                </form>
            </div>

            <!-- Session Messages -->
            @if(session('success'))
            <div class="alert alert-success border-success-subtle rounded-4 p-3 mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                <div class="fw-600 text-success" style="font-size:14px">{{ session('success') }}</div>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger border-danger-subtle rounded-4 p-3 mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-4"></i>
                <div class="fw-600 text-danger" style="font-size:14px">{{ session('error') }}</div>
            </div>
            @endif

            <!-- Order Result -->
            @if(request('order'))
                @if($order)
                <div id="orderResult" class="mb-4">
                    <div class="bg-white border rounded-4 overflow-hidden" style="border-color:var(--gray-200)!important">
                        <!-- Order Header -->
                        <div class="p-4" style="background:linear-gradient(135deg,var(--primary-light),var(--accent-light));border-bottom:1.5px solid var(--primary-100)">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="text-muted small mb-1">Mã Đơn Hàng</div>
                                    <div class="fw-800 font-poppins text-primary" style="font-size:22px">#{{ $order->order_code }}</div>
                                </div>
                                <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Đang Xử Lý',
                                            'processing' => 'Đang Xử Lý',
                                            'completed' => 'Hoàn Tất',
                                            'cancelled' => 'Đã Hủy'
                                        ];
                                        $statusClasses = [
                                            'pending' => 'background:#fef3c7;color:#d97706;border:1px solid #fde68a',
                                            'processing' => 'background:#dbeafe;color:#2563eb;border:1px solid #bfdbfe',
                                            'completed' => 'background:#dcfce7;color:#16a34a;border:1px solid #bbf7d0',
                                            'cancelled' => 'background:#fee2e2;color:#dc2626;border:1px solid #fca5a5'
                                        ];
                                        $statusIcons = [
                                            'pending' => 'bi-clock-fill',
                                            'processing' => 'bi-gear-fill',
                                            'completed' => 'bi-check-circle-fill',
                                            'cancelled' => 'bi-x-circle-fill'
                                        ];
                                        $status = $order->order_status;
                                        $statusLabel = $statusLabels[$status] ?? 'Đang Xử Lý';
                                        $statusStyle = $statusClasses[$status] ?? 'background:#dbeafe;color:#2563eb;border:1px solid #bfdbfe';
                                        $statusIcon = $statusIcons[$status] ?? 'bi-info-circle-fill';
                                        
                                        // Mask email for privacy (e.g. khach***@gmail.com)
                                        $emailParts = explode('@', $order->customer_email);
                                        $maskedEmail = count($emailParts) === 2 ? substr($emailParts[0], 0, 3) . '***@' . $emailParts[1] : $order->customer_email;
                                    @endphp
                                    <span class="badge px-3 py-2 rounded-pill fw-700" style="{{ $statusStyle }};font-size:13px">
                                        <i class="bi {{ $statusIcon }} me-1"></i>{{ $statusLabel }}
                                    </span>
                                </div>
                            </div>
                            <div class="row mt-3 g-2">
                                <div class="col-6 col-md-3">
                                    <div class="text-muted" style="font-size:11.5px;font-weight:600">Ngày đặt</div>
                                    <div class="fw-700" style="font-size:13.5px;color:var(--gray-800)">{{ $order->created_at->format('d/m/Y') }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted" style="font-size:11.5px;font-weight:600">Khách hàng</div>
                                    <div class="fw-700" style="font-size:13.5px;color:var(--gray-800)">{{ $order->customer_name }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted" style="font-size:11.5px;font-weight:600">Email</div>
                                    <div class="fw-700" style="font-size:13.5px;color:var(--gray-800)">{{ $maskedEmail }}</div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-muted" style="font-size:11.5px;font-weight:600">Tổng tiền</div>
                                    <div class="fw-700 text-primary" style="font-size:13.5px">{{ number_format($order->total) }}đ</div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="p-4 border-bottom" style="border-color:var(--gray-100)!important">
                            <h6 class="fw-700 mb-3" style="font-size:14px">Sản Phẩm Đặt Mua</h6>
                            <div class="order-product-item d-flex align-items-center gap-3 p-3 rounded-3" style="background:var(--gray-50)">
                                <div style="width:52px;height:52px;background:linear-gradient(135deg,#4687FF,#2563eb);border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px">
                                    <i class="bi bi-shield-lock-fill"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-700" style="font-size:14px">{{ $order->product_name }}</div>
                                    <div class="text-muted" style="font-size:12.5px">Thương hiệu: {{ $order->brand }} · Gói: {{ ucfirst($order->plan) }} · Số lượng: {{ $order->quantity }}</div>
                                </div>
                                <div class="fw-800 text-primary" style="font-size:16px">{{ number_format($order->total) }}đ</div>
                            </div>
                        </div>

                        <!-- License Key (Only show if completed and key exists) -->
                        @if($order->order_status === 'completed' && $order->license_key)
                        <div class="p-4 border-bottom" style="background:#f0fdf4;border-color:var(--gray-100)!important">
                            <h6 class="fw-700 text-success mb-2" style="font-size:14px">
                                <i class="bi bi-key-fill me-2"></i>Key VPN Của Bạn:
                            </h6>
                            <div class="p-3 rounded-3 bg-white border border-success d-flex align-items-center justify-content-between gap-3">
                                <code class="fw-700 text-dark" style="font-size:15px;word-break:break-all">{{ $order->license_key }}</code>
                                <button class="btn btn-sm btn-success text-white" onclick="navigator.clipboard.writeText('{{ $order->license_key }}').then(()=>showToast('Đã sao chép key VPN!','success'))">
                                    <i class="bi bi-copy me-1"></i>Sao Chép
                                </button>
                            </div>
                            <small class="text-muted mt-2 d-block"><i class="bi bi-info-circle me-1"></i>Vui lòng làm theo hướng dẫn kích hoạt trong email của bạn.</small>
                        </div>
                        @endif

                        <!-- Đánh Giá Sản Phẩm (Chỉ hiển thị khi đơn hàng đã Hoàn tất) -->
                        @if($order->order_status === 'completed')
                        <div class="p-4 border-bottom bg-white" style="border-color:var(--gray-100)!important">
                            <h6 class="fw-700 mb-3" style="font-size:14px">
                                <i class="bi bi-star-fill text-warning me-2"></i>Đánh Giá Sản Phẩm
                            </h6>
                            
                            @if(!$order->is_reviewed)
                            <!-- Form gửi đánh giá -->
                            <form action="{{ route('order.review.submit') }}" method="POST">
                                @csrf
                                <input type="hidden" name="order_code" value="{{ $order->order_code }}">
                                
                                <div class="mb-3">
                                    <label class="form-label fw-600 text-dark mb-1" style="font-size:13px">Chọn số sao đánh giá (Không bắt buộc):</label>
                                    <div class="d-flex gap-2 rating-select" style="font-size:24px;cursor:pointer;color:#ccc">
                                        <i class="bi bi-star-fill star-option" data-value="1"></i>
                                        <i class="bi bi-star-fill star-option" data-value="2"></i>
                                        <i class="bi bi-star-fill star-option" data-value="3"></i>
                                        <i class="bi bi-star-fill star-option" data-value="4"></i>
                                        <i class="bi bi-star-fill star-option" data-value="5"></i>
                                    </div>
                                    <input type="hidden" name="rating" id="reviewRatingInput" value="">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-600 text-dark mb-1" style="font-size:13px">Nội dung đánh giá:</label>
                                    <textarea name="comment" rows="3" class="form-control" placeholder="Chia sẻ trải nghiệm của bạn về dịch vụ/sản phẩm VPN này... (Không bắt buộc)" style="border-radius:10px;font-size:13px"></textarea>
                                </div>
                                
                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-600" style="font-size:13.5px">
                                    Gửi Đánh Giá
                                </button>
                            </form>
                            @else
                            <!-- Đã đánh giá xong -->
                            <div class="p-3 rounded-3 bg-light border border-light d-flex flex-column gap-2">
                                @if($order->review_rating)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-700 text-dark" style="font-size:13.5px">Đánh giá của bạn:</span>
                                    <div class="rating-stars" style="color:#ffb300;font-size:14px">
                                        @for($s=1;$s<=$order->review_rating;$s++)
                                            <i class="bi bi-star-fill"></i>
                                        @endfor
                                        @for($s=$order->review_rating+1;$s<=5;$s++)
                                            <i class="bi bi-star"></i>
                                        @endfor
                                    </div>
                                </div>
                                @endif
                                @if($order->review_comment)
                                <p class="mb-0 text-muted" style="font-size:13px;font-style:italic">"{{ $order->review_comment }}"</p>
                                @endif
                                @if(!$order->review_rating && !$order->review_comment)
                                <p class="mb-0 text-muted" style="font-size:13px;font-style:italic">Bạn đã gửi đánh giá hoàn thành đơn hàng này.</p>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Order Timeline -->
                        <div class="p-4">
                            <h6 class="fw-700 mb-4" style="font-size:14px">Trạng Thái Đơn Hàng</h6>
                            <div class="order-status-timeline">
                                @php
                                $orderTime = $order->created_at->format('H:i d/m/Y');
                                $updatedTime = $order->updated_at->format('H:i d/m/Y');
                                
                                if ($status === 'cancelled') {
                                    $timeline = [
                                        ['done' => true, 'title' => 'Đơn hàng đã đặt', 'desc' => 'Đơn hàng được tạo thành công', 'time' => $orderTime],
                                        ['cancelled' => true, 'title' => 'Đã Hủy Đơn Hàng', 'desc' => 'Đơn hàng của bạn đã bị hủy hoặc hoàn tiền', 'time' => $updatedTime]
                                    ];
                                } else {
                                    $timeline = [
                                        [
                                            'done' => true, 
                                            'title' => 'Đơn hàng đã đặt', 
                                            'desc' => 'Đơn hàng được tạo thành công', 
                                            'time' => $orderTime
                                        ],
                                        [
                                            'done' => in_array($status, ['processing', 'completed']),
                                            'active' => $status === 'pending',
                                            'title' => 'Đang Xác Nhận Thanh Toán', 
                                            'desc' => $status === 'pending' ? 'Chúng tôi đang xác nhận giao dịch chuyển khoản' : 'Đã xác nhận thanh toán thành công', 
                                            'time' => $status === 'pending' ? 'Đang xử lý' : $updatedTime
                                        ],
                                        [
                                            'done' => $status === 'completed',
                                            'active' => $status === 'processing',
                                            'pending' => $status === 'pending',
                                            'title' => 'Đang Chuẩn Bị Key', 
                                            'desc' => 'Key VPN đang được chuẩn bị và kiểm tra', 
                                            'time' => $status === 'processing' ? 'Đang xử lý' : ($status === 'pending' ? 'Chờ thanh toán' : $updatedTime)
                                        ],
                                        [
                                            'done' => $status === 'completed',
                                            'pending' => in_array($status, ['pending', 'processing']),
                                            'title' => 'Gửi Key Về Email & Kích Hoạt', 
                                            'desc' => 'Key VPN sẽ được gửi qua email đã đăng ký và hiển thị ở đây', 
                                            'time' => $status === 'completed' ? $updatedTime : 'Chờ xử lý'
                                        ],
                                        [
                                            'done' => $status === 'completed',
                                            'pending' => in_array($status, ['pending', 'processing']),
                                            'title' => 'Hoàn Tất', 
                                            'desc' => 'Đơn hàng hoàn tất, VPN đã sẵn sàng sử dụng', 
                                            'time' => ''
                                        ]
                                    ];
                                }
                                @endphp
                                @foreach($timeline as $step)
                                <div class="timeline-item">
                                    <div class="timeline-dot {{ !empty($step['done']) ? 'done' : (!empty($step['active']) ? 'active' : (!empty($step['cancelled']) ? 'bg-danger border-danger' : '')) }}" style="{{ !empty($step['cancelled']) ? 'background:var(--danger);box-shadow:0 0 0 1px var(--danger);' : '' }}"></div>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="timeline-title {{ !empty($step['pending']) ? 'text-muted' : (!empty($step['cancelled']) ? 'text-danger' : '') }}">{{ $step['title'] }}</div>
                                            <div class="timeline-desc">{{ $step['desc'] }}</div>
                                        </div>
                                        @if($step['time'])
                                        <div style="font-size:11.5px;color:var(--gray-400);white-space:nowrap;margin-left:12px">{{ $step['time'] }}</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Support -->
                            <div class="mt-4 p-3 rounded-3 d-flex align-items-center gap-3" style="background:var(--primary-light);border:1px solid var(--primary-100)">
                                <i class="bi bi-headset text-primary" style="font-size:24px;flex-shrink:0"></i>
                                <div>
                                    <div class="fw-700" style="font-size:13.5px;color:var(--primary)">Cần hỗ trợ?</div>
                                     <div style="font-size:12.5px;color:var(--gray-600)">
                                         Liên hệ qua Telegram <strong>{{ '@' . ltrim($settings['telegram_support'] ?? 'specademy', '@') }}</strong>
                                         @if(!empty($settings['zalo_support'])) hoặc Zalo <strong>{{ $settings['zalo_support'] }}{{ !empty($settings['zalo_support_2']) ? ' / ' . $settings['zalo_support_2'] : '' }}</strong> @endif
                                     </div>
                                </div>
                                <a href="{{ route('contact') }}" class="btn btn-sm btn-primary ms-auto rounded-pill px-3">Liên Hệ</a>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- Order Not Found -->
                <div class="alert alert-danger border-danger-subtle rounded-4 p-4 mb-4 d-flex align-items-start gap-3">
                    <i class="bi bi-exclamation-triangle-fill fs-3 text-danger mt-1"></i>
                    <div>
                        <h5 class="fw-700 text-danger mb-2">Không tìm thấy đơn hàng</h5>
                        <p class="mb-0 text-muted" style="font-size:14px;line-height:1.6">
                            Không tìm thấy đơn hàng nào có mã <strong>#{{ request('order') }}</strong> trong hệ thống.
                            Vui lòng kiểm tra lại mã đơn hàng (bao gồm chữ in hoa và chữ số) hoặc liên hệ bộ phận hỗ trợ của chúng tôi để được giải đáp.
                        </p>
                    </div>
                </div>
                @endif
            @endif

            <!-- Sample Order IDs -->
            <div class="mt-4 p-4 bg-white border rounded-3" style="border-color:var(--gray-200)!important">
                <h6 class="fw-700 mb-3" style="font-size:14px"><i class="bi bi-info-circle me-2 text-primary"></i>Hỗ Trợ Tra Cứu</h6>
                <p class="text-muted mb-2" style="font-size:13.5px">
                    Không tìm thấy đơn hàng? Kiểm tra lại email hoặc liên hệ hỗ trợ:
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @if(!empty($settings['telegram_support']))
                    <a href="{{ $settings['telegram_url'] ?? 'https://t.me/' . ltrim($settings['telegram_support'],'@') }}" target="_blank" class="btn btn-sm rounded-pill px-3" style="background:#e3f2fd;color:#1565c0;font-size:12.5px;font-weight:600;border:1px solid #bbdefb">
                        <i class="bi bi-telegram me-1"></i>Telegram: {{ '@' . ltrim($settings['telegram_support'],'@') }}
                    </a>
                    @endif
                    @if(!empty($settings['zalo_support']))
                    <a href="{{ $settings['zalo_url_1'] ?? 'https://zalo.me/' . $settings['zalo_support'] }}" target="_blank" class="btn btn-sm rounded-pill px-3" style="background:#fce4ec;color:#7b1fa2;font-size:12.5px;font-weight:600;border:1px solid #f3e5f5">
                        <i class="bi bi-chat-dots me-1"></i>Zalo 1: {{ $settings['zalo_support'] }}
                    </a>
                    @endif
                    @if(!empty($settings['zalo_support_2']))
                    <a href="{{ $settings['zalo_url_2'] ?? 'https://zalo.me/' . $settings['zalo_support_2'] }}" target="_blank" class="btn btn-sm rounded-pill px-3" style="background:#fce4ec;color:#7b1fa2;font-size:12.5px;font-weight:600;border:1px solid #f3e5f5">
                        <i class="bi bi-chat-dots me-1"></i>Zalo 2: {{ $settings['zalo_support_2'] }}
                    </a>
                    @endif
                    @if(!empty($settings['contact_email']))
                    <a href="mailto:{{ $settings['contact_email'] }}" class="btn btn-sm rounded-pill px-3" style="background:#e8f5e9;color:#2e7d32;font-size:12.5px;font-weight:600;border:1px solid #c8e6c9">
                        <i class="bi bi-envelope me-1"></i>{{ $settings['contact_email'] }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-select .star-option');
    const ratingInput = document.getElementById('reviewRatingInput');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const val = parseInt(this.dataset.value);
            ratingInput.value = val;
            
            // Highlight selected stars and reset others
            stars.forEach((s, idx) => {
                if (idx < val) {
                    s.style.color = '#ffb300';
                } else {
                    s.style.color = '#ccc';
                }
            });
        });
        
        // Hover effects
        star.addEventListener('mouseenter', function() {
            const val = parseInt(this.dataset.value);
            stars.forEach((s, idx) => {
                if (idx < val) {
                    s.style.color = '#ffd54f';
                } else {
                    s.style.color = '#ccc';
                }
            });
        });
    });

    const selectContainer = document.querySelector('.rating-select');
    if (selectContainer) {
        selectContainer.addEventListener('mouseleave', function() {
            const currentVal = parseInt(ratingInput.value || 0);
            stars.forEach((s, idx) => {
                if (idx < currentVal) {
                    s.style.color = '#ffb300';
                } else {
                    s.style.color = '#ccc';
                }
            });
        });
    }
});
</script>
@endsection
