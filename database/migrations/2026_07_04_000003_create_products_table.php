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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('brand');
            $table->string('slug');
            $table->string('color')->default('#4687FF');
            $table->decimal('price', 12, 2);
            $table->decimal('old_price', 12, 2)->nullable();
            $table->string('plan'); // 1month, 6month, 1year, 2year, 3year
            $table->double('rating', 3, 1)->default(4.8);
            $table->integer('reviews')->default(120);
            $table->text('features')->nullable(); // json or text features
            $table->integer('stock')->default(999);
            $table->integer('sold')->default(0);
            $table->string('status')->default('active'); // active, inactive
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
