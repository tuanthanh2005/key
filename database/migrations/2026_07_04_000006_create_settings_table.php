<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        $defaults = [
            'store_name' => 'VPNStore',
            'contact_email' => 'tetuongmmovn@gmail.com',
            'telegram_support' => '@specademy',
            'zalo_support' => '0708910952',
            'maintenance_mode' => '0',
            'sales_nordvpn' => '500+',
            'sales_expressvpn' => '300+',
            'sales_surfshark' => '200+',
            'sales_hma' => '100+',
            'sales_cyberghost' => '150+',
            'sales_purevpn' => '80+',
            'sales_ipvanish' => '50+',
            'sales_protonvpn' => '70+'
        ];

        foreach ($defaults as $k => $v) {
            \Illuminate\Support\Facades\DB::table('settings')->insert([
                'key' => $k,
                'value' => $v,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
