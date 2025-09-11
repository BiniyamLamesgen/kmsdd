<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'System Administrator',
                'email' => 'admin@system.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Super User',
                'email' => 'superuser@company.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create($userData);
            
            // Assign roles with web guard
            if ($user->email === 'admin@system.com') {
                $user->assignRole('Super Admin');
            } elseif ($user->email === 'superuser@company.com') {
                $user->assignRole('Super Admin');
            } else {
                $user->assignRole('Manager');
            }
        }
    }
}
