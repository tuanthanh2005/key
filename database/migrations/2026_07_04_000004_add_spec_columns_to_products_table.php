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
        Schema::table('products', function (Blueprint $table) {
            $table->string('servers')->nullable();
            $table->string('countries')->nullable();
            $table->string('devices')->nullable();
            $table->string('speed')->nullable();
            $table->string('protocol')->nullable();
            $table->string('headquarter')->nullable();
            $table->string('founded')->nullable();
            $table->string('refund')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'servers',
                'countries',
                'devices',
                'speed',
                'protocol',
                'headquarter',
                'founded',
                'refund',
                'description'
            ]);
        });
    }
};
