<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CommunitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('communities')->insert([
            ['image' => '1.png', 'created_at' => now(), 'updated_at' => now()],
            ['image' => '2.png', 'created_at' => now(), 'updated_at' => now()],
            ['image' => '3.png', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
