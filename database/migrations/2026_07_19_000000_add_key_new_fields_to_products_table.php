<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'type')) {
                $table->string('type')->default('account');
            }
            if (!Schema::hasColumn('products', 'original_price')) {
                $table->decimal('original_price', 12, 0)->nullable();
            }
            if (!Schema::hasColumn('products', 'duration')) {
                $table->string('duration')->nullable();
            }
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('products', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
            if (!Schema::hasColumn('products', 'sort_order')) {
                $table->integer('sort_order')->default(0);
            }
            if (!Schema::hasColumn('products', 'meta_title')) {
                $table->string('meta_title')->nullable();
            }
            if (!Schema::hasColumn('products', 'meta_description')) {
                $table->text('meta_description')->nullable();
            }
            if (!Schema::hasColumn('products', 'sold_count')) {
                $table->integer('sold_count')->default(0);
            }
            if (!Schema::hasColumn('products', 'review_count')) {
                $table->integer('review_count')->default(0);
            }
        });

        // Migrate existing product data
        $products = DB::table('products')->get();
        foreach ($products as $product) {
            DB::table('products')->where('id', $product->id)->update([
                'original_price' => $product->old_price,
                'duration'       => $product->plan,
                'image'          => str_replace('storage/', '', $product->image_path),
                'is_active'      => $product->status === 'active',
                'sold_count'     => $product->sold ?? 0,
                'review_count'   => $product->reviews ?? 0,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'original_price',
                'duration',
                'image',
                'is_active',
                'is_featured',
                'sort_order',
                'meta_title',
                'meta_description',
                'sold_count',
                'review_count'
            ]);
        });
    }
};
