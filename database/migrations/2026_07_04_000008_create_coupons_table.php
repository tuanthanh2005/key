<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->decimal('discount_value', 10, 2); // % hoặc số tiền
            $table->unsignedBigInteger('min_order')->default(0); // Đơn hàng tối thiểu
            $table->unsignedInteger('max_uses')->nullable(); // null = unlimited
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Seed mã coupon mặc định
        $coupons = [
            [
                'code'           => 'VPNVN10',
                'discount_type'  => 'percent',
                'discount_value' => 10,
                'min_order'      => 0,
                'max_uses'       => null,
                'used_count'     => 0,
                'active'         => true,
                'expires_at'     => null,
                'description'    => 'Giảm 10% cho tất cả đơn hàng',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'VIP20',
                'discount_type'  => 'percent',
                'discount_value' => 20,
                'min_order'      => 0,
                'max_uses'       => null,
                'used_count'     => 0,
                'active'         => true,
                'expires_at'     => null,
                'description'    => 'Giảm 20% dành cho khách VIP',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'SALE15',
                'discount_type'  => 'percent',
                'discount_value' => 15,
                'min_order'      => 0,
                'max_uses'       => null,
                'used_count'     => 0,
                'active'         => true,
                'expires_at'     => null,
                'description'    => 'Giảm 15% khuyến mãi đặc biệt',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        DB::table('coupons')->insert($coupons);
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
