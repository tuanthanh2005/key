@php
$slug = $slug ?? request()->segment(2) ?? 'nordvpn';

$brandData = [
    'nordvpn'   => ['name'=>'NordVPN','color'=>'#4687FF','bg'=>'#EEF4FF','icon'=>'bi-shield-lock-fill','servers'=>'5,400+','countries'=>'60+','devices'=>6,'speed'=>'Rất Nhanh','protocol'=>'NordLynx (WireGuard)','headquarter'=>'Panama','refund'=>'30 ngày','founded'=>'2012','desc'=>'NordVPN là một trong những dịch vụ VPN phổ biến nhất thế giới, nổi tiếng với tốc độ nhanh, bảo mật cao và giao diện thân thiện. Với hơn 5,400 máy chủ tại 60+ quốc gia, NordVPN đảm bảo kết nối ổn định và tốc độ tối ưu.'],
    'expressvpn' => ['name'=>'ExpressVPN','color'=>'#DA3940','bg'=>'#FFF0F0','icon'=>'bi-shield-fill-check','servers'=>'3,000+','countries'=>'94+','devices'=>5,'speed'=>'Siêu Nhanh','protocol'=>'Lightway','headquarter'=>'British Virgin Islands','refund'=>'30 ngày','founded'=>'2009','desc'=>'ExpressVPN là lựa chọn hàng đầu cho tốc độ và độ tin cậy. Giao thức Lightway độc quyền cho phép kết nối nhanh, ổn định và bảo mật cao. Hỗ trợ 94+ quốc gia với hơn 3,000 máy chủ tốc độ cao.'],
    'surfshark'  => ['name'=>'Surfshark','color'=>'#10B981','bg'=>'#ECFDF5','icon'=>'bi-water','servers'=>'3,200+','countries'=>'100+','devices'=>-1,'speed'=>'Nhanh','protocol'=>'WireGuard / IKEv2','headquarter'=>'Netherlands','refund'=>'30 ngày','founded'=>'2018','desc'=>'Surfshark nổi bật với chính sách cho phép kết nối không giới hạn thiết bị, lý tưởng cho gia đình. Tính năng CleanWeb chặn quảng cáo và malware, Camouflage Mode che giấu hoạt động VPN.'],
    'hma'        => ['name'=>'HMA VPN','color'=>'#F59E0B','bg'=>'#FFFBEB','icon'=>'bi-shield-shaded','servers'=>'1,100+','countries'=>'190+','devices'=>5,'speed'=>'Nhanh','protocol'=>'OpenVPN / IKEv2','headquarter'=>'United Kingdom','refund'=>'30 ngày','founded'=>'2005','desc'=>'HMA (Hide My Ass!) là một trong những VPN lâu đời nhất với mạng lưới máy chủ tại 190+ quốc gia, nhiều hơn hầu hết các đối thủ. Giao diện đơn giản, dễ sử dụng phù hợp với người mới bắt đầu.'],
    'cyberghost' => ['name'=>'CyberGhost','color'=>'#8B5CF6','bg'=>'#F5F3FF','icon'=>'bi-incognito','servers'=>'9,700+','countries'=>'91+','devices'=>7,'speed'=>'Nhanh','protocol'=>'WireGuard / OpenVPN','headquarter'=>'Romania','refund'=>'45 ngày','founded'=>'2011','desc'=>'CyberGhost có mạng lưới máy chủ lớn nhất với 9,700+ server và chính sách hoàn tiền 45 ngày - dài nhất trong ngành. Máy chủ chuyên dụng cho streaming và torrenting được gắn nhãn rõ ràng.'],
    'purevpn'    => ['name'=>'PureVPN','color'=>'#EF4444','bg'=>'#FEF2F2','icon'=>'bi-shield-fill-exclamation','servers'=>'6,500+','countries'=>'78+','devices'=>10,'speed'=>'Nhanh','protocol'=>'WireGuard / OpenVPN','headquarter'=>'British Virgin Islands','refund'=>'31 ngày','founded'=>'2007','desc'=>'PureVPN cho phép kết nối đến 10 thiết bị đồng thời với hơn 6,500 máy chủ tại 78+ quốc gia. Đã vượt qua kiểm toán bảo mật độc lập xác nhận chính sách không lưu log.'],
    'ipvanish'   => ['name'=>'IPVanish','color'=>'#0EA5E9','bg'=>'#F0F9FF','icon'=>'bi-shield-lock','servers'=>'2,200+','countries'=>'75+','devices'=>-1,'speed'=>'Nhanh','protocol'=>'WireGuard / OpenVPN','headquarter'=>'United States','refund'=>'30 ngày','founded'=>'2012','desc'=>'IPVanish là lựa chọn tuyệt vời cho người dùng cần không giới hạn thiết bị kết nối đồng thời. Hỗ trợ SOCKS5 proxy, lý tưởng cho torrent và P2P. Có ứng dụng native cho mọi nền tảng.'],
    'protonvpn'  => ['name'=>'ProtonVPN','color'=>'#6D28D9','bg'=>'#F5F3FF','icon'=>'bi-shield-fill-check','servers'=>'3,000+','countries'=>'67+','devices'=>10,'speed'=>'Nhanh','protocol'=>'WireGuard / OpenVPN','headquarter'=>'Switzerland','refund'=>'30 ngày','founded'=>'2017','desc'=>'ProtonVPN từ đội ngũ ProtonMail Thụy Sĩ - tập trung tuyệt đối vào quyền riêng tư. Tính năng Secure Core định tuyến lưu lượng qua nhiều máy chủ, cung cấp bảo vệ tốt nhất. Không bao giờ lưu log.'],
];

