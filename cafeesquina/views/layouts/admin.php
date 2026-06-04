<?php $flash = get_flash(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Admin CAFEESQUINA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= e(asset_url('css/cafeesquina.css')) ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">
<aside class="admin-sidebar w-64 text-cream p-6 hidden lg:block shrink-0">
    <h2 class="text-xl font-bold text-gold mb-8"><i class="fas fa-coffee"></i> Admin</h2>
    <nav class="space-y-2 text-sm">
        <a href="<?= e(base_url('admin')) ?>" class="block py-2 hover:text-gold"><i class="fas fa-chart-pie"></i> Dashboard</a>
        <a href="<?= e(base_url('admin/productos')) ?>" class="block py-2 hover:text-gold"><i class="fas fa-mug-hot"></i> Productos</a>
        <a href="<?= e(base_url('admin/categorias')) ?>" class="block py-2 hover:text-gold"><i class="fas fa-tags"></i> Categorías</a>
        <a href="<?= e(base_url('admin/promociones')) ?>" class="block py-2 hover:text-gold"><i class="fas fa-percent"></i> Promociones</a>
        <a href="<?= e(base_url('admin/usuarios')) ?>" class="block py-2 hover:text-gold"><i class="fas fa-users"></i> Usuarios</a>
        <hr class="border-cream/20 my-4">
        <a href="<?= e(base_url('')) ?>" class="block py-2 hover:text-gold"><i class="fas fa-store"></i> Ver sitio</a>
        <a href="<?= e(base_url('logout')) ?>" class="block py-2 hover:text-gold"><i class="fas fa-sign-out-alt"></i> Salir</a>
    </nav>
</aside>
<div class="flex-1 flex flex-col min-w-0">
    <header class="bg-white shadow px-4 py-3 lg:hidden flex justify-between items-center">
        <span class="font-bold">CAFEESQUINA Admin</span>
        <a href="<?= e(base_url('admin')) ?>" class="text-sm text-gold">Menú</a>
    </header>
    <?php if ($flash): ?>
    <script>document.addEventListener('DOMContentLoaded',()=>showToast('<?= $flash['type']==='success'?'success':'error' ?>','<?= e(addslashes($flash['message'])) ?>'));</script>
    <?php endif; ?>
    <main class="p-4 md:p-8 flex-1"><?= $content ?></main>
</div>
<script src="<?= e(asset_url('js/app.js')) ?>"></script>
</body>
</html>
