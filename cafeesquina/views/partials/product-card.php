<?php
/** @var array $product */
$wa = whatsapp_order_url($product['name'], (float) $product['price']);
$img = media_url($product['image'] ?? null, asset_url('images/placeholder.jpg'));
?>
<article class="card-cafe flex flex-col reveal">
    <img src="<?= e($img) ?>" alt="<?= e($product['name']) ?>" class="h-48 w-full object-cover" loading="lazy">
    <div class="p-5 flex flex-col flex-1">
        <span class="text-xs uppercase tracking-wide text-gold font-semibold"><?= e($product['category_name'] ?? '') ?></span>
        <h3 class="text-lg font-bold mt-1"><?= e($product['name']) ?></h3>
        <p class="text-sm text-coffee-light mt-2 flex-1"><?= e(mb_substr($product['description'], 0, 90)) ?>…</p>
        <p class="text-xl font-bold text-coffee-dark mt-3">$<?= e(number_format((float) $product['price'], 2)) ?></p>
        <a href="#"
           class="btn-whatsapp mt-4 w-full justify-center text-sm"
           data-whatsapp-order
           data-product-id="<?= (int) $product['id'] ?>"
           data-wa-url="<?= e($wa) ?>"
           data-log-url="<?= e(base_url('pedido/registrar')) ?>">
            <i class="fab fa-whatsapp"></i> Pedir por WhatsApp
        </a>
    </div>
</article>
