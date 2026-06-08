<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class CafeesquinaTestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshApplication();
        $this->resetCafeesquinaSession();
        require_once base_path('cafeesquina/config/helpers.php');
    }

    protected function resetCafeesquinaSession(): void
    {
        $this->defaultCookies = [];
        $this->unencryptedCookies = [];
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $this->purgeTestCategories();
        parent::tearDown();
    }

    /** Evita que los tests dejan categorías "Cat QA" / "Categoria Test" en la BD real. */
    protected function purgeTestCategories(): void
    {
        try {
            DB::table('categories')
                ->where('id', '>', 10)
                ->orWhere('name', 'like', 'Cat QA%')
                ->orWhere('name', 'like', 'Categoria Test%')
                ->orWhere('name', 'like', 'TestCat%')
                ->delete();
        } catch (\Throwable) {
            // Sin conexión BD en tests aislados.
        }
    }
}
