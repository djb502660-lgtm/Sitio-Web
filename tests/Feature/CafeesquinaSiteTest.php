<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\CafeesquinaTestCase;
use Tests\Concerns\AuthenticatesCafeesquinaAdmin;

/**
 * Pruebas de integración del sitio CAFEESQUINA (rutas públicas, auth y admin).
 */
class CafeesquinaSiteTest extends CafeesquinaTestCase
{
    use AuthenticatesCafeesquinaAdmin;

    /* --- Páginas públicas --- */

    public function test_home_shows_brand_categories_and_products(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('CAFEESQUINA', false);
        $response->assertSee('Categorías', false);
        $response->assertSee('menu?categoria=', false);
        $response->assertSee('product-card', false);
        $response->assertDontSee('Cat QA', false);
        $response->assertDontSee('Categoria Test', false);
    }

    public function test_home_shows_only_seed_categories_count(): void
    {
        $this->assertSame(10, DB::table('categories')->count());
    }

    public function test_menu_lists_products_without_skeleton_trap(): void
    {
        $response = $this->get('/menu');

        $response->assertStatus(200);
        $response->assertSee('Menú', false);
        $response->assertSee('data-catalog-grid', false);
        $response->assertDontSee('data-catalog-skeleton', false);
        $response->assertSee('product-card', false);
        $response->assertDontSee('data-catalog-grid class="grid grid-4 hidden"', false);
        $response->assertSee('/uploads/products/', false);
        $response->assertDontSee('images.unsplash.com', false);
    }

    public function test_product_upload_images_are_served(): void
    {
        $response = $this->get('/uploads/products/espresso.png');

        $response->assertStatus(200);
        $this->assertStringStartsWith('image/png', (string) $response->headers->get('Content-Type'));
    }

    public function test_menu_filters_by_category(): void
    {
        $response = $this->get('/menu?categoria=1');

        $response->assertStatus(200);
        $response->assertSee('Cafés calientes', false);
        $response->assertSee('product-card', false);
        $response->assertSee('Espresso', false);
    }

    public function test_menu_search_finds_product(): void
    {
        $response = $this->get('/menu?q=Capuchino');

        $response->assertStatus(200);
        $response->assertSee('Capuchino', false);
        $response->assertSee('product-card', false);
    }

    public function test_product_detail_page_loads(): void
    {
        $response = $this->get('/producto?id=1');

        $response->assertStatus(200);
        $response->assertSee('Espresso', false);
        $response->assertSee('wa.me', false);
    }

    public function test_login_and_register_pages_load(): void
    {
        $this->get('/login')->assertStatus(200)->assertSee('Iniciar sesión', false);
        $this->get('/register')->assertStatus(200)->assertSee('Crear cuenta', false);
    }

