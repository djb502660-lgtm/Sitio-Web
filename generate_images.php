<?php
/**
 * Descarga fotos de productos a cafeesquina/uploads/products/
 * Ejecutar: php generate_images.php
 */

$uploadDir = __DIR__ . '/cafeesquina/uploads/products';
if (! is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$sources = [
    'espresso.jpg' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&q=80',
    'capuchino.jpg' => 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=800&q=80',
    'latte.jpg' => 'https://images.unsplash.com/photo-1442512595331-e89e73853f31?w=800&q=80',
    'frappe.jpg' => 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=800&q=80',
    'cheesecake.jpg' => 'https://images.unsplash.com/photo-1504754524776-8f4f37790ca0?w=800&q=80',
    'desayuno.jpg' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800&q=80',
    'affogato.jpg' => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=800&q=80',
];

$context = stream_context_create([
    'http' => [
        'header' => "User-Agent: CAFEESQUINA/1.0\r\n",
        'timeout' => 30,
    ],
]);

foreach ($sources as $filename => $url) {
    $data = @file_get_contents($url, false, $context);
    if ($data === false || strlen($data) < 1024) {
        echo "✗ Error descargando: $filename\n";
        continue;
    }

    $path = $uploadDir . '/' . $filename;
    file_put_contents($path, $data);
    echo '✓ ' . $filename . ' (' . number_format(strlen($data)) . " bytes)\n";
}

echo "\n✅ Imágenes de productos listas.\n";
