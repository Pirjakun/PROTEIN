<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('shopee_link')->nullable()->after('image');
        });

        // Seed existing products with Shopee links from scripts.js
        $links = [
            1 => 'https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.42816990879?extraParams=%7B%22display_model_id%22%3A301402424922%7D',
            2 => 'https://shopee.co.id/BUITENWORKS-Vol.3-Nullism-Long-sleeve-T-Shirt-i.1450606504.40166988322?extraParams=%7B%22display_model_id%22%3A261403389524%7D',
            3 => 'https://shopee.co.id/BUITENWORKS-Vol.1-Stargaze-Oversized-Boxy-T-Shirt-i.1450606504.26874922331?extraParams=%7B%22display_model_id%22%3A242569419158%7D',
            4 => 'https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-WHITE-Boxy-T-Shirt-i.1450606504.26681640067?extraParams=%7B%22display_model_id%22%3A248054076794%7D',
            5 => 'https://shopee.co.id/BUITENWORKS-Vol.-2-Liquera-BLACK-Boxy-T-Shirt-i.1450606504.22190736060?extraParams=%7B%22display_model_id%22%3A198375737907%7D',
        ];

        foreach ($links as $id => $link) {
            DB::table('products')->where('id', $id)->update(['shopee_link' => $link]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('shopee_link');
        });
    }
};
