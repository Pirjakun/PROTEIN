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
                'title' => 'LANDAK DILAN! (jualan dadakan di jalan)',
                'description' => 'heya, buitenfolks! kita bakal jualan offline setiap Sabtu/Minggu pagi. lokasi ada di slide, tapi bisa berubah-ubah seiring waktu - so, stay updated di stories kita!',
                'image' => 'assets/archives/1.jpg',
                'link' => '#https://www.instagram.com/p/DJRmg8mTVv7/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'LIQUERA',
                'description' => 'liquifaction in progress. 🪐✨',
                'image' => 'assets/archives/2.jpg',
                'link' => '#https://www.instagram.com/p/DHvGRLzTL_r/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'STARGAZE',
                'description' => 'COMING SOON “out in a few hours.” ⭐️💫🌠',
                'image' => 'assets/archives/3.jpg',
                'link' => '#https://www.instagram.com/p/DFiKGnVT9k9/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
