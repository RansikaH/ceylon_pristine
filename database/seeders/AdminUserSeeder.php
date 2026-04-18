<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        if (!User::where('email', 'admin@ceylonmoms.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@ceylonmoms.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@ceylonmoms.com');
            $this->command->info('Password: admin123');
        } else {
            // Update existing admin user to ensure it has the correct role
            User::where('email', 'admin@ceylonmoms.com')->update([
                'role' => 'admin',
                'password' => Hash::make('admin123'), // Reset password to default
            ]);
            $this->command->info('Admin user updated successfully!');
        }
    }
}
