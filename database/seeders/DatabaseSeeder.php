<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin and sample users for testing
        $this->call([
            AdminUserSeeder::class,
            InterviewSeeder::class,
        ]);
    }
}
