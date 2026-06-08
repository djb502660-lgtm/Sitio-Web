<?php
/** @var array $product */
$wa = whatsapp_order_url($product['name'], (float) $product['price']);
$img = $product['image'] ?? 'https://images.unsplash.com/photo-1514432324607-09f969782a96?w=600';
$desc = (string) ($product['description'] ?? '');
$available = ($product['status'] ?? 'available') === 'available';
$searchText = strtolower(($product['name'] ?? '') . ' ' . ($product['category_name'] ?? ''));
?>
<<<<<<< Updated upstream
<article class="card-cafe flex flex-col reveal">
    <img src="<?= e($img) ?>" alt="<?= e($product['name']) ?>" class="h-48 w-full object-cover" loading="lazy">
    <div class="p-5 flex flex-col flex-1">
        <span class="text-xs uppercase tracking-wide text-gold font-semibold"><?= e($product['category_name'] ?? '') ?></span>
        <h3 class="text-lg font-bold mt-1"><?= e($product['name']) ?></h3>
        <p class="text-sm text-coffee-light mt-2 flex-1"><?= e(mb_substr($product['description'], 0, 90)) ?>…</p>
        <p class="text-xl font-bold text-coffee-dark mt-3">$<?= e(number_format((float) $product['price'], 2)) ?></p>
        <?php if (is_logged_in()): ?>
=======
<article class="product-card" data-product-card data-search-text="<?= e($searchText) ?>">
    <a href="<?= e(base_url('producto?id=' . (int) $product['id'])) ?>" class="product-card__img-wrap">
        <img src="<?= e($img) ?>" alt="<?= e($product['name']) ?>" class="product-card__img" loading="lazy" decoding="async">
        <span class="product-card__badge <?= $available ? 'product-card__badge--ok' : 'product-card__badge--no' ?>">
            <?= $available ? 'Disponible' : 'Agotado' ?>
        </span>
    </a>
    <div class="product-card__body">
        <span class="product-card__cat"><?= e($product['category_name'] ?? '') ?></span>
        <a href="<?= e(base_url('producto?id=' . (int) $product['id'])) ?>" class="product-card__name"><?= e($product['name']) ?></a>
        <?php if ($desc !== ''): ?>
        <p class="product-card__desc"><?= e($desc) ?></p>
        <?php endif; ?>
        <p class="product-card__price">$<?= e(number_format((float) $product['price'], 2)) ?></p>
        <?php if ($available): ?>
>>>>>>> Stashed changes
        <a href="#"
           class="btn btn-whatsapp product-card__btn"
           data-whatsapp-order
           data-product-id="<?= (int) $product['id'] ?>"
           data-wa-url="<?= e($wa) ?>"
           data-log-url="<?= e(base_url('pedido/registrar')) ?>">
            <i class="fab fa-whatsapp"></i> Comprar por WhatsApp
        </a>
<<<<<<< Updated upstream
        <?php else: ?>
        <a href="<?= e(base_url('login?compra=1')) ?>" class="btn-whatsapp mt-4 w-full justify-center text-sm">
            <i class="fas fa-sign-in-alt"></i> Iniciar sesión para comprar
        </a>
=======
>>>>>>> Stashed changes
        <?php endif; ?>
    </div>
</article>
