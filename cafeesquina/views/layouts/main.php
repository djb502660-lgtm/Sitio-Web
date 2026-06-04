<?php
$flash = get_flash();
$metaDesc = $meta_description ?? 'CAFEESQUINA — Café artesanal, postres y pedidos por WhatsApp.';
$canonical = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . base_url($_SERVER['REQUEST_URI'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?></title>
    <meta name="description" content="<?= e($metaDesc) ?>">
    <meta property="og:title" content="<?= e($pageTitle) ?>">
    <meta property="og:description" content="<?= e($metaDesc) ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?= e($canonical) ?>">
    <meta property="og:image" content="<?= e(asset_url('images/og-cafe.svg')) ?>">
    <link rel="canonical" href="<?= e($canonical) ?>">
    <meta name="csrf-token" content="<?= e(ce_csrf_token()) ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{colors:{coffee:{dark:'#3E2723',medium:'#5D4037',light:'#8D6E63'},cream:'#FFF8E1',gold:'#D4A373',accent:'#C97B63'}}}}</script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= e(asset_url('css/cafeesquina.css')) ?>">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-cream text-coffee-dark antialiased">
<div id="page-loader"><i class="fas fa-mug-hot text-4xl text-gold animate-pulse"></i></div>

<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur shadow-sm">
    <div class="max-w-7xl mx-auto px-4 flex justify-between items-center h-16">
        <a href="<?= e(base_url('')) ?>" class="text-xl font-bold tracking-tight">
            <i class="fas fa-coffee text-gold"></i> CAFEESQUINA
        </a>
        <div class="hidden md:flex gap-6 text-sm font-medium">
            <a href="<?= e(base_url('')) ?>#inicio" class="hover:text-gold">Inicio</a>
            <a href="<?= e(base_url('menu')) ?>" class="hover:text-gold">Menú</a>
            <a href="<?= e(base_url('')) ?>#nosotros" class="hover:text-gold">Nosotros</a>
            <a href="<?= e(base_url('')) ?>#promociones" class="hover:text-gold">Promociones</a>
            <a href="<?= e(base_url('')) ?>#ubicacion" class="hover:text-gold">Ubicación</a>
        </div>
        <div class="flex items-center gap-3 text-sm">
            <?php if (is_admin()): ?>
                <a href="<?= e(base_url('admin')) ?>" class="text-gold font-semibold"><i class="fas fa-chart-line"></i> Admin</a>
            <?php endif; ?>
            <?php if (is_logged_in() && !is_admin()): ?>
                <a href="<?= e(base_url('perfil')) ?>">Perfil</a>
                <a href="<?= e(base_url('pedidos')) ?>">Pedidos</a>
            <?php endif; ?>
            <?php if (is_logged_in()): ?>
                <form method="post" action="<?= e(base_url('logout')) ?>" class="inline">
                    <?= ce_csrf_field() ?>
                    <button type="submit" class="btn-outline text-xs px-3 py-1 border border-coffee-medium rounded-full">Salir</button>
                </form>
            <?php else: ?>
                <a href="<?= e(base_url('login')) ?>">Entrar</a>
                <a href="<?= e(base_url('register')) ?>" class="btn-primary text-xs px-4 py-2">Registro</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if ($flash): ?>
<script>document.addEventListener('DOMContentLoaded',()=>showToast('<?= $flash['type']==='success'?'success':'error' ?>','<?= e(addslashes($flash['message'])) ?>'));</script>
<?php endif; ?>

<main><?= $content ?></main>

<footer class="bg-coffee-dark text-cream py-12 mt-16">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8 text-sm">
        <div>
            <h3 class="font-bold text-gold text-lg mb-2">CAFEESQUINA</h3>
            <p><?= e((string) app_config('tagline')) ?></p>
        </div>
        <div>
            <h4 class="font-semibold mb-2">Horarios</h4>
            <p><?= e((string) app_config('hours')) ?></p>
        </div>
        <div>
            <h4 class="font-semibold mb-2">Síguenos</h4>
            <a href="<?= e(app_config('social')['instagram']) ?>" class="mr-3 hover:text-gold"><i class="fab fa-instagram"></i></a>
            <a href="<?= e(app_config('social')['facebook']) ?>" class="hover:text-gold"><i class="fab fa-facebook"></i></a>
        </div>
    </div>
    <p class="text-center text-cream/60 text-xs mt-8">&copy; <?= date('Y') ?> CAFEESQUINA</p>
</footer>
<script src="<?= e(asset_url('js/app.js')) ?>"></script>
</body>
</html>
