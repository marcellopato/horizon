<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user if it doesn't exist
        if (! User::where('email', 'admin@horizon.test')->exists()) {
            User::create([
                'name' => 'System Admin',
                'email' => 'admin@horizon.test',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
        }

        // Create sample reviewer user for testing
        if (! User::where('email', 'reviewer@horizon.test')->exists()) {
            User::create([
                'name' => 'Sample Reviewer',
                'email' => 'reviewer@horizon.test',
                'password' => Hash::make('reviewer123'),
                'role' => 'reviewer',
                'email_verified_at' => now(),
            ]);
        }

        // Create sample candidate user for testing
        if (! User::where('email', 'candidate@horizon.test')->exists()) {
            User::create([
                'name' => 'Sample Candidate',
                'email' => 'candidate@horizon.test',
                'password' => Hash::make('candidate123'),
                'role' => 'candidate',
                'email_verified_at' => now(),
            ]);
        }
    }
}
