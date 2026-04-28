<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    public function run(): void
    {

        User::query()->updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'username'   => 'main_manager',
                'name'       => 'Main Manager',
                'email'      => 'manager@example.com',
                'password'   => '12345678',
                'role'       => 'Manager',
                'status'     => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
