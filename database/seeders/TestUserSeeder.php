<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'fullname' => 'Admin User',
            'email' => 'admin@example.com',
            'studentid' => 'ADMIN001',
            'courseSection' => 'Administration',
            'phone_number' => '1234567890',
            'gender' => 'Male',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Create regular user
        User::create([
            'fullname' => 'Student User',
            'email' => 'student@example.com',
            'studentid' => 'STU001',
            'courseSection' => 'Computer Science',
            'phone_number' => '0987654321',
            'gender' => 'Female',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->command->info('Test users created successfully!');
        $this->command->info('Admin: admin@example.com / password123');
        $this->command->info('Student: student@example.com / password123');
    }
}
