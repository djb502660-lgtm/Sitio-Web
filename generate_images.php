<?php
/**
 * Generador de imágenes placeholder para productos
 * Crea archivos PNG reales que se pueden servir sin restricciones CORS/ORB
 */

$uploadDir = __DIR__ . '/cafeesquina/uploads/products';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$colors = [
    '#8B6F47', // Café marrón
    '#6D4C41', // Marrón oscuro
    '#A1887F', // Marrón claro
    '#4E342E', // Marrón muy oscuro
    '#795548', // Marrón medio
    '#9E8B80', // Marrón grisáceo
    '#5D4037', // Marrón principal
];

$products = [
    'espresso' => ['Espresso', '#8B6F47'],
    'capuchino' => ['Capuchino', '#6D4C41'],
    'latte' => ['Latte', '#A1887F'],
    'frappe' => ['Frappé', '#4E342E'],
    'cheesecake' => ['Postre', '#795548'],
    'desayuno' => ['Desayuno', '#9E8B80'],
    'affogato' => ['Affogato', '#5D4037'],
];

foreach ($products as $filename => $data) {
    [$label, $color] = $data;
    
    // Crear imagen PNG usando GD
    $width = 600;
    $height = 400;
    $image = imagecreatetruecolor($width, $height);
    
    // Convertir color hex a RGB
    $rgb = hexToRgb($color);
    $bgColor = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    imagefill($image, 0, 0, $bgColor);
    
    // Agregar texto
    $textColor = imagecolorallocate($image, 255, 255, 255);
    $fontFile = __DIR__ . '/cafeesquina/assets/fonts/arial.ttf';
    
    // Si no existe fuente TTF, usar texto plano
    if (!file_exists($fontFile)) {
        imagestring($image, 5, 250, 190, $label, $textColor);
    } else {
        imagettftext($image, 40, 0, 150, 220, $textColor, $fontFile, $label);
    }
    
    // Guardar como PNG
    $filepath = $uploadDir . '/' . $filename . '.png';
    imagepng($image, $filepath, 9);
    imagedestroy($image);
    
    echo "✓ Creado: $filepath\n";
}

echo "\n✅ Imágenes placeholder generadas exitosamente.\n";

function hexToRgb($hex) {
    $hex = ltrim($hex, '#');
    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}
