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

$firstProduct = collect($dbProducts ?? [])->first();
$firstBrand = $firstProduct ? strtolower($firstProduct->brand) : '';
if (!empty($firstBrand) && isset($brandData[$firstBrand])) {
    $brand = $brandData[$firstBrand];
} elseif (isset($brandData[$slug])) {
    $brand = $brandData[$slug];
} else {
    $brandName = $firstProduct ? ($firstProduct->brand ?: $firstProduct->name) : 'Sản phẩm';
    $brand = [
        'name'        => $brandName,
        'color'       => ($firstProduct && $firstProduct->color) ? $firstProduct->color : '#7c3aed',
        'bg'          => '#f5f3ff',
        'icon'        => 'bi-box-seam-fill',
        'servers'     => $firstProduct->servers ?? '',
        'countries'   => $firstProduct->countries ?? '',
        'devices'     => $firstProduct->devices ?? '',
        'speed'       => $firstProduct->speed ?? '',
        'protocol'    => $firstProduct->protocol ?? '',
        'headquarter' => $firstProduct->headquarter ?? '',
        'refund'      => $firstProduct->refund ?? '30 ngày',
        'founded'     => $firstProduct->founded ?? '',
        'desc'        => $firstProduct->description ?? '',
    ];
}

$cleanBrandName = str_replace('-', ' ', $brand['name']);

$plans = [];
$hasPopular = false;
foreach ($dbProducts ?? [] as $dbProd) {
    if ($dbProd->is_popular) {
        $hasPopular = true;
        break;
    }
}

foreach ($dbProducts ?? [] as $dbProd) {
    $planKey = $dbProd->plan;
    $label = \App\Models\Product::formatPlanDuration($planKey);
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
        'popular' => $hasPopular ? (bool) $dbProd->is_popular : ($planKey === '1year'),
        'image_path' => $dbProd->image_url,
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
        'require_upgrade_email' => (bool)$dbProd->require_upgrade_email,
        'meta_title' => $dbProd->meta_title,
        'meta_description' => $dbProd->meta_description,
        'specs' => $dbProd->specs,
        'rating' => $dbProd->rating,
        'reviews' => $dbProd->reviews,
        'plan_note' => $dbProd->plan_note,
    ];
}

if (empty($plans)) {
    $plans = [
        ['id'=>1, 'key'=>'1month', 'label'=>'1 Tháng', 'price'=>120000, 'old'=>200000, 'save'=>null, 'popular'=>false, 'image_path'=>null, 'servers'=>'5,400+', 'countries'=>'60+', 'devices'=>'6', 'speed'=>'Nhanh', 'protocol'=>'WireGuard', 'headquarter'=>'Panama', 'founded'=>'2012', 'refund'=>'30 ngày', 'description'=>'NordVPN là một dịch vụ VPN phổ biến.', 'require_upgrade_email'=>false, 'rating'=>0.0, 'reviews'=>0],
    ];
}

$defaultPlan = collect($plans)->firstWhere('popular', true) ?: ($plans[0] ?? null);
$curRating = floatval($defaultPlan['rating'] ?? 0.0);
$curReviews = intval($defaultPlan['reviews'] ?? 0);
@endphp

@push('head')
@if($defaultPlan)
<script type="application/ld+json">
{
  "@@context": "https://schema.org/",
  "@@type": "Product",
  "name": "{{ $firstProduct ? $firstProduct->name : ($brand['name'] . ' ' . $defaultPlan['label']) }}",
  "image": "{{ !empty($defaultPlan['image_path']) ? asset($defaultPlan['image_path']) : '' }}",
  "description": "{{ strip_tags($defaultPlan['description'] ?? $brand['desc']) }}",
  "brand": {
    "@@type": "Brand",
    "name": "{{ $cleanBrandName }}"
  },
  "offers": {
    "@@type": "Offer",
    "url": "{{ url()->current() }}",
    "priceCurrency": "VND",
    "price": "{{ $defaultPlan['price'] }}",
    "itemCondition": "https://schema.org/NewCondition",
    "availability": "{{ $defaultPlan['stock'] > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}"
  },
  "aggregateRating": {
    "@@type": "AggregateRating",
    "ratingValue": "{{ $curRating }}",
    "reviewCount": "{{ $curReviews }}"
  }
}
</script>
@endif
<style>
.plan-option {
    transition: all 0.2s ease-in-out !important;
}
@media (hover: hover) {
    .plan-option:hover {
        border-color: rgba(124, 58, 237, 0.5) !important;
        background: rgba(124, 58, 237, 0.02) !important;
    }
}
.plan-option.selected {
    border-color: var(--primary-light) !important;
    background: rgba(124, 58, 237, 0.06) !important;
    box-shadow: 0 0 0 1px var(--primary-light) !important;
}
</style>
@endpush

