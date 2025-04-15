<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Admin user
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('123'), // dùng bcrypt
            'is_admin' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        // Normal user
        User::create([
            'username' => 'john_doe',
            'email' => 'john@example.com',
            'password' => Hash::make('123'),
            'provider' => null,
            'provider_id' => null,
            'google_id' => null,
            'is_admin' => false,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);
    }
}
