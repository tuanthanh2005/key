<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $newSettings = [
            // Thanh toán / Payment
            'bank_code'               => 'OCB',
            'bank_account_number'     => '0772698113',
            'bank_account_name'       => 'TRAN THANH TUAN',
            'bank_bin'                => '970212',

            // Liên hệ bổ sung
            'zalo_support_2'          => '0569012134',
            'zalo_url_1'              => 'https://zalo.me/0708910952',
            'zalo_url_2'              => 'https://zalo.me/0569012134',
            'telegram_url'            => 'https://t.me/specademy',

            // Giao diện / UI text
            'topbar_text'             => 'Bảo hành 30 ngày · Hỗ trợ 24/7 · Thanh toán an toàn',
            'footer_copyright'        => 'VPNStore. Tất cả quyền được bảo lưu.',
            'meta_description'        => 'VPNStore - Chuyên cung cấp VPN chính hãng: NordVPN, ExpressVPN, Surfshark, HMA với giá tốt nhất. Bảo hành 30 ngày, hỗ trợ 24/7.',

            // Khuyến mãi / Discount
            'auto_discount_threshold' => '500000',
            'auto_discount_rate'      => '5',

            // Dashboard
            'dashboard_max_days'           => '60',
            'dashboard_orders_per_page'    => '10',
            'dashboard_recent_users_count' => '5',
            'dashboard_top_products_count' => '5',

            // Sản phẩm
            'default_product_features' => 'Mã bản quyền chính hãng,Bảo mật tuyệt đối,Hỗ trợ 24/7',
            'default_product_rating'   => '4.8',
            'default_product_reviews'  => '120',

            // Hành vi
            'show_fake_reviews'        => '1',
        ];

        foreach ($newSettings as $key => $value) {
            // Chỉ insert nếu chưa tồn tại
            DB::table('settings')->insertOrIgnore([
                'key'        => $key,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $keys = [
            'bank_code','bank_account_number','bank_account_name','bank_bin',
            'zalo_support_2','zalo_url_1','zalo_url_2','telegram_url',
            'topbar_text','footer_copyright','meta_description',
            'auto_discount_threshold','auto_discount_rate',
            'dashboard_max_days','dashboard_orders_per_page',
            'dashboard_recent_users_count','dashboard_top_products_count',
            'default_product_features','default_product_rating','default_product_reviews',
            'show_fake_reviews',
        ];
        DB::table('settings')->whereIn('key', $keys)->delete();
    }
};
