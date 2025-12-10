<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Check if admin already exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            
            User::create([
                'name' => 'System Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('Admin@123'), 
                'role' => 'admin',
            ]);
        }
    }
}
