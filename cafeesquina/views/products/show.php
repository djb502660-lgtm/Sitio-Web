<?php
$wa = whatsapp_order_url($product['name'], (float) $product['price']);
$img = $product['image'] ?? 'https://images.unsplash.com/photo-1514432324607-09f969782a96?w=800';
$available = ($product['status'] ?? 'available') === 'available';
?>
<section class="section" style="padding-top:2rem">
    <div class="container">
        <p class="mb-6"><a href="<?= e(base_url('menu')) ?>" class="text-muted text-sm"><i class="fas fa-arrow-left"></i> Volver al menú</a></p>
        <div class="grid grid-2" style="gap:2.5rem;align-items:start">
            <div class="product-card" style="border:0">
                <div class="product-card__img-wrap" style="aspect-ratio:1">
                    <img src="<?= e($img) ?>" alt="<?= e($product['name']) ?>" class="product-card__img">
                </div>
            </div>
            <div>
                <span class="product-card__cat"><?= e($product['category_name']) ?></span>
                <h1 class="font-display" style="font-size:2.25rem;font-weight:700;margin-top:0.5rem"><?= e($product['name']) ?></h1>
                <p class="text-muted mt-4" style="font-size:1.0625rem;line-height:1.7"><?= e($product['description']) ?></p>
                <p class="product-card__price" style="font-size:2rem;margin-top:1.5rem">$<?= e(number_format((float) $product['price'], 2)) ?></p>
                <?php if ($available): ?>
                <div class="product-card__actions" style="max-width:320px">
                    <button type="button"
                            class="btn btn-primary"
                            data-add-to-cart
                            data-product-id="<?= (int) $product['id'] ?>"
                            data-product-name="<?= e($product['name']) ?>"
                            data-product-price="<?= (float) $product['price'] ?>"
                            data-product-image="<?= e($img) ?>">
                        <i class="fas fa-cart-plus"></i> Añadir al carrito
                    </button>
                    <a href="#"
                       class="btn btn-whatsapp"
                       data-whatsapp-order
                       data-product-id="<?= (int) $product['id'] ?>"
                       data-wa-url="<?= e($wa) ?>"
                       data-log-url="<?= e(base_url('pedido/registrar')) ?>">
                        <i class="fab fa-whatsapp"></i> Comprar por WhatsApp
                    </a>
                </div>
                <?php else: ?>
                <p class="mt-6 text-muted">Producto no disponible por el momento.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
