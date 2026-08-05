<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index(['status', 'show_in_list'], 'products_listing_index');
            $table->index(['category_id', 'status'], 'products_category_status_index');
            $table->index(['slug', 'status'], 'products_slug_status_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['brand', 'review_rating', 'updated_at'], 'orders_brand_reviews_index');
            $table->index(['payment_status', 'created_at'], 'orders_payment_created_index');
            $table->index(['order_status', 'created_at'], 'orders_status_created_index');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->index(['user_id', 'active', 'expires_at'], 'coupons_user_valid_index');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_listing_index');
            $table->dropIndex('products_category_status_index');
            $table->dropIndex('products_slug_status_index');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_brand_reviews_index');
            $table->dropIndex('orders_payment_created_index');
            $table->dropIndex('orders_status_created_index');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex('coupons_user_valid_index');
        });
    }
};
