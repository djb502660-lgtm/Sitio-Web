<?php

namespace Tests\Feature;

use Tests\TestCase;

class CafeesquinaCartTest extends TestCase
{
    public function test_cart_page_loads(): void
    {
        $response = $this->get('/carrito');

        $response->assertStatus(200);
        $response->assertSee('Carrito', false);
        $response->assertSee('data-cart-page', false);
        $response->assertSee('data-cart-checkout', false);
    }

    public function test_cart_checkout_requires_csrf(): void
    {
        $response = $this->postJson('/carrito/checkout', [
            'items' => [['product_id' => 1, 'qty' => 1]],
        ]);

        $response->assertStatus(403);
    }

    public function test_cart_checkout_rejects_empty_cart(): void
    {
        $token = $this->csrfTokenFromPage('/carrito');

        $response = $this->post('/carrito/checkout', [
            'items' => [],
        ], [
            'X-CSRF-TOKEN' => $token,
            'Accept' => 'application/json',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['ok' => false, 'error' => 'empty']);
    }

    private function csrfTokenFromPage(string $path): string
    {
        $html = $this->get($path)->getContent();
        if (preg_match('/name="csrf-token" content="([^"]+)"/', $html, $matches)) {
            return $matches[1];
        }

        return '';
    }
}
