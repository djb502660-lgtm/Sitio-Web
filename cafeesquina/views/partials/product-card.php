<?php
/** @var array $product */
$wa = whatsapp_order_url($product['name'], (float) $product['price']);
$defaultImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="600" height="400"%3E%3Crect fill="%23E8D4C8" width="600" height="400"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="system-ui" font-size="24" fill="%235D4037"%3ECafé%3C/text%3E%3C/svg%3E';
$img = function_exists('media_url') ? media_url($product['image'] ?? null, $defaultImg) : ($product['image'] ?? $defaultImg);
$desc = (string) ($product['description'] ?? '');
$available = ($product['status'] ?? 'available') === 'available';
$searchText = strtolower(($product['name'] ?? '') . ' ' . ($product['category_name'] ?? ''));
?>
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
        <div class="product-card__actions">
            <button type="button"
                    class="btn btn-primary"
                    data-add-to-cart
                    data-product-id="<?= (int) $product['id'] ?>"
                    data-product-name="<?= e($product['name']) ?>"
                    data-product-price="<?= (float) $product['price'] ?>"
                    data-product-image="<?= e($img) ?>">
                <i class="fas fa-cart-plus"></i> Añadir al carrito
            </button>
            <?php if (is_logged_in()): ?>
            <a href="#"
               class="btn btn-whatsapp"
               data-whatsapp-order
               data-product-id="<?= (int) $product['id'] ?>"
               data-wa-url="<?= e($wa) ?>"
               data-log-url="<?= e(base_url('pedido/registrar')) ?>">
                <i class="fab fa-whatsapp"></i> Comprar por WhatsApp
            </a>
            <?php else: ?>
            <a href="<?= e(base_url('login?compra=1')) ?>" class="btn btn-whatsapp">
                <i class="fas fa-sign-in-alt"></i> Iniciar sesión para comprar
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</article>
