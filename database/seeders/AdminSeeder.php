<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'       => 'System Admin',
                'password'   => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'role'       => 'admin',
            ]
        );
    }
}
