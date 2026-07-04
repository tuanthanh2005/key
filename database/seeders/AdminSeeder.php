<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo tài khoản admin mặc định
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@vpnstore.vn')],
            [
                'name'     => 'VPNStore Admin',
                'email'    => env('ADMIN_EMAIL', 'admin@vpnstore.vn'),
                'password' => Hash::make(env('ADMIN_PASSWORD', 'Admin@123456')),
                'role'     => 'admin',
                'status'   => 'active',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin account created:');
        $this->command->info('   Email:    ' . env('ADMIN_EMAIL', 'admin@vpnstore.vn'));
        $this->command->info('   Password: ' . env('ADMIN_PASSWORD', 'Admin@123456'));
        $this->command->info('   URL:      http://127.0.0.1:8000/admin');
    }
}
