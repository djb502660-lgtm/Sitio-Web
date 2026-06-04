<section class="py-12">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-4xl font-bold text-coffee-dark">Menú Digital</h1>
        <p class="text-coffee-light mt-2">Explora nuestras bebidas, postres y especialidades.</p>

        <form method="get" action="<?= e(base_url('menu')) ?>" class="mt-8 flex flex-wrap gap-3">
            <input type="text" name="q" value="<?= e($search ?? '') ?>" placeholder="Buscar..." class="input-field max-w-xs">
            <select name="categoria" class="input-field max-w-xs">
                <option value="">Todas las categorías</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['id'] ?>" <?= ($currentCategory ?? 0) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-primary"><i class="fas fa-search"></i> Filtrar</button>
        </form>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mt-10">
            <?php foreach ($products as $p): $product = $p; require dirname(__DIR__) . '/partials/product-card.php'; endforeach; ?>
        </div>
        <?php if ($products === []): ?>
        <p class="text-center py-16 text-coffee-light">No hay productos con esos filtros.</p>
        <?php endif; ?>
    </div>
</section>
