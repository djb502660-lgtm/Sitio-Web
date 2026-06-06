<?php

use App\Http\Controllers\CafeesquinaBridgeController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/*
|--------------------------------------------------------------------------
| CAFEESQUINA — Rutas unificadas (Laravel + motor MVC en cafeesquina/)
|--------------------------------------------------------------------------
*/

Route::redirect('/cafeesquina', '/', 301);
Route::redirect('/cafeesquina/{any}', '/{any}', 301)->where('any', '.*');

/** MIME explícito para que el navegador interprete CSS/JS correctamente */
$ceStaticMime = static function (string $file): string {
    return match (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
        'css' => 'text/css',
        'js' => 'application/javascript',
        'svg' => 'image/svg+xml',
        'json' => 'application/json',
        default => mime_content_type($file) ?: 'application/octet-stream',
    };
};

/** Archivos estáticos (fallback si Apache no reescribe; sin sesión Laravel) */
Route::get('/assets/{path}', function (string $path) use ($ceStaticMime): BinaryFileResponse {
    $file = base_path('cafeesquina/assets/' . $path);
    abort_unless(is_file($file), 404);

    return response()->file($file, ['Content-Type' => $ceStaticMime($file)]);
})->where('path', '.*')->withoutMiddleware([
    StartSession::class,
    ShareErrorsFromSession::class,
    VerifyCsrfToken::class,
]);

Route::get('/uploads/{path}', function (string $path) use ($ceStaticMime): BinaryFileResponse {
    $file = base_path('cafeesquina/uploads/' . $path);
    abort_unless(is_file($file), 404);

    return response()->file($file, ['Content-Type' => $ceStaticMime($file)]);
})->where('path', '.*')->withoutMiddleware([
    StartSession::class,
    ShareErrorsFromSession::class,
    VerifyCsrfToken::class,
]);

/** Aplicación completa (sin sesión/CSRF de Laravel; usa la de cafeesquina) */
Route::any('/{path?}', [CafeesquinaBridgeController::class, 'handle'])
    ->where('path', '^(?!up$).*')
    ->withoutMiddleware([
        StartSession::class,
        ShareErrorsFromSession::class,
        VerifyCsrfToken::class,
    ]);
