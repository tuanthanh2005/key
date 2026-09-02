<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use App\Models\Category;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $allBrands = [
            'ChatGPT', 'Xbox', 'Notion', 'Claude', 'TikTok', 'Netflix', 'Cursor', 'Spotify',
            'Google', 'Grok AI', 'Canva Pro', 'Google One', 'ElevenLabs', 'Duolingo', 'YouTube Premium',
            'DeepSeek', 'Gmail', 'Perplexity', 'Lovable', 'Runway', 'Higgfield', 'Akool',
            'CapCut Pro', 'Kling AI', 'ExpressVPN', 'TradingView', 'Seedance', 'HMA VPN', 'MiniMax',
            'Leonardo AI', 'HeyGen', 'Freepik', 'ProtonVPN', 'OpenArt', 'Kaspersky', 'Krea AI',
            'LinkedIn', 'Magica', 'Facebook', 'NordVPN', 'GenSpark', 'Xingtu', 'Figma',
            'Meitu', 'Replit', 'Wink', 'Scribd', 'Discord', 'Decor Discord', 'Surfshark',
            'Gamma', 'Tele Tool', 'iCloud', 'Qwen', 'Key GPM', 'Apple', 'Locket',
            'Zoom', 'Adobe', 'AutoDesk', 'Mitte', 'Cuty', 'Microsoft Office', 'Outlook',
            'Quizlet', 'AWS (Amazon Web Services)', 'Grammarly', 'TV 360', 'VPN', 'Galaxy Play',
            'Skillshare', 'OnlyFans', 'Antigravity', 'X'
        ];

        foreach ($allBrands as $brand) {
            $slug = Str::slug($brand);
            $name = trim($brand);

            $seoTitle = 'Tài Khoản ' . $name . ' Giá Rẻ - Bảo Hành Trọn Gói';
            $seoDesc = 'Mua tài khoản ' . $name . ' giá rẻ, chính hãng tại vpnstore.pro. Giao tài khoản tự động 24/7, bảo hành uy tín trọn gói 1 đổi 1.';

            $cat = Category::where('slug', $slug)->first();
            if (!$cat) {
                Category::create([
                    'name' => $name,
                    'slug' => $slug,
                    'type' => str_contains(strtolower($name), 'vpn') || str_contains(strtolower($name), 'proxy') ? 'vpn' : 'software',
                    'seo_title' => $seoTitle,
                    'seo_description' => $seoDesc,
                ]);
            } else {
                $cat->seo_title = $seoTitle;
                $cat->seo_description = $seoDesc;
                $cat->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
