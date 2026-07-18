@extends('layouts.app')

@section('title', 'Bảng Giá VPN - VPNStore')

@section('content')

<div class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang Chủ</a></li>
                <li class="breadcrumb-item active">Bảng Giá</li>
            </ol>
        </nav>
    </div>
</div>

<div class="page-header text-center">
    <div class="container">
        <span class="section-label mb-3 d-inline-block"><i class="bi bi-cash-coin text-success" style="margin-right: 6px;"></i> Giá Tốt Nhất Thị Trường</span>
        <h1 class="section-title mb-2">Bảng Giá VPN Chi Tiết</h1>
        <p class="section-subtitle mx-auto">So sánh các gói VPN để chọn gói phù hợp nhất với nhu cầu của bạn</p>
    </div>
</div>

@if($activeProducts->isEmpty())
<section class="section" style="background:var(--gray-50)">
    <div class="container text-center py-5">
        <div class="empty-state-icon mb-4" style="font-size:64px;color:var(--gray-300)">
            <i class="bi bi-tag-fill"></i>
        </div>
        <h3 class="fw-800 text-dark mb-2 font-poppins">Hiện Chưa Có Bảng Giá</h3>
        <p class="text-muted mx-auto mb-4" style="max-width:480px;font-size:14.5px">
            Cửa hàng hiện chưa có sản phẩm VPN nào đang hoạt động. Vui lòng quay lại sau khi chúng tôi cập nhật sản phẩm mới!
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary rounded-pill px-4 fw-600">
            <i class="bi bi-arrow-left me-2"></i>Quay Lại Trang Chủ
        </a>
    </div>
