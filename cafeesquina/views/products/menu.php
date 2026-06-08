<section class="section" style="padding-top:2rem">
    <div class="container">
        <div class="section__head">
            <h1 class="section__title">Menú</h1>
            <p class="section__subtitle">Todos nuestros productos</p>
        </div>

        <form method="get" action="<?= e(base_url('menu')) ?>" class="filter-bar">
            <input type="search" name="q" value="<?= e($search ?? '') ?>" class="input-field" placeholder="Buscar..." aria-label="Buscar">
            <select name="categoria" class="input-field" aria-label="Categoría">
                <option value="">Todas</option>
                <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= ($currentCategory ?? 0) == $c['id'] ? 'selected' : '' ?>><?= e($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <div data-catalog-skeleton class="grid grid-4">
            <?php for ($i = 0; $i < 4; $i++): ?>
                <?php require dirname(__DIR__) . '/partials/skeleton-card.php'; ?>
            <?php endfor; ?>
        </div>

        <div data-catalog-grid class="grid grid-4" hidden>
            <?php if ($products === []): ?>
            <div class="panel text-center" style="grid-column:1/-1;padding:3rem">
                <p class="text-muted">No hay productos con esos filtros.</p>
                <a href="<?= e(base_url('menu')) ?>" class="btn btn-primary mt-6">Ver todo</a>
            </div>
            <?php else: ?>
                <?php foreach ($products as $p): $product = $p; require dirname(__DIR__) . '/partials/product-card.php'; endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>