$brand = $brandData[$slug] ?? $brandData['nordvpn'];

$plans = [];
foreach ($dbProducts ?? [] as $dbProd) {
    $planKey = $dbProd->plan;
    $label = match($planKey) {
        '1month' => '1 Tháng',
        '6month' => '6 Tháng',
        '1year' => '1 Năm',
        '2year' => '2 Năm',
        '3year' => '3 Năm',
        default => $planKey
    };
    $save = null;
    if ($dbProd->old_price && $dbProd->old_price > $dbProd->price) {
        $pct = round((1 - ($dbProd->price / $dbProd->old_price)) * 100);
        $save = "Tiết kiệm {$pct}%";
    }
    $plans[] = [
        'id' => $dbProd->id,
        'key' => $planKey,
        'label' => $label,
        'price' => $dbProd->price,
        'old' => $dbProd->old_price ?: ($dbProd->price * 1.5),
        'save' => $save,
        'popular' => ($planKey === '1year'),
        'image_path' => $dbProd->image_path,
        'color' => $dbProd->color,
        'servers' => $dbProd->servers ?: ($brand['servers'] ?? ''),
        'countries' => $dbProd->countries ?: ($brand['countries'] ?? ''),
        'devices' => $dbProd->devices ?: (($brand['devices'] ?? '') === -1 ? 'Không giới hạn' : ($brand['devices'] ?? '') . ' thiết bị đồng thời'),
        'speed' => $dbProd->speed ?: ($brand['speed'] ?? ''),
        'protocol' => $dbProd->protocol ?: ($brand['protocol'] ?? ''),
        'headquarter' => $dbProd->headquarter ?: ($brand['headquarter'] ?? ''),
        'founded' => $dbProd->founded ?: ($brand['founded'] ?? ''),
        'refund' => $dbProd->refund ?: ($brand['refund'] ?? ''),
        'description' => $dbProd->description ?: ($brand['desc'] ?? ''),
        'stock' => $dbProd->stock,
    ];
}

// Fallback just in case
if (empty($plans)) {
    $plans = [
        ['id'=>1, 'key'=>'1month', 'label'=>'1 Tháng', 'price'=>120000, 'old'=>200000, 'save'=>null, 'popular'=>false, 'image_path'=>null, 'servers'=>'5,400+', 'countries'=>'60+', 'devices'=>'6', 'speed'=>'Nhanh', 'protocol'=>'WireGuard', 'headquarter'=>'Panama', 'founded'=>'2012', 'refund'=>'30 ngày', 'description'=>'NordVPN là một dịch vụ VPN phổ biến.'],
        ['id'=>2, 'key'=>'6month', 'label'=>'6 Tháng', 'price'=>350000, 'old'=>720000, 'save'=>'Tiết kiệm 51%', 'popular'=>false, 'image_path'=>null, 'servers'=>'5,400+', 'countries'=>'60+', 'devices'=>'6', 'speed'=>'Nhanh', 'protocol'=>'WireGuard', 'headquarter'=>'Panama', 'founded'=>'2012', 'refund'=>'30 ngày', 'description'=>'NordVPN là một dịch vụ VPN phổ biến.'],
        ['id'=>3, 'key'=>'1year',  'label'=>'1 Năm',   'price'=>599000, 'old'=>1440000,'save'=>'Tiết kiệm 58%', 'popular'=>true, 'image_path'=>null, 'servers'=>'5,400+', 'countries'=>'60+', 'devices'=>'6', 'speed'=>'Nhanh', 'protocol'=>'WireGuard', 'headquarter'=>'Panama', 'founded'=>'2012', 'refund'=>'30 ngày', 'description'=>'NordVPN là một dịch vụ VPN phổ biến.'],
        ['id'=>4, 'key'=>'2year',  'label'=>'2 Năm',   'price'=>849000, 'old'=>2880000,'save'=>'Tiết kiệm 70%', 'popular'=>false, 'image_path'=>null, 'servers'=>'5,400+', 'countries'=>'60+', 'devices'=>'6', 'speed'=>'Nhanh', 'protocol'=>'WireGuard', 'headquarter'=>'Panama', 'founded'=>'2012', 'refund'=>'30 ngày', 'description'=>'NordVPN là một dịch vụ VPN phổ biến.'],
    ];
}

