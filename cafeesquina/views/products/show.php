<?php $wa = whatsapp_order_url($product['name'], (float)$product['price']); ?>
<section class="py-12">
    <div class="max-w-4xl mx-auto px-4 grid md:grid-cols-2 gap-10">
        <img src="<?= e(media_url($product['image'] ?? null)) ?>" alt="<?= e($product['name']) ?>" class="rounded-2xl shadow-xl w-full object-cover">
        <div>
            <span class="text-gold text-sm font-semibold uppercase"><?= e($product['category_name']) ?></span>
            <h1 class="text-3xl font-bold mt-2"><?= e($product['name']) ?></h1>
            <p class="mt-4 text-coffee-light"><?= e($product['description']) ?></p>
            <p class="text-3xl font-bold mt-6 text-coffee-dark">$<?= e(number_format((float)$product['price'], 2)) ?></p>
            <p class="mt-2 text-sm">Estado: <?= $product['status'] === 'available' ? '✅ Disponible' : '❌ No disponible' ?></p>
            <a href="#" class="btn-whatsapp mt-8" data-whatsapp-order data-product-id="<?= (int)$product['id'] ?>" data-wa-url="<?= e($wa) ?>" data-log-url="<?= e(base_url('pedido/registrar')) ?>">
                <i class="fab fa-whatsapp"></i> Pedir por WhatsApp
            </a>
        </div>
    </div>
</section>
