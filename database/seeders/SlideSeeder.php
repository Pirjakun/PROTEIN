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
        $slides = [
            ['image' => '5.png', 'title' => 'Banner 1'],
            ['image' => '6.png', 'title' => 'Banner 2'],
            ['image' => '7.png', 'title' => 'Banner 3'],
        ];

        DB::table('slides')->insert($slides);
    }
}
