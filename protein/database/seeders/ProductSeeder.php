<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Product::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $products = [
            [
                'name' => 'BUITENWORKS Vol.3 Nullism Long-sleeve T-Shirt',
                'description' => 'Black Long-sleeve with graphic print.',
                'price' => 145000,
                'stock' => 50,
                'image' => 'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/2.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/3.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/4.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/5.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/6.png',
                ],
                'category' => 'longsleeve'
            ],
            [
                'name' => 'BUITENWORKS Vol.2 Nullism Long-sleeve T-Shirt',
                'description' => 'Variant design of the Nullism series.',
                'price' => 150000,
                'stock' => 50,
                'image' => 'BUITENWORKS - Vol.2 Nullism Long sleeve T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol.2 Nullism Long sleeve T-Shirt/2.png',
                    'BUITENWORKS - Vol.2 Nullism Long sleeve T-Shirt/3.png',
                    'BUITENWORKS - Vol.2 Nullism Long sleeve T-Shirt/4.png',
                    'BUITENWORKS - Vol.2 Nullism Long sleeve T-Shirt/5.png',
                    'BUITENWORKS - Vol.2 Nullism Long sleeve T-Shirt/6.png',
                ],
                'category' => 'longsleeve'
            ],
            [
                'name' => 'BUITENWORKS Vol.1 Stargaze Oversized Boxy T-Shirt',
                'description' => 'Oversized fit with stargaze graphic.',
                'price' => 150000,
                'stock' => 50,
                'image' => 'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/2.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/3.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/4.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/5.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/6.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/7.png',
                ],
                'category' => 'boxy'
            ],
            [
                'name' => 'BUITENWORKS Vol.2 Liquera WHITE Boxy T-Shirt',
                'description' => "LIMITED STOCK\n\nBoxy Fit\n\nMaterial :\n\n- Premium Cotton Combed 20s\n\n- Sablon Puff + Plastisol\n\n\nSIZE CHART (Chest Width x Length x Shoulder Width x Sleeve)\n\nS = 58 x 61 x 56 x 20\n\nM = 60 x 63 x 58 x 21\n\nL = 62 x 65 x 60 x 21\n\nXL = 64 x 67 x 62 x 22\n\n\n---PENGIRIMAN SENIN s/d SABTU 10:00-16:00 WIB---",
                'price' => 170000,
                'stock' => 50,
                'image' => 'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/2.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/3.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/4.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/5.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/6.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/7.png',
                ],
                'category' => 'boxy'
            ],
            [
                'name' => 'BUITENWORKS Vol.2 Liquera BLACK Boxy T-Shirt',
                'description' => 'Black boxy tee from Liquera collection.',
                'price' => 170000,
                'stock' => 50,
                'image' => 'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/2.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/3.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/4.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/5.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/6.png',
                ],
                'category' => 'boxy'
            ]
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
