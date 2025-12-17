<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@buitenworks.com'],
            [
                'name' => 'Admin Buitenworks',
                'password' => bcrypt('password'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
