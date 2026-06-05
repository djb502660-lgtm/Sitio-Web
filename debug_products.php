<?php
require 'cafeesquina/config/bootstrap.php';

echo "=== Debug Productos ===\n\n";

$products = (new Product())->all(null, null, 'available');
echo "Total productos: " . count($products) . "\n\n";

if (count($products) > 0) {
    echo "Primer producto:\n";
    var_dump($products[0]);
    echo "\n\n";
    
    echo "Verificación de campos:\n";
    foreach ($products[0] as $k => $v) {
        echo "  $k: " . (is_string($v) ? substr($v, 0, 60) : $v) . "\n";
    }
}