$defaultPlan = collect($plans)->firstWhere('popular', true) ?: ($plans[0] ?? null);
$curRating = floatval($defaultPlan['rating'] ?? 4.8);
$curReviews = intval($defaultPlan['reviews'] ?? 120);
@endphp

@extends('layouts.app')

@section('title', $brand['name'] . ' Giá Rẻ Bản Quyền Chính Hãng — VPNStore')
@section('meta_description', $brand['name'] . ' chính hãng giá tốt nhất. Mua tài khoản/key ' . $brand['name'] . ' bảo hành uy tín 30 ngày, đổi trả tức thì. Nhận key trong 5 phút.')
@section('meta_keywords', strtolower($brand['name']) . ' gia re, mua ' . strtolower($brand['name']) . ', tai khoan ' . strtolower($brand['name']) . ', key ' . strtolower($brand['name']) . ' ban quyen, vpn store')

@section('json_ld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "BreadcrumbList",
      "@@id": "{{ request()->url() }}#breadcrumb",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Trang Chủ",
          "item": "{{ route('home') }}"
        },
        {
          "@@type": "ListItem",
          "position": 2,
          "name": "Sản Phẩm",
          "item": "{{ route('products') }}"
        },
        {
          "@@type": "ListItem",
          "position": 3,
          "name": "{{ $brand['name'] }}",
          "item": "{{ request()->url() }}"
        }
      ]
    },
    {
      "@@type": "Product",
      "@@id": "{{ request()->url() }}#product",
      "name": "{{ $brand['name'] }} — VPN Bản Quyền Chính Hãng",
      "image": "{{ !empty($defaultPlan['image_path']) ? asset($defaultPlan['image_path']) : (!empty($settings['favicon_path']) ? asset($settings['favicon_path']) : asset('favicon.ico')) }}",
      "description": "{{ strip_tags($brand['desc']) }}",
      "brand": {
        "@@type": "Brand",
        "name": "{{ $brand['name'] }}"
      },
      "offers": {
        "@@type": "AggregateOffer",
        "priceCurrency": "VND",
        "lowPrice": "{{ collect($plans)->min('price') }}",
        "highPrice": "{{ collect($plans)->max('price') }}",
        "offerCount": "{{ count($plans) }}",
        "availability": "https://schema.org/InStock",
        "url": "{{ request()->url() }}"
      },
      "aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ $curRating }}",
        "reviewCount": "{{ $curReviews }}",
        "bestRating": "5",
        "worstRating": "1"
      },
      "review": [
        @foreach(array_slice($realReviews, 0, 3) as $idx => $rv)
        {
          "@@type": "Review",
          "author": {
            "@@type": "Person",
            "name": "{{ $rv['name'] }}"
          },
          "datePublished": "{{ date('Y-m-d', strtotime('-' . ($idx + 2) . ' days')) }}",
          "reviewBody": "{{ $rv['text'] }}",
          "reviewRating": {
            "@@type": "Rating",
            "ratingValue": "{{ $rv['star'] }}",
            "bestRating": "5"
          }
        }{{ $idx < 2 && $idx < count($realReviews) - 1 ? ',' : '' }}
        @endforeach
      ]
    }
  ]
}
</script>
@endsection

@section('content')

