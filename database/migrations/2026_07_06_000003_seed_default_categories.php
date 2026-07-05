<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\Category;
use App\Models\Product;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = [
            ['name' => 'NordVPN', 'slug' => 'nordvpn', 'seo_title' => 'Đăng Ký Tài Khoản NordVPN Premium Bản Quyền', 'seo_description' => 'Mua tài khoản NordVPN chính hãng tại VPNStore với giá tốt nhất thị trường.'],
            ['name' => 'ExpressVPN', 'slug' => 'expressvpn', 'seo_title' => 'Mua Tài Khoản ExpressVPN Chính Hãng Giá Tốt', 'seo_description' => 'Cung cấp các gói ExpressVPN giá tốt nhất thị trường, cam kết kích hoạt nhanh.'],
            ['name' => 'Surfshark', 'slug' => 'surfshark', 'seo_title' => 'Dịch Vụ Surfshark VPN Bản Quyền Giá Rẻ', 'seo_description' => 'Đăng ký tài khoản Surfshark VPN bản quyền chính hãng giá tốt bảo hành uy tín.'],
            ['name' => 'HMA VPN', 'slug' => 'hma', 'seo_title' => 'Mua Key HMA VPN Bản Quyền Kích Hoạt Nhanh', 'seo_description' => 'Key HMA VPN bản quyền giá tốt nhất Việt Nam, hỗ trợ kích hoạt trọn đời.'],
            ['name' => 'CyberGhost', 'slug' => 'cyberghost', 'seo_title' => 'Tài Khoản CyberGhost VPN Giá Rẻ Uy Tín', 'seo_description' => 'Dịch vụ cung cấp tài khoản CyberGhost VPN uy tín hàng đầu.'],
            ['name' => 'PureVPN', 'slug' => 'purevpn', 'seo_title' => 'Mua Tài Khoản PureVPN Bản Quyền Giá Tốt', 'seo_description' => 'Tài khoản PureVPN bản quyền giá tốt cam kết 1 đổi 1.'],
            ['name' => 'IPVanish', 'slug' => 'ipvanish', 'seo_title' => 'Đăng Ký IPVanish VPN Chính Hãng Giá Rẻ', 'seo_description' => 'Cung cấp tài khoản IPVanish VPN chính hãng giá rẻ, bảo hành trọn đời.'],
            ['name' => 'ProtonVPN', 'slug' => 'protonvpn', 'seo_title' => 'Mua Tài Khoản ProtonVPN Giá Rẻ Bảo Hành 1 Đổi 1', 'seo_description' => 'Tài khoản ProtonVPN bảo mật cao hàng chính hãng.'],
        ];

        foreach ($defaults as $d) {
            $cat = Category::updateOrCreate(['slug' => $d['slug']], [
                'name' => $d['name'],
                'seo_title' => $d['seo_title'],
                'seo_description' => $d['seo_description']
            ]);
            
            // Link existing products to this category
            Product::where('slug', $d['slug'])->update(['category_id' => $cat->id]);
        }
    }

    public function down(): void
    {
        // No rollback needed for data seeding
    }
};
