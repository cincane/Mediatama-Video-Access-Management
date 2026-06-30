<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@mediatama.com'],
            [
                'name' => 'Admin Mediatama',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Customer user
        User::updateOrCreate(
            ['email' => 'customer@mediatama.com'],
            [
                'name' => 'Customer Mediatama',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );
    }
}
