<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Panel;
use App\Models\Position;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'AdminUser',
            'student_id' => 'ADMIN2024', // Add a student ID
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
        ]);

        // Create Panel for 2024
        $panel2024 = Panel::create([
            'name' => '2024',
            'description' => 'Panel for the year 2024',
            'is_current' => true,
        ]);

        // Add Admin as President Level 1
        Position::create([
            'user_id' => $admin->id,
            'panel_id' => $panel2024->id,
            'position' => 'President',
            'level' => 1,
        ]);
    }
}
