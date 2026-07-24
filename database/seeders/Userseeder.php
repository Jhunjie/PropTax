<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed one admin account and one regular (taxpayer) account.
     *
     * Login credentials:
     *   Admin    — admin@example.com    / password
     *   Resident — resident@example.com / password
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_verified' => true,
                'status' => 'approved',
                'tin' => '000-000-000-000',
                'role' => 'admin',
            ]
        );

        User::updateOrCreate(
            ['email' => 'resident@example.com'],
            [
                'name' => 'Rosario Dela Cruz',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_verified' => true,
                'status' => 'approved',
                'tin' => '111-222-333-000',
                'role' => 'user',
            ]
        );
    }
}