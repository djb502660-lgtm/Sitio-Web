<?php

namespace Tests\Feature;

use Tests\CafeesquinaTestCase;

class CafeesquinaAssetTest extends CafeesquinaTestCase
{

    public function test_base_path_is_empty_with_artisan_serve(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';

        $this->assertSame('', ce_app_base_path());
        $this->assertSame('/assets/css/cafeesquina.css', asset_url('css/cafeesquina.css'));
        $this->assertSame('/menu', base_url('menu'));
    }

    public function test_base_path_includes_subdirectory_on_laragon(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/Sitio-Web/index.php';

        $this->assertSame('/Sitio-Web', ce_app_base_path());
        $this->assertSame('/Sitio-Web/assets/css/cafeesquina.css', asset_url('css/cafeesquina.css'));
        $this->assertSame('/Sitio-Web/menu', base_url('menu'));
    }

    public function test_windows_backslash_base_path_is_normalized(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        $this->assertSame('', ce_app_base_path());
    }

    public function test_base_path_strips_public_and_uses_app_url(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/Sitio-Web/public/index.php';

        $this->assertSame('/Sitio-Web', ce_app_base_path());
        $this->assertSame('/Sitio-Web/admin/categorias', base_url('admin/categorias'));
    }

    public function test_css_asset_returns_200_with_correct_mime(): void
    {
        $response = $this->get('/assets/css/cafeesquina.css');

        $response->assertStatus(200);
        $this->assertStringStartsWith('text/css', (string) $response->headers->get('Content-Type'));
    }

    public function test_upload_url_respects_subdirectory(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/Sitio-Web/index.php';

        $this->assertSame('/Sitio-Web/uploads/foto.jpg', upload_url('foto.jpg'));
    }
}