@extends('layouts.app')

@php
    $storeName = $settings['store_name'] ?? 'VPNStore';
    
    $seoTitle = isset($category) && !empty($category->seo_title) ? $category->seo_title : null;
    if (!$seoTitle && $defaultPlan && !empty($defaultPlan['meta_title'])) {
        $seoTitle = $defaultPlan['meta_title'];
    }
    
    if (!$seoTitle) {
        $seoTitle = 'Tài Khoản ' . ($firstProduct ? $firstProduct->name : $brand['name']) . ' Bản Quyền Chính Hãng';
    } else {
        // Strip store suffix from DB meta_title if already present to avoid duplication in layout
        $seoTitle = preg_replace('/\s*[\|—–\-]\s*' . preg_quote($storeName, '/') . '$/i', '', $seoTitle);
    }

    $seoDesc = isset($category) && !empty($category->seo_description) ? $category->seo_description : null;
    if (!$seoDesc && $defaultPlan && !empty($defaultPlan['meta_description'])) {
        $seoDesc = $defaultPlan['meta_description'];
    }
    if (!$seoDesc) {
        $seoDesc = 'Mua tài khoản / key ' . ($firstProduct ? $firstProduct->name : $brand['name']) . ' bản quyền chính hãng tại ' . $storeName . ' với giá tốt nhất thị trường. Giao key tự động nhanh chóng, hỗ trợ kích hoạt miễn phí và bảo hành uy tín 1 đổi 1.';
    }

    $cleanBrandName = str_replace('-', ' ', $brand['name']);
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDesc)
@section('meta_keywords', strtolower($cleanBrandName) . ' ban quyen, mua ' . strtolower($cleanBrandName) . ', tai khoan ' . strtolower($cleanBrandName) . ' chinh hang, key ' . strtolower($cleanBrandName) . ' gia tot, ' . strtolower($storeName))

@section('content')

