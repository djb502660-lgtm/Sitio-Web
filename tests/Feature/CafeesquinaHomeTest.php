<?php

namespace Tests\Feature;

use Tests\TestCase;

class CafeesquinaHomeTest extends TestCase
{
    public function test_home_page_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CAFEESQUINA', false);
    }

    public function test_menu_route_loads(): void
    {
        $response = $this->get('/menu');

        $response->assertStatus(200);
        $response->assertSee('Menú', false);
    }

    public function test_unknown_route_returns_404(): void
    {
        $response = $this->get('/ruta-inexistente-xyz');

        $response->assertStatus(404);
        $response->assertSee('404', false);
    }
}
