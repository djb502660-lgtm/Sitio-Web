<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CafeesquinaSeeder extends Seeder
{
    public function run(): void
    {
        $adminHash = Hash::make('Admin123!');

        if (DB::table('users')->where('email', 'admin@cafeesquina.local')->doesntExist()) {
            DB::table('users')->insert([
                'username' => 'admin',
                'email' => 'admin@cafeesquina.local',
                'password' => $adminHash,
                'full_name' => 'Administrador',
                'role' => 'admin',
            ]);
        } else {
            DB::table('users')->where('email', 'admin@cafeesquina.local')->update([
                'password' => $adminHash,
                'role' => 'admin',
            ]);
        }

        $categories = [
            ['Cafés calientes', 'Espresso, americano, capuchino y más'],
            ['Cafés fríos', 'Cold brew, iced latte, nitro'],
            ['Frappés', 'Bebidas frías cremosas'],
            ['Tés', 'Tés artesanales e infusiones'],
            ['Chocolates', 'Chocolate caliente premium'],
            ['Postres', 'Brownies, cheesecakes, galletas'],
            ['Pasteles', 'Porciones y pasteles del día'],
            ['Sándwiches', 'Opciones saladas frescas'],
            ['Desayunos', 'Combos para empezar el día'],
            ['Especialidades de la casa', 'Creaciones exclusivas CAFEESQUINA'],
        ];

        foreach ($categories as [$name, $desc]) {
            DB::table('categories')->updateOrInsert(['name' => $name], ['description' => $desc]);
        }

        if (DB::table('products')->count() === 0) {
            $cat = fn (string $n) => (int) DB::table('categories')->where('name', $n)->value('id');

            DB::table('products')->insert([
                ['category_id' => $cat('Cafés calientes'), 'name' => 'Espresso Esquina', 'description' => 'Shot intenso de café artesanal.', 'price' => 2.50, 'image' => 'uploads/products/espresso.png', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Cafés calientes'), 'name' => 'Capuchino Vainilla', 'description' => 'Espuma sedosa con toque de vainilla.', 'price' => 3.75, 'image' => 'uploads/products/capuchino.png', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Cafés fríos'), 'name' => 'Iced Caramel Latte', 'description' => 'Latte frío con caramelo.', 'price' => 4.25, 'image' => 'uploads/products/latte.png', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Frappés'), 'name' => 'Frappé Moka', 'description' => 'Chocolate y café batido.', 'price' => 4.99, 'image' => 'uploads/products/frappe.png', 'status' => 'available', 'featured' => 0],
                ['category_id' => $cat('Postres'), 'name' => 'Cheesecake Maracuyá', 'description' => 'Cremoso con coulis tropical.', 'price' => 3.99, 'image' => 'uploads/products/cheesecake.png', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Desayunos'), 'name' => 'Desayuno Esquina', 'description' => 'Café + croissant + jugo.', 'price' => 6.50, 'image' => 'uploads/products/desayuno.png', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Especialidades de la casa'), 'name' => 'Affogato Especial', 'description' => 'Helado de vainilla con espresso caliente.', 'price' => 5.25, 'image' => 'uploads/products/affogato.png', 'status' => 'available', 'featured' => 1],
            ]);
        }

        if (DB::table('promotions')->count() === 0) {
            DB::table('promotions')->insert([
                [
                    'title' => 'Combo Mañanero',
                    'description' => 'Café mediano + pastel por $5.99',
                    'image' => 'uploads/products/desayuno.png',
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(30)->toDateString(),
                    'active' => 1,
                ],
                [
                    'title' => '2x1 Frappés',
                    'description' => 'Viernes de frappés: lleva 2 y paga 1',
                    'image' => 'uploads/products/frappe.png',
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(14)->toDateString(),
                    'active' => 1,
                ],
            ]);
        }
    }
}