<section class="section">
    <div class="container">
        {{-- Breadcrumb --}}
        <nav style="display:flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--text-muted); margin-bottom:32px;">
            <a href="{{ route('home') }}" style="color:var(--text-muted); text-decoration:none;">Trang Chủ</a>
            <span>/</span>
            <a href="{{ route('products') }}" style="color:var(--text-muted); text-decoration:none;">Sản Phẩm</a>
            <span>/</span>
            <span style="color:var(--text-primary);">{{ $firstProduct ? $firstProduct->name : $brand['name'] }}</span>
        </nav>

        <div class="product-detail-layout">

            {{-- ===== LEFT: PRODUCT INFO ===== --}}
            <div class="product-info-column">
                {{-- Product Image & Placeholder --}}
                <div class="product-gallery-card" style="position:relative; aspect-ratio:16/9; background:linear-gradient(135deg, {{ $brand['color'] }}22, {{ $brand['color'] }}55); border-radius:var(--radius-xl); overflow:hidden; margin-bottom:32px; border:1px solid var(--border); display:flex; align-items:center; justify-content:center;">
                    <img src="{{ !empty($defaultPlan['image_path']) ? asset($defaultPlan['image_path']) : '' }}" alt="{{ $brand['name'] }}" id="main-product-image" style="width:100%; height:100%; object-fit:cover;" class="{{ empty($defaultPlan['image_path']) ? 'd-none' : '' }}">
                    
                    <div id="main-product-icon-container" class="{{ !empty($defaultPlan['image_path']) ? 'd-none' : '' }}" style="text-align:center;">
                        <div style="font-size:5rem; color:#fff; line-height:1; filter:drop-shadow(0 4px 12px rgba(0,0,0,0.1));">
                            <i class="bi {{ $brand['icon'] }}"></i>
                        </div>
                        <div style="font-size:1.2rem; font-weight:800; color:#fff; margin-top:16px;">{{ $brand['name'] }}</div>
                    </div>
                </div>

                <div class="product-details-group">
                {{-- Specs & Description --}}
                <div class="card" style="padding:24px; margin-bottom:32px;">
                    <h2 style="font-size:1.1rem; font-weight:800; color:var(--text-primary); margin-bottom:16px;">Mô Tả Sản Phẩm</h2>
                    <p id="main-product-description" style="color:var(--text-secondary); line-height:1.8; font-size:0.925rem; white-space:pre-line;">
                        {{ $defaultPlan['description'] ?? $brand['desc'] }}
                    </p>
                </div>

                {{-- Specs Grid --}}
                <div class="card" style="padding:24px; margin-bottom:32px;">
                    <h2 style="font-size:1.1rem; font-weight:800; color:var(--text-primary); margin-bottom:16px;">Thông Số Kỹ Thuật</h2>
                    <div class="specs-grid" id="specs-table-container">
                        @if(!empty($defaultPlan['specs']) && is_array($defaultPlan['specs']))
                            @foreach($defaultPlan['specs'] as $spec)
                                @if(!empty($spec['name']) || !empty($spec['value']))
                                <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                    <span style="color:var(--text-muted);">{{ $spec['name'] }}</span>
                                    <strong>{{ $spec['value'] }}</strong>
                                </div>
                                @endif
                            @endforeach
                        @else
                            {{-- Fallback --}}
                            @if(!empty($defaultPlan['servers']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Máy chủ (Servers)</span>
                                <strong id="spec-servers">{{ $defaultPlan['servers'] }}</strong>
                            </div>
                            @endif
                            @if(!empty($defaultPlan['countries']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Quốc gia (Countries)</span>
                                <strong id="spec-countries">{{ $defaultPlan['countries'] }}</strong>
                            </div>
                            @endif
                            @if(!empty($defaultPlan['devices']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Thiết bị đồng thời</span>
                                <strong id="spec-devices">{{ $defaultPlan['devices'] }}</strong>
                            </div>
                            @endif
                            @if(!empty($defaultPlan['speed']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Tốc độ kết nối</span>
                                <strong id="spec-speed">{{ $defaultPlan['speed'] }}</strong>
                            </div>
                            @endif
                            @if(!empty($defaultPlan['protocol']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Giao thức hỗ trợ</span>
                                <strong id="spec-protocol">{{ $defaultPlan['protocol'] }}</strong>
                            </div>
                            @endif
                            @if(!empty($defaultPlan['headquarter']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Trụ sở quốc gia</span>
                                <strong id="spec-headquarter">{{ $defaultPlan['headquarter'] }}</strong>
                            </div>
                            @endif
                            @if(!empty($defaultPlan['founded']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Năm thành lập</span>
                                <strong id="spec-founded">{{ $defaultPlan['founded'] }}</strong>
                            </div>
                            @endif
                            @if(!empty($defaultPlan['refund']))
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">Chính sách hoàn tiền</span>
                                <strong id="spec-refund">{{ $defaultPlan['refund'] }}</strong>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Activation Guide --}}
                <div class="card" style="padding:24px; margin-bottom:32px;">
                    <h2 style="font-size:1.1rem; font-weight:800; color:var(--text-primary); margin-bottom:16px;">Hướng Dẫn Kích Hoạt</h2>
                    <ul style="padding-left:20px; color:var(--text-secondary); line-height:1.8; font-size:0.9rem; display:flex; flex-direction:column; gap:8px;">
                        <li>Sau khi thanh toán thành công, hệ thống sẽ tự động gửi thông tin tài khoản / license key qua email trong vòng 1-30 phút.</li>
                        <li>Tải ứng dụng chính thức của {{ $brand['name'] }} trên thiết bị của bạn.</li>
                        <li>Đăng nhập ứng dụng bằng thông tin tài khoản được cấp hoặc nhập License Key để kích hoạt quyền lợi Premium.</li>
                        <li>Mọi thắc mắc hoặc cần hỗ trợ cài đặt, vui lòng liên hệ Zalo / Telegram hỗ trợ kỹ thuật hiển thị trên website.</li>
                    </ul>
                </div>

                {{-- Reviews List --}}
                <div class="card" style="padding:24px;">
                    <h2 style="font-size:1.1rem; font-weight:800; color:var(--text-primary); margin-bottom:16px;"><i class="bi bi-chat-left-text-fill text-primary" style="margin-right: 8px;"></i>Đánh Giá Khách Hàng</h2>
                    @if(!empty($realReviews))
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            @foreach($realReviews as $rv)
                            <div class="review-card-wrap" style="padding:16px; border:1px solid var(--border); border-radius:var(--radius-lg); background:var(--bg-elevated);">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                    <strong style="color:var(--text-primary); font-size:0.9rem;"><i class="bi bi-person-fill me-1"></i>{{ $rv['name'] }}</strong>
                                    <span style="font-size:0.75rem; color:var(--text-muted);">{{ $rv['date'] }}</span>
                                </div>
                                <div style="display:flex; gap:2px; margin-bottom:8px;">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi bi-star-fill" style="color:{{ $i <= $rv['star'] ? 'var(--warning)' : 'var(--border)' }}; font-size:0.75rem;"></i>
                                    @endfor
                                </div>
                                <p style="font-size:0.875rem; color:var(--text-secondary); line-height:1.6; margin-bottom:0;">{{ $rv['text'] }}</p>
                            </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-4" id="reviewsPaginationContainer"></div>
                    @else
                        <div style="text-align:center; padding:32px 0; color:var(--text-muted);">
                            <div style="margin-bottom:8px;"><i class="bi bi-chat-left-text text-muted" style="font-size: 2.5rem;"></i></div>
                            <p style="font-size:0.9rem; margin-bottom:0;">Chưa có đánh giá nào cho sản phẩm này.</p>
                        </div>
                    @endif
                </div>
                </div>
            </div>

            {{-- ===== RIGHT: PURCHASE BOX ===== --}}
            <div class="product-purchase-column product-sidebar-sticky">
                <div class="product-purchase-card card" style="border-color:rgba(124,58,237,0.3); padding:24px;">
                    <span style="font-size:0.75rem; font-weight:800; color:var(--accent); text-transform:uppercase; letter-spacing:0.05em; display:block; margin-bottom:6px;">Premium Account</span>
                    <h1 style="font-size:1.6rem; font-weight:800; margin-bottom:12px; color:var(--text-primary);">{{ $firstProduct ? $firstProduct->name : $brand['name'] }}</h1>
                    
                    {{-- Rating Stars --}}
                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:20px;">
                        <div class="stars" style="display:flex; gap:2px; color:var(--warning); font-size:0.85rem;">
                            @if($curReviews > 0)
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($curRating))
                                        <i class="bi bi-star-fill"></i>
                                    @elseif($i - $curRating < 1)
                                        <i class="bi bi-star-half"></i>
                                    @else
                                        <i class="bi bi-star" style="color:var(--border)"></i>
                                    @endif
                                @endfor
                            @else
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star" style="color:var(--border)"></i>
                                @endfor
                            @endif
                        </div>
                        <span style="font-size:0.85rem; font-weight:700; color:var(--text-primary);">{{ $curReviews > 0 ? number_format($curRating, 1) : '0.0' }}</span>
                        <span style="font-size:0.85rem; color:var(--text-muted);">({{ number_format($curReviews) }} đánh giá)</span>
                    </div>
                    {{-- Price Display --}}
                    <div style="margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid var(--border);">
                        <div class="product-pricing" style="margin-bottom:6px; display:flex; align-items:baseline; gap:8px;">
                            <span class="price-current" style="font-size:2.2rem; font-weight:900; color:var(--primary-light);" id="selected-price">{{ number_format($defaultPlan['price'] ?? 0) }}đ</span>
                            <span class="price-period" style="font-size:1rem; color:var(--text-muted);" id="selected-period">/ {{ $defaultPlan['label'] ?? 'năm' }}</span>
                        </div>
                        <div style="font-size:0.75rem; text-decoration:line-through; color:var(--text-muted);" id="selected-old-price">
                            Gốc: {{ number_format($defaultPlan['old'] ?? 0) }}đ
                        </div>
                    </div>

                    {{-- Plans Selector --}}
                    <div style="margin-bottom:24px;">
                        <span style="font-size:0.8rem; font-weight:700; color:var(--text-secondary); display:block; margin-bottom:10px;">Chọn Gói Sử Dụng:</span>
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($plans as $p)
                            <div class="plan-option {{ ($p['id'] == $defaultPlan['id']) ? 'selected' : '' }}"
                                 data-id="{{ $p['id'] }}"
                                 data-price="{{ $p['price'] }}"
                                 data-plan="{{ $p['key'] }}"
                                 data-period="{{ $p['label'] }}"
                                 data-old="{{ $p['old'] }}"
                                 data-stock="{{ $p['stock'] }}"
                                 data-image="{{ $p['image_path'] ? asset($p['image_path']) : '' }}"
                                 data-servers="{{ $p['servers'] }}"
                                 data-countries="{{ $p['countries'] }}"
                                 data-devices="{{ $p['devices'] }}"
                                 data-speed="{{ $p['speed'] }}"
                                 data-protocol="{{ $p['protocol'] }}"
                                 data-headquarter="{{ $p['headquarter'] }}"
                                 data-founded="{{ $p['founded'] }}"
                                 data-refund="{{ $p['refund'] }}"
                                 data-desc="{{ $p['description'] }}"
                                 data-specs="{{ json_encode($p['specs'] ?? []) }}"
                                 data-require-email="{{ $p['require_upgrade_email'] ? '1' : '0' }}"
                                 style="padding:12px; border:1px solid var(--border); border-radius:var(--radius); cursor:pointer; background:var(--bg-elevated); transition:var(--transition); display:flex; justify-content:space-between; align-items:center;">
                                <div style="display:flex; flex-direction:column; gap:2px;">
                                    <strong style="font-size:0.9rem; color:var(--text-primary);">Gói {{ $p['label'] }}</strong>
                                    @if(!empty($p['plan_note']))
                                        <span style="font-size:0.75rem; color:var(--text-muted); font-weight:500;">{{ $p['plan_note'] }}</span>
                                    @endif
                                    @if($p['save'])
                                        <span class="badge badge-sale" style="font-size:0.65rem; width:fit-content; padding:2px 6px;">{{ $p['save'] }}</span>
                                    @endif
                                </div>
                                <div style="text-align:right;">
                                    <strong style="color:var(--primary-light); font-size:0.95rem;">{{ number_format($p['price']) }}đ</strong>
                                    <div style="font-size:0.75rem; text-decoration:line-through; color:var(--text-muted);">{{ number_format($p['old']) }}đ</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Quantity Selector --}}
                    <div style="display:flex; align-items:center; gap:12px; margin-bottom:24px;">
                        <span style="font-size:0.85rem; color:var(--text-secondary); font-weight:600;">Số lượng:</span>
                        <div class="qty-control" style="background:var(--bg-input);">
                            <button class="qty-btn" data-action="minus"><i class="bi bi-dash"></i></button>
                            <input type="number" class="qty-input" value="1" id="detail-qty" min="1" max="10" readonly style="background:transparent; border:none; width:40px; text-align:center; color:var(--text-primary); font-weight:700;">
                            <button class="qty-btn" data-action="plus"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>

                    {{-- Purchase Buttons --}}
                    <div style="display:flex; gap:12px; margin-bottom:24px;">
                        @php
                            $isDefaultOutOfStock = ($defaultPlan['stock'] ?? 0) <= 0;
                            $btnDisabledAttr = $isDefaultOutOfStock ? 'disabled style="background:#94a3b8; border-color:#94a3b8; cursor:not-allowed; pointer-events:none;"' : '';
                        @endphp
                        <button class="btn btn-primary btn-lg" style="flex:1; padding:14px; font-weight:700;"
                                id="btn-main-add-cart"
                                data-add-cart
                                data-id="{{ $defaultPlan['id'] ?? '' }}"
                                data-name="{{ $brand['name'] }} {{ $defaultPlan['label'] ?? '' }}"
                                data-brand="{{ $brand['name'] }}"
                                data-plan="{{ $defaultPlan['key'] ?? '' }}"
                                data-price="{{ $defaultPlan['price'] ?? 0 }}"
                                data-color="{{ $brand['color'] }}"
                                data-slug="{{ $slug }}"
                                data-require-email="{{ $defaultPlan['require_upgrade_email'] ? '1' : '0' }}"
                                {!! $btnDisabledAttr !!}>
                            @if($isDefaultOutOfStock)
                                <i class="bi bi-x-circle me-2"></i>Tạm Hết Hàng
                            @else
                                <i class="bi bi-bag-plus-fill me-2"></i>Thêm Vào Giỏ
                            @endif
                        </button>
                        <button class="btn btn-outline" data-wishlist data-id="{{ $defaultPlan['id'] }}" style="width:48px; height:48px; border-radius:var(--radius); padding:0; display:flex; align-items:center; justify-content:center;">
                            <i class="bi bi-heart" style="font-size:1.2rem;"></i>
                        </button>
                    </div>

                    {{-- Guarantees --}}
                    <div style="display:flex; flex-direction:column; gap:12px; padding-top:20px; border-top:1px solid var(--border);">
                        <div style="display:flex; align-items:center; gap:8px; font-size:0.8rem; color:var(--text-muted);">
                            <i class="bi bi-shield-fill-check text-success"></i>
                            <span>Bảo hành hoàn tiền trong {{ $brand['refund'] }}</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; font-size:0.8rem; color:var(--text-muted);">
                            <i class="bi bi-patch-check-fill text-success"></i>
                            <span>Cam kết key bản quyền chính hãng 100%</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:8px; font-size:0.8rem; color:var(--text-muted);">
                            <i class="bi bi-lightning-fill text-warning"></i>
                            <span>Giao key tự động trong vòng 1-30 phút</span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('extra_js')
<script>
// Plan options click event handlers
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
            btn.dataset.id = this.dataset.id;
            btn.dataset.price = price;
            btn.dataset.plan  = plan;
            btn.dataset.name  = '{{ $brand["name"] }} ' + period;
            btn.dataset.requireEmail = this.dataset.requireEmail || '0';

            if (stock <= 0) {
                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-x-circle me-2"></i>Tạm Hết Hàng';
                btn.style.background = '#94a3b8';
                btn.style.borderColor = '#94a3b8';
                btn.style.cursor = 'not-allowed';
                btn.style.pointerEvents = 'none';
            } else {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-bag-plus-fill me-2"></i>Thêm Vào Giỏ';
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

        // Update old price display and save display
        const oldPrice = parseInt(this.dataset.old || 0);
        const curPrice = parseInt(price);
        const priceEl = document.getElementById('selected-price');
        const periodEl = document.getElementById('selected-period');
        const oldPriceEl = document.getElementById('selected-old-price');
        if (priceEl) priceEl.textContent = curPrice.toLocaleString('vi-VN') + 'đ';
        if (periodEl) periodEl.textContent = '/ ' + period;
        if (oldPriceEl) oldPriceEl.textContent = 'Gốc: ' + oldPrice.toLocaleString('vi-VN') + 'đ';

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
        const specsContainer = document.getElementById('specs-table-container');
        if (specsContainer) {
            let specs = [];
            try {
                specs = JSON.parse(this.dataset.specs || '[]');
            } catch (e) {
                console.error("Error parsing plan specs dataset", e);
            }

            function escapeHtml(text) {
                if (!text) return '';
                return String(text)
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }

            if (specs && specs.length > 0) {
                let html = '';
                specs.forEach(spec => {
                    html += `
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                            <span style="color:var(--text-muted);">${escapeHtml(spec.name)}</span>
                            <strong>${escapeHtml(spec.value)}</strong>
                        </div>
                    `;
                });
                specsContainer.innerHTML = html;
            } else {
                const ids = ['servers', 'countries', 'devices', 'speed', 'protocol', 'headquarter', 'founded', 'refund'];
                const fallbackLabels = {
                    servers: 'Máy chủ (Servers)',
                    countries: 'Quốc gia (Countries)',
                    devices: 'Thiết bị đồng thời',
                    speed: 'Tốc độ kết nối',
                    protocol: 'Giao thức hỗ trợ',
                    headquarter: 'Trụ sở quốc gia',
                    founded: 'Năm thành lập',
                    refund: 'Chính sách hoàn tiền'
                };
                let html = '';
                ids.forEach(id => {
                    if (this.dataset[id]) {
                        html += `
                            <div style="display:flex; justify-content:space-between; border-bottom:1px solid var(--border); padding-bottom:8px; font-size:0.875rem;">
                                <span style="color:var(--text-muted);">${fallbackLabels[id]}</span>
                                <strong id="spec-${id}">${escapeHtml(this.dataset[id])}</strong>
                            </div>
                        `;
                    }
                });
                specsContainer.innerHTML = html;
            }
        }

        // Update description dynamically
        const descEl = document.getElementById('main-product-description');
        if (descEl && this.dataset.desc) {
            descEl.textContent = this.dataset.desc;
        }
    });
});

// Reviews pagination (4 reviews per page)
document.addEventListener('DOMContentLoaded', function() {
    let currentReviewPage = 1;
    const reviewsPerPage = 4;
    const reviewCards = document.querySelectorAll('.review-card-wrap');
    
    function showReviewPage(page) {
        currentReviewPage = page;
        const totalReviews = reviewCards.length;
        const totalPages = Math.ceil(totalReviews / reviewsPerPage);
        
        const startIndex = (page - 1) * reviewsPerPage;
        const endIndex = startIndex + reviewsPerPage;
        
        reviewCards.forEach((card, index) => {
            if (index >= startIndex && index < endIndex) {
                card.style.setProperty('display', 'block', 'important');
            } else {
                card.style.setProperty('display', 'none', 'important');
            }
        });
        
        renderReviewsPagination(totalPages);
    }
    
    function renderReviewsPagination(totalPages) {
        const container = document.getElementById('reviewsPaginationContainer');
        if (!container) return;
        container.innerHTML = '';
        
        if (totalPages <= 1) return;
        
        let html = '<nav aria-label="Page navigation"><ul class="pagination pagination-custom d-flex gap-2 align-items-center mb-0" style="list-style:none; padding:0; margin:0;">';
        
        // Prev Button
        html += `<li class="page-item">
            <button type="button" class="page-link shadow-sm d-flex align-items-center justify-content-center" data-page="${currentReviewPage - 1}" ${currentReviewPage === 1 ? 'disabled' : ''} style="width:32px; height:32px; border-radius:8px; border:1px solid var(--border); background:${currentReviewPage === 1 ? 'var(--bg-elevated)' : 'var(--bg-card)'}; color:${currentReviewPage === 1 ? 'var(--text-muted)' : 'var(--text-primary)'}; font-weight:600; cursor:${currentReviewPage === 1 ? 'not-allowed' : 'pointer'};"><i class="bi bi-chevron-left"></i></button>
        </li>`;
        
        // Page Numbers
        for (let i = 1; i <= totalPages; i++) {
            const isActive = i === currentReviewPage;
            html += `<li class="page-item">
                <button type="button" class="page-link shadow-sm d-flex align-items-center justify-content-center" data-page="${i}" style="width:32px; height:32px; border-radius:8px; border:1px solid ${isActive ? 'var(--primary)' : 'var(--border)'}; background:${isActive ? 'var(--primary)' : 'var(--bg-card)'}; color:${isActive ? '#fff' : 'var(--text-primary)'}; font-weight:600; cursor:pointer;">${i}</button>
            </li>`;
        }
        
        // Next Button
        html += `<li class="page-item">
            <button type="button" class="page-link shadow-sm d-flex align-items-center justify-content-center" data-page="${currentReviewPage + 1}" ${currentReviewPage === totalPages ? 'disabled' : ''} style="width:32px; height:32px; border-radius:8px; border:1px solid var(--border); background:${currentReviewPage === totalPages ? 'var(--bg-elevated)' : 'var(--bg-card)'}; color:${currentReviewPage === totalPages ? 'var(--text-muted)' : 'var(--text-primary)'}; font-weight:600; cursor:${currentReviewPage === totalPages ? 'not-allowed' : 'pointer'};"><i class="bi bi-chevron-right"></i></button>
        </li>`;
        
        html += '</ul></nav>';
        container.innerHTML = html;
        
        // Attach click listeners to pagination buttons
        container.querySelectorAll('button[data-page]').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetPage = parseInt(this.dataset.page);
                if (targetPage >= 1 && targetPage <= totalPages) {
                    showReviewPage(targetPage);
                }
            });
        });
    }
    
    if (reviewCards.length > 0) {
        showReviewPage(1);
    }
});
</script>
@endsection
