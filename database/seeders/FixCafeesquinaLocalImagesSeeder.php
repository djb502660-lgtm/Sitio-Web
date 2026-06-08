<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Restaura rutas locales de imágenes (evita dependencia de URLs externas tipo Unsplash).
 */
class FixCafeesquinaLocalImagesSeeder extends Seeder
{
    public function run(): void
    {
        $productImages = [
            1 => 'uploads/products/espresso.png',
            2 => 'uploads/products/capuchino.png',
            3 => 'uploads/products/latte.png',
            4 => 'uploads/products/frappe.png',
            5 => 'uploads/products/cheesecake.png',
            6 => 'uploads/products/desayuno.png',
            7 => 'uploads/products/affogato.png',
        ];

        foreach ($productImages as $id => $image) {
            DB::table('products')->where('id', $id)->update(['image' => $image]);
        }

        DB::table('promotions')->where('id', 1)->update(['image' => 'uploads/products/desayuno.png']);
        DB::table('promotions')->where('id', 2)->update(['image' => 'uploads/products/frappe.png']);
    }
}
