<?php

declare(strict_types=1);

// Laravel ya define e(); solo en scripts standalone:
if (! function_exists('e')) {
    function e(?string $v): string
    {
        return htmlspecialchars((string) $v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

/**
 * Ruta base real según el entorno (raíz con artisan serve, /Sitio-Web con Laragon/XAMPP).
 */
function ce_app_base_path(): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
    $base = dirname($scriptName);

    // Windows: dirname('/index.php') puede devolver '\' en vez de '/'
    if ($base === '\\' || $base === '.') {
        $base = '';
    }

    $base = str_replace('\\', '/', (string) $base);

    if ($base === '/' || $base === '') {
        return '';
    }

    return rtrim($base, '/');
}

function base_url(string $path = ''): string
{
    $base = ce_app_base_path();

    if ($path === '') {
        return $base !== '' ? $base : '/';
    }

    return ($base !== '' ? $base : '') . '/' . ltrim($path, '/');
}

function ce_redirect(string $path): never
{
    $url = base_url($path);

    if (function_exists('app') && class_exists(\Illuminate\Foundation\Application::class)) {
        try {
            $app = app();
            if ($app->bound('router')) {
                throw new \Illuminate\Http\Exceptions\HttpResponseException(
                    redirect()->to($url)
                );
            }
        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            throw $e;
        } catch (\Throwable) {
            // Fallback a redirect nativo (scripts standalone).
        }
    }

    header('Location: ' . $url, true, 302);
    exit;
}

function ce_csrf_token(): string
{
    $_SESSION['csrf_token'] ??= bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}

function ce_csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(ce_csrf_token()) . '">';
}

function ce_csrf_verify(): bool
{
    return ce_csrf_verify_request();
}

function ce_csrf_verify_request(): bool
{
    $expected = $_SESSION['csrf_token'] ?? null;
    if (! is_string($expected) || $expected === '') {
        return false;
    }

    $submitted = $_POST['csrf_token'] ?? null;
    if ($submitted === null && ! empty($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $submitted = $_SERVER['HTTP_X_CSRF_TOKEN'];
    }

    return is_string($submitted) && hash_equals($expected, $submitted);
}

function ce_client_ip(): string
{
    $forwarded = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    if (is_string($forwarded) && $forwarded !== '') {
        return trim(explode(',', $forwarded)[0]);
    }

    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

/**
 * @return bool true si la petición está permitida; false si superó el límite
 */
function ce_rate_limit(string $bucket, int $maxAttempts, int $windowSeconds): bool
{
    $key = 'rate_' . preg_replace('/[^a-zA-Z0-9:_-]/', '', $bucket);
    $now = time();

    if (! isset($_SESSION[$key]) || $now >= ($_SESSION[$key]['reset'] ?? 0)) {
        $_SESSION[$key] = ['count' => 0, 'reset' => $now + $windowSeconds];
    }

    $_SESSION[$key]['count']++;

    return $_SESSION[$key]['count'] <= $maxAttempts;
}

function auth_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return auth_user() !== null;
}

function is_admin(): bool
{
    $u = auth_user();
    return $u && ($u['role'] ?? '') === 'admin';
}

function require_auth(): void
{
    if (!is_logged_in()) {
        flash('error', 'Inicia sesión para continuar.');
        ce_redirect('login');
    }
}

function require_admin(): void
{
    require_auth();
    if (!is_admin()) {
        flash('error', 'Acceso restringido al personal autorizado.');
        ce_redirect('');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = compact('type', 'message');
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $f;
}

function sanitize_string(string $v, int $max = 255): string
{
    return mb_substr(trim($v), 0, $max);
}

function whatsapp_order_url(string $productName, float $price): string
{
    $user = auth_user();
    $hello = $user
        ? "Hola, soy {$user['username']}. Me gustaría ordenar el siguiente producto:"
        : 'Hola, me gustaría ordenar el siguiente producto:';
    $msg = sprintf("%s\n\n☕ Producto: %s\n💲 Precio: $%s\n\nGracias.", $hello, $productName, number_format($price, 2));
    $num = preg_replace('/\D/', '', (string) app_config('whatsapp_number'));
    return 'https://wa.me/' . $num . '?text=' . rawurlencode($msg);
}

function ce_view(string $name, array $data = [], string $layout = 'main'): void
{
    $data['extendsLayout'] = $layout === 'admin'
        ? 'cafeesquina.layouts.admin'
        : 'cafeesquina.layouts.main';

    $viewName = 'cafeesquina.' . str_replace('/', '.', $name);

    if (function_exists('view') && class_exists(\Illuminate\Foundation\Application::class)) {
        try {
            echo view($viewName, $data)->render();
            return;
        } catch (\InvalidArgumentException) {
            // Vista Blade no encontrada; fallback legacy abajo.
        }
    }

    extract($data, EXTR_SKIP);
    ob_start();
    require dirname(__DIR__) . '/views/' . str_replace('.', '/', $name) . '.php';
    $content = ob_get_clean();
    $pageTitle = $title ?? (string) app_config('app_name');
    $meta_description = $meta_description ?? (string) app_config('tagline');
    require dirname(__DIR__) . '/views/layouts/' . $layout . '.php';
}

function asset_url(string $path): string
{
    return base_url('assets/' . ltrim($path, '/'));
}

function upload_url(string $path): string
{
    return base_url('uploads/' . ltrim($path, '/'));
}

function media_url(?string $path, ?string $fallback = null): string
{
    if ($path === null || $path === '') {
        return $fallback ?? '';
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, 'data:')) {
        return $path;
    }

    $base = rtrim((string) app_config('base_url'), '/');
    if ($base !== '' && (str_starts_with($path, $base . '/') || $path === $base)) {
        return $path;
    }

    return base_url(ltrim($path, '/'));
}
