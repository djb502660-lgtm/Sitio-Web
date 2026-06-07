<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Elimina categorías de prueba creadas por tests manuales o automatizados.
 */
class CleanupCafeesquinaTestDataSeeder extends Seeder
{
    public function run(): void
    {
        $deleted = DB::table('categories')
            ->where(function ($q) {
                $q->where('id', '>', 10)
                    ->orWhere('name', 'like', 'Cat QA%')
                    ->orWhere('name', 'like', 'Categoria Test%')
                    ->orWhere('name', 'like', 'TestCat%');
            })
            ->delete();

        $this->command?->info("Categorías de prueba eliminadas: {$deleted}");
    }
}
