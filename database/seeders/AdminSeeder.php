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
        // Create admin account
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'       => 'System Admin',
                'password'   => Hash::make(env('ADMIN_PASSWORD', 'admin123')),
                'role'       => 'admin',
            ]
        );

        // Create test boardmember account
        User::updateOrCreate(
            ['email' => 'boardmember@test.com'],
            [
                'name'       => 'Test Boardmember',
                'password'   => Hash::make(env('BOARDMEMBER_PASSWORD', 'boardmember123')),
                'role'       => 'boardmember',
            ]
        );
    }
}
