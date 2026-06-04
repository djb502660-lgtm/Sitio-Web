<section class="py-16 max-w-lg mx-auto px-4">
    <h1 class="text-3xl font-bold">Mi perfil</h1>
    <form method="post" action="<?= e(base_url('perfil/actualizar')) ?>" class="mt-8 bg-white p-8 rounded-2xl shadow space-y-4">
        <?= ce_csrf_field() ?>
        <input type="text" name="full_name" value="<?= e($user['full_name'] ?? '') ?>" class="input-field" placeholder="Nombre completo">
        <input type="tel" name="phone" value="<?= e($user['phone'] ?? '') ?>" class="input-field" placeholder="Teléfono">
        <input type="email" name="email" value="<?= e($user['email']) ?>" required class="input-field">
        <button type="submit" class="btn-primary w-full">Guardar cambios</button>
    </form>
    <a href="<?= e(base_url('pedidos')) ?>" class="inline-block mt-6 text-gold font-semibold"><i class="fas fa-receipt"></i> Ver historial de pedidos</a>
</section>
