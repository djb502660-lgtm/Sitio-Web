<?php

$defaults = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'dbname' => 'cafeesquina',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
];

if (function_exists('env')) {
    $driver = env('DB_CONNECTION', 'mysql');
    if ($driver === 'sqlite') {
        return [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', ':memory:'),
        ];
    }

    return [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'dbname' => env('DB_DATABASE', 'cafeesquina'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
    ];
}

if (file_exists(dirname(__DIR__, 2) . '/.env')) {
    $vars = [];
    foreach (file(dirname(__DIR__, 2) . '/.env') as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
            continue;
        }
        [$k, $v] = explode('=', $line, 2);
        $vars[trim($k)] = trim($v, " \t\"'");
    }
    return [
        'host' => $vars['DB_HOST'] ?? $defaults['host'],
        'port' => $vars['DB_PORT'] ?? $defaults['port'],
        'dbname' => $vars['DB_DATABASE'] ?? $defaults['dbname'],
        'username' => $vars['DB_USERNAME'] ?? $defaults['username'],
        'password' => $vars['DB_PASSWORD'] ?? $defaults['password'],
        'charset' => 'utf8mb4',
    ];
}

return $defaults;
