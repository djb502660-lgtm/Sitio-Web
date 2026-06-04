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
                'name' => 'Administrador',
                'username' => 'admin',
                'email' => 'admin@cafeesquina.local',
                'password' => $adminHash,
                'full_name' => 'Administrador',
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
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
            ['Tés', 'Tés artesanales'],
            ['Chocolates', 'Chocolate caliente premium'],
            ['Postres', 'Brownies y cheesecakes'],
            ['Pasteles', 'Porciones del día'],
            ['Sándwiches', 'Opciones saladas'],
            ['Desayunos', 'Combos mañaneros'],
            ['Especialidades de la casa', 'Creaciones exclusivas'],
        ];

        foreach ($categories as [$name, $desc]) {
            DB::table('categories')->updateOrInsert(['name' => $name], ['description' => $desc]);
        }

        if (DB::table('products')->count() === 0) {
            $cat = fn (string $n) => (int) DB::table('categories')->where('name', $n)->value('id');

            DB::table('products')->insert([
                ['category_id' => $cat('Cafés calientes'), 'name' => 'Espresso Esquina', 'description' => 'Shot intenso de café artesanal.', 'price' => 2.50, 'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=600', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Cafés calientes'), 'name' => 'Capuchino Vainilla', 'description' => 'Espuma sedosa con vainilla.', 'price' => 3.75, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e3d9?w=600', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Cafés fríos'), 'name' => 'Iced Caramel Latte', 'description' => 'Latte frío con caramelo.', 'price' => 4.25, 'image' => 'https://images.unsplash.com/photo-1517701604599-b8c035bafa0e?w=600', 'status' => 'available', 'featured' => 1],
                ['category_id' => $cat('Postres'), 'name' => 'Cheesecake Maracuyá', 'description' => 'Cremoso con coulis tropical.', 'price' => 3.99, 'image' => 'https://images.unsplash.com/photo-1524351199678-941a58a4df50?w=600', 'status' => 'available', 'featured' => 1],
            ]);
        }

        if (DB::table('promotions')->count() === 0) {
            DB::table('promotions')->insert([
                [
                    'title' => 'Combo Mañanero',
                    'description' => 'Café mediano + pastel por $5.99',
                    'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800',
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(30)->toDateString(),
                    'active' => 1,
                ],
            ]);
        }
    }
}
