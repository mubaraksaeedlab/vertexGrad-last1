<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // // مستخدم طالب
        User::create([
            'username' => 'student1',
            'name' => 'Student One',
            'email' => 'student1@example.com',
            'password' => 'Student123',
            'role' => 'Student',
            'status' => 'active',
            'profile_image' => null,
            'email_verified_at' => now(),
        ]);

        // // مستخدم مشرف
        User::create([
            'username' => 'supervisor1',
            'name' => 'Supervisor One',
            'email' => 'supervisor1@example.com',
            'password' => 'Supervisor123',
            'role' => 'Supervisor',
            'status' => 'active',
            'profile_image' => null,
            'email_verified_at' => now(),
        ]);

        // // مستخدم مستثمر
        User::create([
            'username' => 'investor1',
            'name' => 'Investor One',
            'email' => 'investor1@example.com',
            'password' => 'Investor123',
            'role' => 'Investor',
            'status' => 'active',
            'profile_image' => null,
            'email_verified_at' => now(),
        ]);

        // يمكن إضافة مستخدمين آخرين بنفس الطريقة
    }
}
