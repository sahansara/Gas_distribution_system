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
        if (!User::where('email', 'staff@example.com')->exists()) {
            
            User::create([
                'name' => 'System Staff',
                'email' => 'staff@example.com',
                'password' => Hash::make('staff@123'), 
                'role' => 'admin',
            ]);
        }
    }
}
