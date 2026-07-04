<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('email'); // user | admin
            $table->string('google_id')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('google_id');
            $table->string('phone')->nullable()->after('avatar');
            $table->enum('status', ['active', 'banned'])->default('active')->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'google_id', 'avatar', 'phone', 'status']);
        });
    }
};
