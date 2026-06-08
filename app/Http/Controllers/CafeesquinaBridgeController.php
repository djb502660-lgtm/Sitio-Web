<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Puente Laravel → motor MVC de cafeesquina/.
 */
class CafeesquinaBridgeController extends Controller
{
    public function handle(Request $request, ?string $path = ''): Response
    {
        $route = trim((string) $path, '/');

        // Quitar prefijo de subcarpeta XAMPP si llega en la ruta (ej. Sitio-Web/menu)
        $base = trim((string) parse_url((string) config('app.url'), PHP_URL_PATH), '/');
        if ($base !== '' && ($route === $base || str_starts_with($route, $base . '/'))) {
            $route = ltrim(substr($route, strlen($base)), '/');
        }

        $_SERVER['REQUEST_METHOD'] = $request->method();
        $_SERVER['REQUEST_URI'] = $request->getRequestUri();
        if ($request->getContent() !== '') {
            $_POST = array_merge($_POST, $request->request->all());
        }

        require_once base_path('cafeesquina/router.php');

        ob_start();
        try {
            cafeesquina_dispatch($route);
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            ob_end_clean();
            throw $e;
        }
        $html = ob_get_clean();

        $status = http_response_code();
        if ($status === false) {
            $status = 200;
        }

        return response($html, $status);
    }
}
