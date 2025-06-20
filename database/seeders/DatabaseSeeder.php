<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'name' => 'Admin Sriwilai',
            'email' => 'admin@admin.com',
            'role' => 'admin',
            'password' => Hash::make('admin11049'), // เข้ารหัสรหัสผ่าน
        ]);
    }
}
