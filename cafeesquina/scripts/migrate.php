<?php

declare(strict_types=1);

echo "=== Migración CAFEESQUINA ===\n\n";
$mysql = 'C:\\xampp\\mysql\\bin\\mysql.exe';
$sqlFile = dirname(__DIR__) . '/database/schema.sql';
$fresh = in_array('--fresh', $argv ?? [], true);

if ($fresh) {
    exec("\"{$mysql}\" -u root -e \"DROP DATABASE IF EXISTS cafeesquina\"", $o, $c);
    echo "[!] Base recreada (--fresh)\n";
}

exec('cmd /c "' . $mysql . '" -u root < "' . str_replace('\\', '/', $sqlFile) . '"', $o, $code);
if ($code !== 0) {
    echo "[FAIL] Error importando schema.sql\n";
    exit(1);
}

$c = require dirname(__DIR__) . '/config/database.php';
$pdo = new PDO(
    sprintf('mysql:host=%s;port=%s;dbname=%s', $c['host'], $c['port'], $c['dbname']),
    $c['username'],
    $c['password']
);
$hash = password_hash('Admin123!', PASSWORD_DEFAULT);
$pdo->prepare('UPDATE users SET email=?, password=?, role=? WHERE username=?')->execute([
    'admin@cafeesquina.local', $hash, 'admin', 'admin',
]);

echo "[OK] Tablas y datos iniciales listos.\n";
echo "Admin: admin@cafeesquina.local / Admin123!\n";
echo "URL: http://localhost/Sitio-Web/\n";
