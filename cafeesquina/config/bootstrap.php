<?php

declare(strict_types=1);

$appConfig = require __DIR__ . '/app.php';

require_once __DIR__ . '/helpers.php';

session_name($appConfig['session_name']);
$sessionPath = ce_app_base_path() ?: '/';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => $sessionPath,
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

spl_autoload_register(function (string $class): void {
    foreach ([
        dirname(__DIR__) . '/models/' . $class . '.php',
        dirname(__DIR__) . '/controllers/' . $class . '.php',
    ] as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $c = require __DIR__ . '/database.php';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    if (($c['driver'] ?? 'mysql') === 'sqlite') {
        $pdo = new PDO('sqlite:' . $c['database'], null, null, $options);
    } else {
        $pdo = new PDO(
            sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $c['host'], $c['port'], $c['dbname'], $c['charset']),
            $c['username'],
            $c['password'],
            $options
        );
    }

    return $pdo;
}

function app_config(?string $key = null): mixed
{
    static $config = null;
    $config ??= require __DIR__ . '/app.php';
    return $key === null ? $config : ($config[$key] ?? null);
}
