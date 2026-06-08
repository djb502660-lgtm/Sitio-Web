<?php
$heroImg = upload_url('hero.jpg');
$waGeneral = 'https://wa.me/' . preg_replace('/\D/', '', (string) app_config('whatsapp_number')) . '?text=' . rawurlencode('Hola CAFEESQUINA, quisiera hacer un pedido.');
?>
<!-- Hero -->
<section id="inicio" class="relative min-h-[85vh] flex items-center bg-cover bg-center" style="background-image:url('<?= e($heroImg) ?>')">
    <div class="hero-overlay absolute inset-0"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 py-24 text-cream text-center md:text-left">
        <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight reveal">CAFEESQUINA</h1>
        <p class="text-xl md:text-2xl mt-4 text-gold font-light reveal"><?= e((string) app_config('tagline')) ?></p>
        <div class="mt-8 flex flex-wrap gap-4 justify-center md:justify-start reveal">
            <a href="<?= e(base_url('menu')) ?>" class="btn-gold"><i class="fas fa-utensils"></i> Ver Menú</a>
            <a href="<?= e($waGeneral) ?>" target="_blank" class="btn-whatsapp"><i class="fab fa-whatsapp"></i> Pedir por WhatsApp</a>
        </div>
    </div>
</section>

<!-- Sobre nosotros -->
<section id="nosotros" class="py-20 bg-white reveal">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-coffee-dark text-center">Sobre Nosotros</h2>
        <p class="text-center text-coffee-light mt-2 max-w-2xl mx-auto">Una cafetería moderna, elegante y acogedora.</p>
        <div class="grid md:grid-cols-3 gap-8 mt-12">
            <div class="p-6 rounded-2xl bg-cream border border-gold/30">
                <h3 class="font-bold text-gold"><i class="fas fa-book-open"></i> Historia</h3>
                <p class="mt-3 text-sm">Nacimos con la pasión por el café de especialidad y el servicio cálido que convierte cada visita en un ritual.</p>
            </div>
            <div class="p-6 rounded-2xl bg-cream border border-gold/30">
                <h3 class="font-bold text-gold"><i class="fas fa-bullseye"></i> Misión</h3>
                <p class="mt-3 text-sm">Ofrecer experiencias gastronómicas memorables con ingredientes de calidad y atención personalizada.</p>
            </div>
            <div class="p-6 rounded-2xl bg-cream border border-gold/30">
                <h3 class="font-bold text-gold"><i class="fas fa-eye"></i> Visión</h3>
                <p class="mt-3 text-sm">Ser la cafetería referente de la ciudad en innovación, sabor y comunidad.</p>
            </div>
        </div>
        <div class="mt-8 flex flex-wrap justify-center gap-4 text-sm">
            <?php foreach (['Calidad', 'Pasión', 'Sostenibilidad', 'Hospitalidad'] as $v): ?>
                <span class="px-4 py-2 rounded-full bg-coffee-dark text-cream"><?= e($v) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Destacados -->
<section class="py-20 reveal">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center">Productos Destacados</h2>
        <?php if (count($featured) > 3): ?>
        <div class="mt-10 overflow-hidden" data-carousel>
            <div class="carousel-track">
                <?php foreach (array_chunk($featured, 3) as $chunk): ?>
                <div class="carousel-slide min-w-full grid md:grid-cols-3 gap-6 px-1">
                    <?php foreach ($chunk as $p): $product = $p; require dirname(__DIR__) . '/partials/product-card.php'; endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="grid md:grid-cols-3 gap-8 mt-10">
            <?php foreach ($featured as $p): $product = $p; require dirname(__DIR__) . '/partials/product-card.php'; endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Promociones -->
<section id="promociones" class="py-20 bg-coffee-dark text-cream reveal">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gold">Promociones</h2>
        <div class="grid md:grid-cols-2 gap-8 mt-10">
            <?php foreach ($promotions as $promo): ?>
            <div class="card-cafe text-coffee-dark overflow-hidden flex flex-col md:flex-row">
                <img src="<?= e($promo['image']) ?>" alt="" class="md:w-1/2 h-48 object-cover">
                <div class="p-6">
                    <h3 class="font-bold text-lg"><?= e($promo['title']) ?></h3>
                    <p class="text-sm mt-2"><?= e($promo['description']) ?></p>
                    <p class="text-xs mt-3 text-coffee-light">Válido hasta <?= e($promo['end_date']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if ($promotions === []): ?>
            <p class="col-span-2 text-center text-cream/70">Próximamente nuevas ofertas.</p>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Testimonios -->
<section class="py-20 bg-white reveal">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center">Testimonios</h2>
        <div class="grid md:grid-cols-3 gap-8 mt-10">
            <?php
            $tests = [
                ['María G.', 'El mejor capuchino de la ciudad. Ambiente increíble.', 5],
                ['Carlos R.', 'Los frappés son adictivos. Siempre pido por WhatsApp.', 5],
                ['Ana P.', 'Desayuno Esquina: perfecto para empezar el día.', 5],
            ];
            foreach ($tests as $t): ?>
            <blockquote class="p-6 rounded-2xl bg-cream shadow">
                <div class="text-gold"><?= str_repeat('<i class="fas fa-star"></i>', $t[2]) ?></div>
                <p class="mt-3 text-sm italic">"<?= e($t[1]) ?>"</p>
                <cite class="block mt-4 font-semibold text-sm">— <?= e($t[0]) ?></cite>
            </blockquote>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Ubicación -->
<section id="ubicacion" class="py-20 reveal">
    <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-10">
        <div>
            <h2 class="text-3xl font-bold">Ubicación</h2>
            <p class="mt-4 flex items-start gap-2"><i class="fas fa-map-marker-alt text-gold mt-1"></i> <?= e((string) site_config('address')) ?></p>
            <p class="mt-2 flex items-start gap-2"><i class="fas fa-clock text-gold mt-1"></i> <?= e((string) site_config('hours')) ?></p>
            <div class="mt-6 flex gap-4">
                <a href="<?= e(app_config('social')['instagram']) ?>" class="text-2xl hover:text-gold"><i class="fab fa-instagram"></i></a>
                <a href="<?= e(app_config('social')['facebook']) ?>" class="text-2xl hover:text-gold"><i class="fab fa-facebook"></i></a>
            </div>
        </div>
        <iframe class="w-full h-72 rounded-2xl shadow-lg border-0" src="<?= e((string) site_config('map_embed')) ?>" loading="lazy" title="Mapa CAFEESQUINA"></iframe>
    </div>
</section>
