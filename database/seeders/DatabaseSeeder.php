<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            ManagerSeeder::class,
            UsersSeeder::class,

        ]);

        // // ✅ FORCE manager password at the END (guarantee)
        // \App\Models\User::where('email', 'manager@example.com')
        //     ->update(['password' => Hash::make('12345678')]);
    }
}
