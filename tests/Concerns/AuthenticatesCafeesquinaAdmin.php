<?php

namespace Tests\Concerns;

trait AuthenticatesCafeesquinaAdmin
{
    protected function loginAsAdmin(): void
    {
        $probe = $this->get('/admin');
        if ($probe->getStatusCode() === 200) {
            return;
        }

        $login = $this->get('/login');
        $this->post('/login/post', [
            'csrf_token' => $this->extractCsrfToken((string) $login->getContent()),
            'email' => 'admin@cafeesquina.local',
            'password' => 'Admin123!',
        ]);
    }

    protected function extractCsrfToken(string $html): string
    {
        if (preg_match('/name="csrf_token" value="([^"]+)"/', $html, $matches)) {
            return $matches[1];
        }

        $this->fail('No se encontró csrf_token en la respuesta HTML.');
    }
}
