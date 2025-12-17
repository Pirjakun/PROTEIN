<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('slides')->truncate();

        $slides = [
            ['image' => '1.png', 'title' => 'Banner 1'],
            ['image' => '2.png', 'title' => 'Banner 2'],
            ['image' => '3.png', 'title' => 'Banner 3'],
        ];

        DB::table('slides')->insert($slides);
    }
}
