<?php

declare(strict_types=1);

/**
 * Router MVC de CAFEESQUINA (usable desde index.php o Laravel Bridge).
 */
function cafeesquina_dispatch(string $route): void
{
    require_once __DIR__ . '/config/bootstrap.php';

    http_response_code(200);

    $route = trim($route, '/');

    $routes = [
        '' => [HomeController::class, 'index'],
        'menu' => [ProductController::class, 'menu'],
        'producto' => [ProductController::class, 'show'],
        'carrito' => [CartController::class, 'index'],
        'carrito/checkout' => [CartController::class, 'checkout'],
        'pedido/registrar' => [ProductController::class, 'logOrder'],
        'login' => [AuthController::class, 'loginForm'],
        'login/post' => [AuthController::class, 'login'],
        'register' => [AuthController::class, 'registerForm'],
        'register/post' => [AuthController::class, 'register'],
        'logout' => [AuthController::class, 'logout'],
        'perfil' => [AuthController::class, 'profile'],
        'perfil/actualizar' => [AuthController::class, 'updateProfile'],
        'pedidos' => [AuthController::class, 'orders'],
        'sitemap.xml' => [HomeController::class, 'sitemap'],
        'admin' => [AdminController::class, 'dashboard'],
        'admin/productos' => [AdminController::class, 'products'],
        'admin/productos/store' => [AdminController::class, 'storeProduct'],
        'admin/productos/update' => [AdminController::class, 'updateProduct'],
        'admin/productos/delete' => [AdminController::class, 'deleteProduct'],
        'admin/categorias' => [AdminController::class, 'categories'],
        'admin/categorias/store' => [AdminController::class, 'storeCategory'],
        'admin/categorias/update' => [AdminController::class, 'updateCategory'],
        'admin/categorias/delete' => [AdminController::class, 'deleteCategory'],
        'admin/promociones' => [AdminController::class, 'promotions'],
        'admin/promociones/store' => [AdminController::class, 'storePromotion'],
        'admin/promociones/update' => [AdminController::class, 'updatePromotion'],
        'admin/promociones/delete' => [AdminController::class, 'deletePromotion'],
        'admin/usuarios' => [AdminController::class, 'users'],
        'admin/usuarios/update' => [AdminController::class, 'updateUser'],
        'admin/usuarios/delete' => [AdminController::class, 'deleteUser'],
    ];

    if (!isset($routes[$route])) {
        http_response_code(404);
        ce_view('_404', ['title' => 'Página no encontrada']);

        return;
    }

    [$class, $method] = $routes[$route];
    (new $class())->{$method}();
}
