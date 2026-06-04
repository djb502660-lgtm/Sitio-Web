<h1 class="text-2xl font-bold text-coffee-dark">Dashboard</h1>
<div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4 mt-6">
    <?php foreach ([
        ['Productos', $stats['products'], 'fa-mug-hot'],
        ['Categorías', $stats['categories'], 'fa-tags'],
        ['Usuarios', $stats['users'], 'fa-users'],
        ['Pedidos', $stats['orders'], 'fa-receipt'],
        ['Promos activas', $stats['promotions'], 'fa-percent'],
    ] as [$label, $val, $icon]): ?>
    <div class="stat-card"><i class="fas <?= $icon ?> opacity-80"></i><p class="text-sm mt-2 opacity-90"><?= e($label) ?></p><p class="text-3xl font-bold"><?= (int)$val ?></p></div>
    <?php endforeach; ?>
</div>
<div class="grid lg:grid-cols-2 gap-8 mt-10">
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-bold">Más pedidos</h2>
        <ul class="mt-4 space-y-2 text-sm">
            <?php foreach ($topSelling as $t): ?>
            <li class="flex justify-between"><span><?= e($t['product_name']) ?></span><strong><?= (int)$t['total'] ?></strong></li>
            <?php endforeach; ?>
            <?php if ($topSelling === []): ?><li class="text-gray-400">Sin datos aún</li><?php endif; ?>
        </ul>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="font-bold">Pedidos recientes</h2>
        <ul class="mt-4 space-y-2 text-sm">
            <?php foreach ($recentOrders as $o): ?>
            <li><?= e($o['product_name']) ?> — $<?= e(number_format((float)$o['price'],2)) ?> <span class="text-gray-400">(<?= e($o['username'] ?? 'invitado') ?>)</span></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
