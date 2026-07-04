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
        <span class="section-label mb-3 d-inline-block">💰 Giá Tốt Nhất Thị Trường</span>
        <h1 class="section-title mb-2">Bảng Giá VPN Chi Tiết</h1>
        <p class="section-subtitle mx-auto">So sánh các gói VPN để chọn gói phù hợp nhất với nhu cầu của bạn</p>
    </div>
</div>

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
        $vpns = [
            ['name'=>'NordVPN','slug'=>'nordvpn','color'=>'#4687FF','featured'=>true,
             'prices'=>['1month'=>120000,'6month'=>350000,'1year'=>599000,'2year'=>849000],
             'old'   =>['1month'=>200000,'6month'=>700000,'1year'=>1200000,'2year'=>2400000],
             'devices'=>6,'servers'=>'5,400+','countries'=>'60+','refund'=>30,
             'features'=>[true,true,true,true,true,true,false]],
            ['name'=>'ExpressVPN','slug'=>'expressvpn','color'=>'#DA3940',
             'prices'=>['1month'=>160000,'6month'=>450000,'1year'=>799000,'2year'=>1099000],
             'old'   =>['1month'=>260000,'6month'=>850000,'1year'=>1500000,'2year'=>2800000],
             'devices'=>5,'servers'=>'3,000+','countries'=>'94+','refund'=>30,
             'features'=>[true,true,true,true,true,true,false]],
            ['name'=>'Surfshark','slug'=>'surfshark','color'=>'#10B981',
             'prices'=>['1month'=>89000,'6month'=>279000,'1year'=>449000,'2year'=>699000],
             'old'   =>['1month'=>180000,'6month'=>600000,'1year'=>1100000,'2year'=>1800000],
             'devices'=>-1,'servers'=>'3,200+','countries'=>'100+','refund'=>30,
             'features'=>[true,true,true,false,true,true,true]],
            ['name'=>'HMA VPN','slug'=>'hma','color'=>'#F59E0B',
             'prices'=>['1month'=>79000,'6month'=>230000,'1year'=>449000,'2year'=>649000],
             'old'   =>['1month'=>150000,'6month'=>450000,'1year'=>900000,'2year'=>1800000],
             'devices'=>5,'servers'=>'1,100+','countries'=>'190+','refund'=>30,
             'features'=>[true,false,true,false,true,false,false]],
            ['name'=>'CyberGhost','slug'=>'cyberghost','color'=>'#8B5CF6',
             'prices'=>['1month'=>99000,'6month'=>250000,'1year'=>299000,'2year'=>399000],
             'old'   =>['1month'=>190000,'6month'=>550000,'1year'=>700000,'2year'=>1100000],
             'devices'=>7,'servers'=>'9,700+','countries'=>'91+','refund'=>45,
             'features'=>[true,false,true,false,false,true,false]],
            ['name'=>'ProtonVPN','slug'=>'protonvpn','color'=>'#6D28D9',
             'prices'=>['1month'=>110000,'6month'=>320000,'1year'=>549000,'2year'=>799000],
             'old'   =>['1month'=>200000,'6month'=>650000,'1year'=>1000000,'2year'=>2000000],
             'devices'=>10,'servers'=>'3,000+','countries'=>'67+','refund'=>30,
             'features'=>[true,true,true,true,false,false,false]],
        ];
        $featureLabels = ['Không lưu log','Kill Switch','P2P/Torrent','Multi-hop','Ad Blocker','Streaming','Thiết bị không giới hạn'];
        $periods = ['1month','6month','1year','2year'];
        @endphp

        <div class="row g-4" id="pricingGrid">
            @foreach($vpns as $vpn)
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card {{ !empty($vpn['featured']) ? 'featured' : '' }}">
                    <div class="pricing-brand-icon" style="background:linear-gradient(135deg,{{ $vpn['color'] }},{{ $vpn['color'] }}99)">
                        <i class="bi bi-shield-lock-fill text-white" style="font-size:28px"></i>
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
                    </div>
                    @endforeach

                    <hr class="my-3" style="border-color:var(--gray-100)">

                    <ul class="pricing-features">
                        <li>
                            <i class="bi bi-check-circle-fill" style="color:var(--success)"></i>
                            {{ $vpn['devices'] === -1 ? 'Không giới hạn thiết bị' : $vpn['devices'].' thiết bị đồng thời' }}
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
                        @foreach($vpns as $vpn)<td class="text-center fw-600">{{ $vpn['devices'] === -1 ? '∞' : $vpn['devices'] }}</td>@endforeach
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
                            <div class="fw-800 text-primary font-poppins" style="font-size:15px">{{ number_format($vpn['prices']['1year']) }}đ</div>
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
