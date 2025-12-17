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
                'name' => 'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt',
                'description' => "LIMITED STOCK\n\nDouble Layered, Oversized Long Sleeve\n\nMaterial:\n\n- Premium Cotton Combed 24s\n\n- Sablon Plastisol",
                'price' => 145000,
                'stock' => 10,
                'image' => 'BUITENWORKS - Vol.3 Nullism Black Long sleeve T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol.3 Nullism Black Long sleeve T-Shirt/2.png',
                    'BUITENWORKS - Vol.3 Nullism Black Long sleeve T-Shirt/3.png',
                    'BUITENWORKS - Vol.3 Nullism Black Long sleeve T-Shirt/4.png',
                    'BUITENWORKS - Vol.3 Nullism Black Long sleeve T-Shirt/5.png',
                    'BUITENWORKS - Vol.3 Nullism Black Long sleeve T-Shirt/6.png',
                ],
                'category' => 'longsleeve',
                'shopee_link' => 'https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.42816990879?extraParams=%7B%22display_model_id%22%3A301402424922%7D'
            ],
            [
                'name' => 'BUITENWORKS - Vol.3 Nullism Grey Long sleeve T-Shirt',
                'description' => "LIMITED STOCK\n\nDouble Layered, Oversized Long Sleeve\n\nMaterial:\n\n- Premium Cotton Combed 24s\n\n- Sablon Plastisol",
                'price' => 150000,
                'stock' => 8,
                'image' => 'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/2.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/3.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/4.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/5.png',
                    'BUITENWORKS - Vol.3 Nullism Long sleeve T-Shirt/6.png',
                ],
                'category' => 'longsleeve',
                'shopee_link' => 'https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.40166988322?extraParams=%7B%22display_model_id%22%3A261403389524%7D'
            ],
            [
                'name' => 'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt',
                'description' => "LIMITED STOCK\n\nDouble Layered, Oversized Long Sleeve\n\nMaterial:\n\n- Premium Cotton Combed 20s\n\n- Sablon Plastisol",
                'price' => 150000,
                'stock' => 4,
                'image' => 'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/2.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/3.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/4.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/5.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/6.png',
                    'BUITENWORKS - Vol.1 Stargaze Oversized Boxy T-Shirt/7.png',
                ],
                'category' => 'short sleeve',
                'shopee_link' => 'https://shopee.co.id/BUITENWORKS-Vol.1-Stargaze-Oversized-Boxy-T-Shirt-i.1450606504.26874922331?extraParams=%7B%22display_model_id%22%3A242569419158%7D'
            ],
            [
                'name' => 'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt',
                'description' => "LIMITED STOCK\n\nBoxy Fit\n\nMaterial :\n\n- Premium Cotton Combed 20s\n\n- Sablon Puff + Plastisol",
                'price' => 170000,
                'stock' => 7,
                'image' => 'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/2.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/3.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/4.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/5.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/6.png',
                    'BUITENWORKS - Vol. 2 Liquera WHITE Boxy T-Shirt/7.png',
                ],
                'category' => 'short sleeve',
                'shopee_link' => 'https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-WHITE-Boxy-T-Shirt-i.1450606504.26681640067?extraParams=%7B%22display_model_id%22%3A248054076794%7D'
            ],
            [
                'name' => 'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt',
                'description' => "LIMITED STOCK\n\nBoxy Fit\n\nMaterial :\n\n- Premium Cotton Combed 20s\n\n- Sablon Puff + Plastisol",
                'price' => 170000,
                'stock' => 4,
                'image' => 'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/1.png',
                'gallery' => [
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/2.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/3.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/4.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/5.png',
                    'BUITENWORKS - Vol. 2 Liquera BLACK Boxy T-Shirt/6.png',
                ],
                'category' => 'short sleeve',
                'shopee_link' => 'https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-BLACK-Boxy-T-Shirt-i.1450606504.22190736060?extraParams=%7B%22display_model_id%22%3A198375737907%7D'
            ]
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