    public function test_register_redirects_to_home(): void
    {
        $email = 'nuevo.' . time() . '@cafeesquina.local';
        $page = $this->get('/register');
        $token = $this->extractCsrfToken((string) $page->getContent());

        $this->post('/register/post', [
            'csrf_token' => $token,
            'full_name' => 'Usuario Nuevo',
            'email' => $email,
            'password' => 'Admin123!',
            'password_confirm' => 'Admin123!',
        ])->assertRedirect('/');

        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_sitemap_xml_is_available(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/xml; charset=utf-8');
        $response->assertSee('<urlset', false);
    }

    public function test_legacy_cafeesquina_path_redirects_to_root(): void
    {
        $response = $this->get('/cafeesquina/menu');

        $response->assertRedirect('/menu');
    }

    /* --- Assets --- */

    public function test_assets_served_from_cafeesquina_motor(): void
    {
        $css = $this->get('/assets/css/cafeesquina.css');
        $css->assertStatus(200);
        $this->assertStringStartsWith('text/css', (string) $css->headers->get('Content-Type'));

        $js = $this->get('/assets/js/app.js');
        $js->assertStatus(200);
        $this->assertStringStartsWith('application/javascript', (string) $js->headers->get('Content-Type'));

        $jsPath = base_path('cafeesquina/assets/js/app.js');
        $this->assertFileExists($jsPath);
        $this->assertStringContainsString('revealCatalog', (string) file_get_contents($jsPath));
    }

    /* --- Autenticación --- */

    public function test_login_rejects_invalid_credentials(): void
    {
        $login = $this->get('/login');
        $response = $this->post('/login/post', [
            'csrf_token' => $this->extractCsrfToken((string) $login->getContent()),
            'email' => 'admin@cafeesquina.local',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_admin_login_succeeds(): void
    {
        $this->loginAsAdmin();

        $this->get('/admin')->assertStatus(200)->assertSee('Dashboard', false);
    }

    /* --- Panel admin --- */

    public function test_admin_sections_require_login(): void
    {
        foreach (['admin', 'admin/productos', 'admin/categorias', 'admin/promociones', 'admin/usuarios', 'admin/ubicacion'] as $path) {
            $this->get('/' . $path)->assertRedirect('/login');
        }
    }

    public function test_admin_sections_load_for_admin(): void
    {
        $this->loginAsAdmin();

        $this->get('/admin/productos')->assertStatus(200)->assertSee('Productos', false);
        $this->get('/admin/categorias')->assertStatus(200)->assertSee('Categorías', false);
        $this->get('/admin/promociones')->assertStatus(200)->assertSee('Promociones', false);
        $this->get('/admin/usuarios')->assertStatus(200)->assertSee('Usuarios', false);
        $this->get('/admin/ubicacion')->assertStatus(200)->assertSee('Ubicación', false);
    }

    public function test_admin_can_update_location_settings(): void
    {
        $this->loginAsAdmin();

        $page = $this->get('/admin/ubicacion');
        $token = $this->extractCsrfToken((string) $page->getContent());
        $address = 'Calle Test ' . time() . ', Ecuador';

        $this->post('/admin/ubicacion/actualizar', [
            'csrf_token' => $token,
            'address' => $address,
            'hours' => 'Lun–Dom 8:00–20:00',
            'map_embed' => 'https://www.google.com/maps?q=1.28,-78.83&output=embed',
        ])->assertRedirect('/admin/ubicacion');

        $this->get('/')->assertStatus(200)->assertSee($address, false);
        $this->assertDatabaseHas('site_settings', ['key' => 'address', 'value' => $address]);
    }

    public function test_admin_category_crud_flow(): void
    {
        $this->loginAsAdmin();

        $page = $this->get('/admin/categorias');
        $name = 'Cat QA ' . uniqid();

        $store = $this->post('/admin/categorias/store', [
            'csrf_token' => $this->extractCsrfToken((string) $page->getContent()),
            'name' => $name,
            'description' => 'Prueba integración',
        ]);

        $store->assertRedirect('/admin/categorias');
        $this->assertStringNotContainsString('/Sitio-Web/Sitio-Web/', (string) $store->headers->get('Location'));

        $after = $this->get('/admin/categorias');
        $after->assertSee($name, false);
    }

    public function test_guest_sees_login_instead_of_buy_on_menu(): void
    {
        $response = $this->get('/menu');

        $response->assertStatus(200);
        $response->assertSee('Iniciar sesión para comprar', false);
        $response->assertSee('/login?compra=1', false);
        $response->assertDontSee('data-whatsapp-order', false);
    }

    public function test_logged_in_user_can_buy_from_menu(): void
    {
        $this->loginAsAdmin();

        $response = $this->get('/menu');

        $response->assertStatus(200);
        $response->assertSee('data-whatsapp-order', false);
        $response->assertSee('Comprar por WhatsApp', false);
    }

    public function test_profile_save_redirects_to_menu(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'perfil.menu@cafeesquina.local'],
            [
                'username' => 'perfilmenu',
                'password' => bcrypt('Admin123!'),
                'role' => 'client',
                'full_name' => 'Adrian Castillo',
                'phone' => '0963947808',
            ]
        );

        $login = $this->get('/login');
        $token = $this->extractCsrfToken((string) $login->getContent());
        $this->post('/login/post', [
            'csrf_token' => $token,
            'email' => 'perfil.menu@cafeesquina.local',
            'password' => 'Admin123!',
        ]);

        $profile = $this->get('/perfil');
        $token2 = $this->extractCsrfToken((string) $profile->getContent());

        $this->post('/perfil/actualizar', [
            'csrf_token' => $token2,
            'full_name' => 'Adrian Castillo',
            'phone' => '0963947808',
            'email' => 'perfil.menu@cafeesquina.local',
        ])->assertRedirect('/menu');
    }

    public function test_purchase_flow_redirects_to_profile_then_menu(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'cliente.test@cafeesquina.local'],
            [
                'username' => 'clientetest',
                'password' => bcrypt('Admin123!'),
                'role' => 'client',
                'full_name' => 'Cliente Test',
                'phone' => null,
            ]
        );

        $login = $this->get('/login?compra=1');
        $token = $this->extractCsrfToken((string) $login->getContent());

        $this->post('/login/post', [
            'csrf_token' => $token,
            'email' => 'cliente.test@cafeesquina.local',
            'password' => 'Admin123!',
            'compra' => '1',
        ])->assertRedirect('/perfil?compra=1');

        $profile = $this->get('/perfil?compra=1');
        $token2 = $this->extractCsrfToken((string) $profile->getContent());

        $this->post('/perfil/actualizar', [
            'csrf_token' => $token2,
            'full_name' => 'Cliente Test',
            'phone' => '0991234567',
            'email' => 'cliente.test@cafeesquina.local',
            'compra' => '1',
        ])->assertRedirect('/menu');
    }

    public function test_log_order_requires_authentication(): void
    {
        $login = $this->get('/login');
        $token = $this->extractCsrfToken((string) $login->getContent());

        $response = $this->postJson('/pedido/registrar', [
            'product_id' => 1,
        ], [
            'X-CSRF-TOKEN' => $token,
        ]);

        $response->assertStatus(401);
        $response->assertJson(['ok' => false, 'error' => 'auth']);
    }

    public function test_logout_redirects_to_home(): void
    {
        $this->loginAsAdmin();

        $page = $this->get('/admin');
        $response = $this->post('/logout', [
            'csrf_token' => $this->extractCsrfToken((string) $page->getContent()),
        ]);

        $response->assertRedirect('/');
        $this->get('/admin')->assertRedirect('/login');
    }
}
