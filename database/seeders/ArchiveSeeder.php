<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArchiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('archives')->insert([
            [
                'title' => 'Sample Archive 1',
                'description' => 'This is a sample archive restored after refresh.',
                'image' => 'assets/archives/1.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Sample Archive 2',
                'description' => 'Another sample archive.',
                'image' => 'assets/archives/2.png',
                'link' => '#',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
