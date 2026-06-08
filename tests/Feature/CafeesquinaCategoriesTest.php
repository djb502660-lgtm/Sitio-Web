<?php

namespace Tests\Feature;

use Tests\CafeesquinaTestCase;
use Tests\Concerns\AuthenticatesCafeesquinaAdmin;

class CafeesquinaCategoriesTest extends CafeesquinaTestCase
{
    use AuthenticatesCafeesquinaAdmin;

    public function test_admin_categories_requires_login(): void
    {
        $response = $this->get('/admin/categorias');

        $response->assertRedirect('/login');
        $this->assertStringNotContainsString('/Sitio-Web/Sitio-Web/', (string) $response->headers->get('Location'));
    }

    public function test_admin_categories_page_loads_for_admin(): void
    {
        $this->loginAsAdmin();

        $response = $this->get('/admin/categorias');

        $response->assertStatus(200);
        $response->assertSee('Categorías', false);
        $response->assertSee('/admin/categorias/store', false);
    }

    public function test_store_category_redirects_without_doubled_base_path(): void
    {
        $this->loginAsAdmin();

        $page = $this->get('/admin/categorias');
        $token = $this->extractCsrf((string) $page->getContent());

        $response = $this->post('/admin/categorias/store', [
            'csrf_token' => $token,
            'name' => 'Categoria Test ' . uniqid(),
            'description' => 'Prueba automatizada',
        ]);

        $response->assertRedirect('/admin/categorias');
        $this->assertStringNotContainsString('/Sitio-Web/Sitio-Web/', (string) $response->headers->get('Location'));
    }

    private function extractCsrf(string $html): string
    {
        return $this->extractCsrfToken($html);
    }
}