</section>
@else
<section class="section" style="background:var(--gray-50)">
    <div class="container">
        <!-- Period Toggle -->
        <div class="text-center mb-5">
            <div class="btn-group" id="periodToggle" role="group">
                <input type="radio" class="btn-check" name="period" id="p1m" value="1month">
                <label class="btn btn-outline-primary fw-600 rounded-start-pill px-4" for="p1m">1 Tháng</label>
                <input type="radio" class="btn-check" name="period" id="p6m" value="6month">
                <label class="btn btn-outline-primary fw-600 px-4" for="p6m">6 Tháng</label>
                <input type="radio" class="btn-check" name="period" id="p1y" value="1year" checked>
                <label class="btn btn-outline-primary fw-600 px-4" for="p1y">1 Năm <span class="badge bg-success ms-1" style="font-size:9px">Phổ Biến</span></label>
                <input type="radio" class="btn-check" name="period" id="p2y" value="2year">
                <label class="btn btn-outline-primary fw-600 rounded-end-pill px-4" for="p2y">2 Năm <span class="badge bg-warning text-dark ms-1" style="font-size:9px">-70%</span></label>
            </div>
        </div>

        @php
        $featureLabels = ['Không lưu log','Kill Switch','P2P/Torrent','Multi-hop','Ad Blocker','Streaming','Thiết bị không giới hạn'];
        $periods = ['1month','6month','1year','2year'];
        
        $vpns = [];
        $grouped = $activeProducts->groupBy('brand');
        
        foreach ($grouped as $brandName => $products) {
            $first = $products->first();
            
            // Build prices and old prices
            $prices = [];
            $oldPrices = [];
            foreach ($products as $p) {
                $prices[$p->plan] = $p->price;
                if ($p->old_price) {
                    $oldPrices[$p->plan] = $p->old_price;
                }
            }
            
            // Map DB features to boolean features array
            $dbFeatures = $first->features ?: [];
            $booleanFeatures = [];
            foreach ($featureLabels as $label) {
                $hasFeature = false;
                foreach ($dbFeatures as $dbF) {
                    $dbFLower = mb_strtolower($dbF);
                    $labelLower = mb_strtolower($label);
                    
                    if ($label === 'Không lưu log' && (str_contains($dbFLower, 'no-log') || str_contains($dbFLower, 'không lưu') || str_contains($dbFLower, 'no log') || str_contains($dbFLower, 'nolog'))) {
                        $hasFeature = true;
                        break;
                    }
                    if ($label === 'Kill Switch' && (str_contains($dbFLower, 'kill') || str_contains($dbFLower, 'switch'))) {
                        $hasFeature = true;
                        break;
                    }
                    if ($label === 'P2P/Torrent' && (str_contains($dbFLower, 'p2p') || str_contains($dbFLower, 'torrent'))) {
                        $hasFeature = true;
                        break;
                    }
                    if ($label === 'Multi-hop' && (str_contains($dbFLower, 'multi') || str_contains($dbFLower, 'hop') || str_contains($dbFLower, 'double'))) {
                        $hasFeature = true;
                        break;
                    }
                    if ($label === 'Ad Blocker' && (str_contains($dbFLower, 'ad') || str_contains($dbFLower, 'block') || str_contains($dbFLower, 'chặn quảng cáo'))) {
                        $hasFeature = true;
                        break;
                    }
                    if ($label === 'Streaming' && (str_contains($dbFLower, 'stream') || str_contains($dbFLower, 'netflix') || str_contains($dbFLower, 'youtube'))) {
                        $hasFeature = true;
                        break;
                    }
                    if ($label === 'Thiết bị không giới hạn' && (str_contains($dbFLower, 'không giới hạn') || str_contains($dbFLower, 'unlimited') || $first->devices === -1 || $first->devices === '-1')) {
                        $hasFeature = true;
                        break;
                    }
                    
                    if (str_contains($dbFLower, $labelLower) || str_contains($labelLower, $dbFLower)) {
                        $hasFeature = true;
                        break;
                    }
                }
                $booleanFeatures[] = $hasFeature;
            }
            
            $vpns[] = [
                'name'      => $brandName,
                'slug'      => $first->slug,
                'color'     => $first->color ?: '#4687FF',
                'featured'  => ($first->slug === 'nordvpn'),
                'prices'    => $prices,
                'old'       => $oldPrices,
                'devices'   => $first->devices,
                'servers'   => $first->servers ?: 'N/A',
                'countries' => $first->countries ?: 'N/A',
                'refund'    => str_replace([' ngày', 'ngày'], '', $first->refund) ?: '30',
                'features'  => $booleanFeatures,
                'image_path'=> $first->image_path,
            ];
        }
        @endphp

        <div class="row g-4" id="pricingGrid">
            @foreach($vpns as $vpn)
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card {{ !empty($vpn['featured']) ? 'featured' : '' }}">
                    <div class="product-card-img mb-4" style="border-radius: 12px; height: 180px;">
                        @if(!empty($vpn['image_path']))
                            <img src="{{ asset($vpn['image_path']) }}" alt="{{ $vpn['name'] }}">
                        @else
                            <div class="product-brand-logo" style="background:linear-gradient(135deg,{{ $vpn['color'] }},{{ $vpn['color'] }}99)">
                                <i class="bi bi-shield-lock-fill"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="fw-800 mb-1 font-poppins" style="font-size:20px;color:var(--gray-900)">{{ $vpn['name'] }}</h3>
                    <div style="font-size:12.5px;color:var(--gray-400)" class="mb-3">
                        {{ $vpn['servers'] }} máy chủ · {{ $vpn['countries'] }} quốc gia
                    </div>

                    @foreach($periods as $period)
                    <div class="period-price" data-period="{{ $period }}" style="{{ $period === '1year' ? '' : 'display:none' }}">
                        <div class="pricing-period">
                            {{ ['1month'=>'1 Tháng','6month'=>'6 Tháng','1year'=>'1 Năm','2year'=>'2 Năm'][$period] }}
                        </div>
                        @if(isset($vpn['prices'][$period]))
                        <div class="pricing-price mb-1">
                            <sup>đ</sup>{{ number_format($vpn['prices'][$period]) }}
                        </div>
                        @if(isset($vpn['old'][$period]))
                        <div style="font-size:12px;color:var(--gray-400);text-decoration:line-through;margin-bottom:4px">
                            {{ number_format($vpn['old'][$period]) }}đ
                        </div>
                        <div style="font-size:12px;color:var(--success);font-weight:700">
                            Tiết kiệm {{ round(($vpn['old'][$period]-$vpn['prices'][$period])/$vpn['old'][$period]*100) }}%
                        </div>
                        @endif
                        @else
                        <div class="pricing-price mb-1" style="font-size:18px;color:var(--gray-400);padding:10px 0">
                            Không hỗ trợ
                        </div>
                        @endif
                    </div>
                    @endforeach

                    <hr class="my-3" style="border-color:var(--gray-100)">

                    <ul class="pricing-features">
                        <li>
                            <i class="bi bi-check-circle-fill" style="color:var(--success)"></i>
                            {{ $vpn['devices'] == -1 || $vpn['devices'] === 'Không giới hạn' ? 'Không giới hạn thiết bị' : $vpn['devices'].' thiết bị đồng thời' }}
                        </li>
                        @foreach($featureLabels as $fi => $flabel)
                        <li>
                            <i class="bi {{ $vpn['features'][$fi] ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"
                               style="color:{{ $vpn['features'][$fi] ? 'var(--success)' : 'var(--gray-300)' }}"></i>
                            <span style="{{ !$vpn['features'][$fi] ? 'color:var(--gray-300)' : '' }}">{{ $flabel }}</span>
                        </li>
                        @endforeach
                        <li>
                            <i class="bi bi-check-circle-fill" style="color:var(--success)"></i>
                            Hoàn tiền {{ $vpn['refund'] }} ngày
                        </li>
                    </ul>

                    <a href="{{ route('product.detail', $vpn['slug']) }}" class="btn w-100 fw-700 py-2 mt-2 rounded-pill"
                       style="background:{{ !empty($vpn['featured']) ? 'linear-gradient(135deg,'.$vpn['color'].','.$vpn['color'].'99)' : '#fff' }};color:{{ !empty($vpn['featured']) ? '#fff' : $vpn['color'] }};border:2px solid {{ $vpn['color'] }};font-size:14px">
                        Mua {{ $vpn['name'] }}
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Comparison Table -->
<section class="section-sm bg-white">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">So Sánh Chi Tiết</h2>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" style="font-size:14px">
                <thead>
                    <tr style="background:var(--gray-50)">
                        <th class="fw-700" style="color:var(--gray-800);padding:14px 16px">Tính Năng</th>
                        @foreach($vpns as $vpn)
                        <th class="text-center fw-700" style="color:{{ $vpn['color'] }};padding:14px 12px">{{ $vpn['name'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-600" style="color:var(--gray-600)">Số máy chủ</td>
                        @foreach($vpns as $vpn)<td class="text-center fw-600">{{ $vpn['servers'] }}</td>@endforeach
                    </tr>
                    <tr>
                        <td class="fw-600" style="color:var(--gray-600)">Số quốc gia</td>
                        @foreach($vpns as $vpn)<td class="text-center fw-600">{{ $vpn['countries'] }}</td>@endforeach
                    </tr>
                    <tr>
                        <td class="fw-600" style="color:var(--gray-600)">Thiết bị đồng thời</td>
                        @foreach($vpns as $vpn)<td class="text-center fw-600">{{ $vpn['devices'] == -1 || $vpn['devices'] === 'Không giới hạn' ? '∞' : $vpn['devices'] }}</td>@endforeach
                    </tr>
                    <tr>
                        <td class="fw-600" style="color:var(--gray-600)">Hoàn tiền</td>
                        @foreach($vpns as $vpn)<td class="text-center fw-600">{{ $vpn['refund'] }} ngày</td>@endforeach
                    </tr>
                    @foreach($featureLabels as $fi => $flabel)
                    <tr>
                        <td class="fw-600" style="color:var(--gray-600)">{{ $flabel }}</td>
                        @foreach($vpns as $vpn)
                        <td class="text-center">
                            @if($vpn['features'][$fi])
                            <i class="bi bi-check-circle-fill text-success" style="font-size:16px"></i>
                            @else
                            <i class="bi bi-x-circle-fill" style="font-size:16px;color:var(--gray-300)"></i>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    <tr style="background:var(--gray-50)">
                        <td class="fw-700" style="color:var(--gray-800)">Giá 1 Năm</td>
                        @foreach($vpns as $vpn)
                        <td class="text-center">
                            <div class="fw-800 text-primary font-poppins" style="font-size:15px">
                                {{ isset($vpn['prices']['1year']) ? number_format($vpn['prices']['1year']) . 'đ' : 'N/A' }}
                            </div>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td></td>
                        @foreach($vpns as $vpn)
                        <td class="text-center py-3">
                            <a href="{{ route('product.detail', $vpn['slug']) }}" class="btn btn-sm rounded-pill fw-600 px-3" style="background:{{ $vpn['color'] }}15;color:{{ $vpn['color'] }};border:1.5px solid {{ $vpn['color'] }}40">
                                Xem Chi Tiết
                            </a>
                        </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
@endif

@endsection

@section('extra_js')
<script>
document.querySelectorAll('input[name="period"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const period = this.value;
        document.querySelectorAll('.period-price').forEach(el => {
            el.style.display = el.dataset.period === period ? '' : 'none';
        });
    });
});
</script>
@endsection
