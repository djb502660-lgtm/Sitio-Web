<?php

$defaults = [
    'base_url' => '/Sitio-Web', // sincronizado con APP_URL en .env
    'app_name' => 'CAFEESQUINA',
    'tagline' => 'Cada taza cuenta una historia',
    'whatsapp_number' => '593963947808',
    'session_name' => 'CAFEESQUINA_SESSION',
    'address' => 'Av. Principal y Calle del Café, Quito, Ecuador',
    'hours' => 'Lun–Vie 7:00–21:00 | Sáb–Dom 8:00–22:00',
    'social' => [
        'instagram' => 'https://instagram.com/cafeesquina',
        'facebook' => 'https://facebook.com/cafeesquina',
    ],
    'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.75!2d-78.4678!3d-0.1807!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTHCsDEwJzUwLjUiUyA3OMKwMjgnMDQuMSJX!5e0!3m2!1ses!2sec!4v1',
];

if (function_exists('config')) {
    $merged = array_merge($defaults, config('cafeesquina', []));
    if (function_exists('env')) {
        $path = parse_url((string) env('APP_URL', 'http://localhost/Sitio-Web'), PHP_URL_PATH);
        $merged['base_url'] = rtrim($path ?: '/Sitio-Web', '/');
    }
    return $merged;
}

// Standalone (scripts/migrate.php)
if (file_exists(dirname(__DIR__, 2) . '/.env')) {
    $lines = file(dirname(__DIR__, 2) . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), 'APP_URL=')) {
            $url = trim(substr($line, 8), " \t\"'");
            $path = parse_url($url, PHP_URL_PATH);
            $defaults['base_url'] = rtrim($path ?: '/Sitio-Web', '/');
            break;
        }
    }
}

return $defaults;
