<section class="py-16 max-w-3xl mx-auto px-4">
    <h1 class="text-3xl font-bold">Mis pedidos</h1>
    <div class="mt-8 bg-white rounded-2xl shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-cream"><tr><th class="p-3 text-left">Producto</th><th>Precio</th><th>Fecha</th></tr></thead>
            <tbody>
            <?php foreach ($orders as $o): ?>
            <tr class="border-t"><td class="p-3"><?= e($o['product_name']) ?></td><td class="p-3">$<?= e(number_format((float)$o['price'],2)) ?></td><td class="p-3"><?= e($o['created_at']) ?></td></tr>
            <?php endforeach; ?>
            <?php if ($orders === []): ?><tr><td colspan="3" class="p-8 text-center text-coffee-light">Aún no tienes pedidos registrados.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