<!-- BREADCRUMB -->
<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="bi bi-house me-1"></i>Trang Chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products') }}">Sản Phẩm</a></li>
                <li class="breadcrumb-item active">{{ $brand['name'] }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">
        <!-- Product Visual -->
        <div class="col-lg-5">
            <div class="product-detail-img mb-4 d-flex align-items-center justify-content-center" style="background:{{ $brand['bg'] }}; min-height: 250px; position: relative;">
                @php
                    $defaultPlan = collect($plans)->firstWhere('popular', true) ?: ($plans[0] ?? null);
                    $hasImg = $defaultPlan && !empty($defaultPlan['image_path']);
                @endphp
                <img id="main-product-image" src="{{ $hasImg ? asset($defaultPlan['image_path']) : '' }}" alt="{{ $brand['name'] }}" class="{{ $hasImg ? '' : 'd-none' }}" style="max-height: 200px; max-width: 80%; object-fit: contain;">
                
                <div id="main-product-icon-container" class="text-center {{ $hasImg ? 'd-none' : '' }}">
                    <div class="mb-3" style="font-size:90px;color:{{ $brand['color'] }}">
                        <i class="bi {{ $brand['icon'] }}"></i>
                    </div>
                    <div class="fw-800" style="font-family:'Poppins',sans-serif;font-size:28px;color:{{ $brand['color'] }}">
                        {{ $brand['name'] }}
                    </div>
                    <div class="text-muted" style="font-size:13px">VPN Chính Hãng · Bảo Hành {{ $brand['refund'] }}</div>
                </div>
                <!-- Badges -->
                <div style="position:absolute;top:16px;right:16px">
                    <span class="badge-hot"><i class="bi bi-patch-check-fill me-1"></i>Chính Hãng</span>
                </div>
            </div>

            <!-- Key Specs -->
            <div class="bg-white border rounded-3 p-4 mb-4" style="border-color:var(--gray-200)!important">
                <h6 class="fw-700 mb-3" style="font-size:14px;color:var(--gray-800)">
                    <i class="bi bi-info-circle me-2 text-primary"></i>Thông Số Kỹ Thuật
                </h6>
                <table class="product-spec-table w-100">
                    <tr><td>Máy chủ</td><td><strong id="spec-servers">{{ $defaultPlan['servers'] ?? '' }}</strong></td></tr>
                    <tr><td>Quốc gia</td><td><strong id="spec-countries">{{ $defaultPlan['countries'] ?? '' }}</strong></td></tr>
                    <tr><td>Thiết bị</td><td><strong id="spec-devices">{{ $defaultPlan['devices'] ?? '' }}</strong></td></tr>
                    <tr><td>Tốc độ</td><td><strong id="spec-speed" class="text-success">{{ $defaultPlan['speed'] ?? '' }}</strong></td></tr>
                    <tr><td>Giao thức</td><td><strong id="spec-protocol">{{ $defaultPlan['protocol'] ?? '' }}</strong></td></tr>
                    <tr><td>Trụ sở</td><td><strong id="spec-headquarter">{{ $defaultPlan['headquarter'] ?? '' }}</strong></td></tr>
                    <tr><td>Thành lập</td><td><strong id="spec-founded">{{ $defaultPlan['founded'] ?? '' }}</strong></td></tr>
                    <tr><td>Hoàn tiền</td><td><strong id="spec-refund" class="text-success">{{ $defaultPlan['refund'] ?? '' }}</strong></td></tr>
                </table>
            </div>

            <!-- Trust Signals -->
            <div class="row g-2">
                @foreach([['bi-shield-check-fill','text-success','Mã hóa AES-256'],['bi-eye-slash-fill','text-primary','Zero-Log Policy'],['bi-patch-check-fill','text-warning','100% Chính Hãng'],['bi-headset','text-danger','Hỗ trợ 24/7']] as [$ic,$cls,$lbl])
                <div class="col-6">
                    <div class="d-flex align-items-center gap-2 p-2 rounded-2" style="background:var(--gray-50);border:1px solid var(--gray-100)">
                        <i class="bi {{ $ic }} {{ $cls }}"></i>
                        <span style="font-size:12.5px;font-weight:600;color:var(--gray-700)">{{ $lbl }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Product Info & Purchase -->
        <div class="col-lg-7">
            <!-- Brand Tag -->
            <div class="d-flex align-items-center gap-2 mb-3">
                <span class="badge px-3 py-2 rounded-pill fw-700" style="background:{{ $brand['color'] }}20;color:{{ $brand['color'] }};font-size:12px;border:1px solid {{ $brand['color'] }}40">
                    <span style="width:8px;height:8px;background:{{ $brand['color'] }};border-radius:50%;display:inline-block;margin-right:6px"></span>
                    {{ $brand['name'] }}
                </span>
                <span class="badge bg-success-light text-success rounded-pill" style="font-size:11.5px;font-weight:700;background:var(--success-light)!important;border:1px solid #bbf7d0">
                    <i class="bi bi-check-circle-fill me-1"></i>Còn Hàng
                </span>
                <span class="badge bg-primary-light text-primary rounded-pill" style="font-size:11.5px;font-weight:700;background:var(--primary-light)!important;border:1px solid var(--primary-100)">
                    <i class="bi bi-cart-check-fill me-1"></i>Đã Bán {{ \App\Models\Setting::get('sales_' . $slug, '100+') }}
                </span>
                @php
                    $curRating = floatval($defaultPlan['rating'] ?? 4.8);
                    $curReviews = intval($defaultPlan['reviews'] ?? 120);
                @endphp
                <div class="rating-stars ms-auto">
                    @for($i=1;$i<=5;$i++)
                        <i class="bi {{ $i <= floor($curRating) ? 'bi-star-fill' : ($i - $curRating < 1 ? 'bi-star-half' : 'bi-star') }}" style="font-size:13px;color:#ffb300"></i>
                    @endfor
                    <span class="ms-1 fw-600" style="font-size:12.5px;color:var(--gray-700)">{{ number_format($curRating, 1) }}</span>
                    <span class="ms-1" style="font-size:12px;color:var(--gray-400)">({{ number_format($curReviews) }} đánh giá)</span>
                </div>
            </div>

            <h1 class="font-poppins fw-800 mb-3" style="font-size:28px;line-height:1.2;color:var(--gray-900)">
                {{ $brand['name'] }} — VPN Chính Hãng
            </h1>
            <p class="text-muted mb-4" id="main-product-description" style="font-size:14.5px;line-height:1.75">{{ $defaultPlan['description'] ?? '' }}</p>

            <!-- Plan Selector -->
            <div class="mb-4">
                <h6 class="fw-700 mb-3" style="font-size:14px">
                    <i class="bi bi-calendar3 me-2 text-primary"></i>Chọn Gói Thời Hạn
                </h6>
                <div class="plan-selector" id="plan-selector">
                    @foreach($plans as $plan)
                    <div class="plan-option {{ $plan['popular'] ? 'selected' : '' }} {{ $plan['stock'] <= 0 ? 'out-of-stock-option' : '' }}"
                        data-id="{{ $plan['id'] }}"
                        data-plan="{{ $plan['key'] }}"
                        data-period="{{ $plan['label'] }}"
                        data-price="{{ $plan['price'] }}"
                        data-old="{{ $plan['old'] }}"
                        data-image="{{ !empty($plan['image_path']) ? asset($plan['image_path']) : '' }}"
                        data-servers="{{ $plan['servers'] }}"
                        data-countries="{{ $plan['countries'] }}"
                        data-devices="{{ $plan['devices'] }}"
                        data-speed="{{ $plan['speed'] }}"
                        data-protocol="{{ $plan['protocol'] }}"
                        data-headquarter="{{ $plan['headquarter'] }}"
                        data-founded="{{ $plan['founded'] }}"
                        data-refund="{{ $plan['refund'] }}"
                        data-desc="{{ $plan['description'] }}"
                        data-stock="{{ $plan['stock'] }}">
                        @if($plan['stock'] <= 0)
                        <span class="plan-badge bg-danger text-white">Hết Hàng</span>
                        @elseif($plan['popular'])
                        <span class="plan-badge">Phổ Biến</span>
                        @endif
                        <div class="plan-period">{{ $plan['label'] }}</div>
                        <div class="plan-price">{{ number_format($plan['price']) }}đ</div>
                        @if($plan['save'])
                        <span class="plan-save">{{ $plan['save'] }}</span>
                        @else
                        <span class="plan-save" style="visibility:hidden">-</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Price Display -->
            <div class="p-4 rounded-3 mb-4 detail-price-container" style="background:linear-gradient(135deg,var(--primary-light),var(--accent-light));border:1.5px solid var(--primary-100)">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted" style="font-size:12.5px;font-weight:600;text-decoration:line-through">
                            {{ number_format($plans[2]['old']) }}đ
                        </div>
                        <div class="d-flex align-items-baseline gap-2">
                            <span class="font-poppins fw-800" style="font-size:36px;color:var(--primary)" id="selected-price">{{ number_format($plans[2]['price']) }}đ</span>
                            <span class="text-muted" style="font-size:14px" id="selected-period">/ 1 năm</span>
                        </div>
                        <div class="text-success small fw-600">
                            <i class="bi bi-tag-fill me-1"></i>
                            Tiết kiệm {{ number_format($plans[2]['old'] - $plans[2]['price']) }}đ (58%)
                        </div>
                    </div>
                    <div class="text-end">
                        <div style="font-size:12px;color:var(--gray-500)" class="mb-1">
                            <i class="bi bi-arrow-repeat text-success me-1"></i>Hoàn tiền {{ $brand['refund'] }}
                        </div>
                        <div style="font-size:12px;color:var(--gray-500)">
                            <i class="bi bi-shield-check text-success me-1"></i>100% Chính Hãng
                        </div>
                        <div style="font-size:12px;color:var(--gray-500)" class="mt-1">
                            <i class="bi bi-lightning-fill text-warning me-1"></i>Giao Key 5–15 phút
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quantity -->
            <div class="d-flex align-items-center gap-3 mb-4">
                <span class="fw-600 text-muted" style="font-size:14px">Số lượng:</span>
                <div class="qty-control">
                    <button class="qty-btn" data-action="minus"><i class="bi bi-dash"></i></button>
                    <input type="number" class="qty-input" value="1" id="detail-qty" min="1" max="10">
                    <button class="qty-btn" data-action="plus"><i class="bi bi-plus"></i></button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-3 mb-4 flex-wrap">
                @php
                    $isDefaultOutOfStock = ($plans[2]['stock'] ?? 0) <= 0;
                @endphp
                <button class="btn-add-cart flex-grow-1 py-3 fs-6"
                    id="btn-main-add-cart"
                    data-add-cart
                    data-id="detail_{{ $slug }}"
                    data-name="{{ $brand['name'] }} 1 Năm"
                    data-brand="{{ $brand['name'] }}"
                    data-plan="1year"
                    data-price="{{ $plans[2]['price'] }}"
                    data-color="{{ $brand['color'] }}"
                    data-slug="{{ $slug }}"
                    @if($isDefaultOutOfStock)
                        disabled
                        style="background:#94a3b8; border-color:#94a3b8; cursor:not-allowed; pointer-events:none;"
                    @endif>
                    @if($isDefaultOutOfStock)
                        <i class="bi bi-x-circle me-2"></i>Tạm Hết Hàng
                    @else
                        <i class="bi bi-bag-plus me-2"></i>Thêm Vào Giỏ Hàng
                    @endif
                </button>
                <button class="btn-wishlist" data-wishlist data-id="{{ $defaultPlan['id'] }}" style="width:48px;height:48px;border-radius:var(--radius);font-size:20px">
                    <i class="bi bi-heart"></i>
                </button>
            </div>

            <!-- Share/Contact -->
            <div class="d-flex align-items-center gap-3 pt-3 border-top">
                <span class="text-muted" style="font-size:13px">Hỗ trợ nhanh:</span>
                <a href="#" class="btn btn-sm rounded-pill px-3" style="background:#e3f2fd;color:#1565c0;font-size:12.5px;font-weight:600;border:1px solid #bbdefb">
                    <i class="bi bi-telegram me-1"></i>Telegram
                </a>
                <a href="#" class="btn btn-sm rounded-pill px-3" style="background:#e8f5e9;color:#2e7d32;font-size:12.5px;font-weight:600;border:1px solid #c8e6c9">
                    <i class="bi bi-chat-dots me-1"></i>Zalo
                </a>
                <a href="tel:0909999999" class="btn btn-sm rounded-pill px-3" style="background:#fce4ec;color:#c62828;font-size:12.5px;font-weight:600;border:1px solid #f8bbd0">
                    <i class="bi bi-telephone me-1"></i>Hotline
                </a>
            </div>
        </div>
    </div>

    <!-- Features Tabs -->
    <div class="mt-5">
        <ul class="nav nav-tabs border-bottom-0 mb-0" id="productTabs">
            <li class="nav-item">
                <button class="nav-link active fw-600" data-bs-toggle="tab" data-bs-target="#tab-features">
                    <i class="bi bi-grid-3x3-gap me-2"></i>Tính Năng
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-600" data-bs-toggle="tab" data-bs-target="#tab-guide">
                    <i class="bi bi-book me-2"></i>Hướng Dẫn Kích Hoạt
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-600" data-bs-toggle="tab" data-bs-target="#tab-reviews">
                    <i class="bi bi-chat-square-text me-2"></i>Đánh Giá ({{ number_format($curReviews) }})
                </button>
            </li>
        </ul>
        <div class="tab-content bg-white border rounded-3 p-4" style="border-color:var(--gray-200)!important;border-top-left-radius:0!important">
            <!-- Features Tab -->
            <div class="tab-pane fade show active" id="tab-features">
                <div class="row g-4">
                    @php
                    $features = [
                        ['icon'=>'bi-shield-lock-fill','color'=>'#2563eb','title'=>'Bảo Mật Tối Đa','desc'=>'Mã hóa AES-256-GCM chuẩn quân đội, không thể bẻ khóa. Giao thức ' . $brand['protocol'] . ' nhanh và an toàn.'],
                        ['icon'=>'bi-eye-slash-fill','color'=>'#16a34a','title'=>'Zero-Log Policy','desc'=>'Tuyệt đối không lưu bất kỳ nhật ký hoạt động. Đã được kiểm toán bởi bên thứ ba độc lập.'],
                        ['icon'=>'bi-lightning-fill','color'=>'#d97706','title'=>'Tốc Độ ' . $brand['speed'],'desc'=>'Công nghệ tối ưu tốc độ giúp duyệt web, stream 4K, chơi game mượt mà không lag.'],
                        ['icon'=>'bi-globe2','color'=>'#7c3aed','title'=>$brand['servers'] . ' Máy Chủ','desc'=>'Mạng lưới server tại ' . $brand['countries'] . ' phủ sóng toàn cầu, luôn có máy chủ gần bạn nhất.'],
                        ['icon'=>'bi-phone-fill','color'=>'#0ea5e9','title'=>($brand['devices'] === -1 ? 'Không Giới Hạn' : $brand['devices'].' Thiết Bị'),'desc'=>'Sử dụng trên Windows, macOS, Android, iOS, Linux và nhiều thiết bị khác cùng lúc.'],
                        ['icon'=>'bi-arrow-repeat','color'=>'#dc2626','title'=>'Hoàn Tiền ' . $brand['refund'],'desc'=>'Không hài lòng? Hoàn tiền 100% trong ' . $brand['refund'] . ' không cần lý do, không rườm rà.'],
                    ];
                    @endphp
                    @foreach($features as $feat)
                    <div class="col-md-4">
                        <div class="d-flex gap-3">
                            <div style="width:44px;height:44px;background:{{ $feat['color'] }}15;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:{{ $feat['color'] }};font-size:20px">
                                <i class="bi {{ $feat['icon'] }}"></i>
                            </div>
                            <div>
                                <div class="fw-700 mb-1" style="font-size:14px;color:var(--gray-900)">{{ $feat['title'] }}</div>
                                <div style="font-size:13px;color:var(--gray-500);line-height:1.6">{{ $feat['desc'] }}</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Guide Tab -->
            <div class="tab-pane fade" id="tab-guide">
                <div class="row g-4">
                    <div class="col-md-8">
                        <h6 class="fw-700 mb-4" style="color:var(--gray-800)">Hướng Dẫn Kích Hoạt {{ $brand['name'] }}</h6>
                        @php
                        $steps = [
                            'Sau khi thanh toán, bạn sẽ nhận được email chứa key/tài khoản VPN trong vòng 5–15 phút.',
                            'Truy cập trang web chính thức của ' . $brand['name'] . ' và tải ứng dụng cho thiết bị của bạn.',
                            'Mở ứng dụng, chọn "Đăng Nhập" hoặc "Kích Hoạt" và nhập thông tin tài khoản được cung cấp.',
                            'Chọn máy chủ phù hợp (gần Việt Nam để tốc độ tốt nhất) và nhấn "Kết Nối".',
                            'Hoàn tất! VPN đã hoạt động. Kiểm tra IP tại whatismyip.com để xác nhận.',
                        ];
                        @endphp
                        @foreach($steps as $si => $step)
                        <div class="d-flex gap-3 mb-4">
                            <div style="width:32px;height:32px;background:linear-gradient(135deg,{{ $brand['color'] }},{{ $brand['color'] }}99);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:14px;flex-shrink:0">{{ $si+1 }}</div>
                            <div class="pt-1" style="font-size:14px;color:var(--gray-600);line-height:1.7">{{ $step }}</div>
                        </div>
                        @endforeach
                        <div class="alert-notice">
                            <i class="bi bi-info-circle-fill"></i>
                            Gặp khó khăn? Liên hệ ngay Telegram <strong>@specademy</strong> hoặc Zalo <strong>0708910952 / 0569012134</strong> để được hỗ trợ miễn phí!
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-4 rounded-3" style="background:var(--gray-50);border:1px solid var(--gray-200)">
                            <h6 class="fw-700 mb-3" style="font-size:13px">Thiết Bị Hỗ Trợ</h6>
                            @foreach([['bi-windows','Windows 7/8/10/11'],['bi-apple','macOS 10.12+'],['bi-android2','Android 5.0+'],['bi-phone','iOS 12+'],['bi-ubuntu','Linux'],['bi-tv','Smart TV'],['bi-router','Router'],['bi-browser-chrome','Browser Extension']] as [$icon,$device])
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi {{ $icon }} text-primary" style="font-size:14px;width:18px"></i>
                                <span style="font-size:13px;color:var(--gray-600)">{{ $device }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews Tab -->
            <div class="tab-pane fade" id="tab-reviews">
                <div class="row g-3">
                    @foreach($realReviews as $rv)
                    <div class="col-md-6">
                        <div class="testimonial-card">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="testimonial-avatar" style="background:linear-gradient(135deg,{{ $brand['color'] }},{{ $brand['color'] }}88)">
                                    {{ strtoupper(mb_substr($rv['name'], 0, 1)) }}
                                </div>
                                <div>
                                    <div class="testimonial-name">{{ $rv['name'] }}</div>
                                    <div class="testimonial-tag">{{ $rv['date'] }}</div>
                                </div>
                                <div class="ms-auto rating-stars">
                                    @for($s=1;$s<=$rv['star'];$s++)<i class="bi bi-star-fill" style="font-size:11.5px;color:#ffb300"></i>@endfor
                                    @for($s=$rv['star']+1;$s<=5;$s++)<i class="bi bi-star" style="font-size:11.5px;color:#ccc"></i>@endfor
                                </div>
                            </div>
                            <p class="testimonial-text mb-0">{{ $rv['text'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra_js')
<script>
// Sync plan selection with qty and add-to-cart
document.querySelectorAll('.plan-option').forEach(opt => {
    opt.addEventListener('click', function() {
        document.querySelectorAll('.plan-option').forEach(el => el.classList.remove('selected'));
        this.classList.add('selected');

        const price = this.dataset.price;
        const plan  = this.dataset.plan;
        const period = this.dataset.period;
        const image = this.dataset.image;
        const btn = document.getElementById('btn-main-add-cart');
        const stock = parseInt(this.dataset.stock || 0);
        if (btn) {
            btn.dataset.price = price;
            btn.dataset.plan  = plan;
            btn.dataset.name  = '{{ $brand["name"] }} ' + period;

            if (stock <= 0) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Tạm Hết Hàng';
                btn.style.background = '#94a3b8';
                btn.style.borderColor = '#94a3b8';
                btn.style.cursor = 'not-allowed';
                btn.style.pointerEvents = 'none';
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-bag-plus me-2"></i>Thêm Vào Giỏ Hàng';
                btn.style.background = '';
                btn.style.borderColor = '';
                btn.style.cursor = '';
                btn.style.pointerEvents = '';
            }
        }

        const id = this.dataset.id;
        const wishlistBtn = document.querySelector('[data-wishlist]');
        if (wishlistBtn && id) {
            wishlistBtn.dataset.id = id;
            if (window.dbWishlist && window.dbWishlist.includes(parseInt(id))) {
                const icon = wishlistBtn.querySelector('i');
                if (icon) icon.className = 'bi bi-heart-fill';
                wishlistBtn.style.color = '#ef4444';
            } else {
                const icon = wishlistBtn.querySelector('i');
                if (icon) icon.className = 'bi bi-heart';
                wishlistBtn.style.color = '';
            }
        }
        // Update old price display too
        const oldPrice = parseInt(this.dataset.old || 0);
        const curPrice = parseInt(price);
        const saving = oldPrice - curPrice;
        const pct    = Math.round(saving / oldPrice * 100);
        const priceEl = document.getElementById('selected-price');
        const periodEl = document.getElementById('selected-period');
        if (priceEl) priceEl.textContent = curPrice.toLocaleString('vi-VN') + 'đ';
        if (periodEl) periodEl.textContent = '/ ' + period;

        // Swap product image or fallback icon
        const mainImg = document.getElementById('main-product-image');
        const iconContainer = document.getElementById('main-product-icon-container');
        if (mainImg && iconContainer) {
            if (image) {
                mainImg.src = image;
                mainImg.classList.remove('d-none');
                iconContainer.classList.add('d-none');
            } else {
                mainImg.classList.add('d-none');
                iconContainer.classList.remove('d-none');
            }
        }

        // Update specs table dynamically
        const ids = ['servers', 'countries', 'devices', 'speed', 'protocol', 'headquarter', 'founded', 'refund'];
        ids.forEach(id => {
            const el = document.getElementById('spec-' + id);
            if (el && this.dataset[id]) {
                el.textContent = this.dataset[id];
            }
        });

        // Update description dynamically
        const descEl = document.getElementById('main-product-description');
        if (descEl && this.dataset.desc) {
            descEl.textContent = this.dataset.desc;
        }
    });
});
</script>
@endsection

