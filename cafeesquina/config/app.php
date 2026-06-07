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
    'map_embed' => 'https://www.google.com/maps?q=1.282271829334691,-78.82911662497025&output=embed',
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
