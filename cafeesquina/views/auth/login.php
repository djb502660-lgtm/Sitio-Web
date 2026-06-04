<section class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <h1 class="text-2xl font-bold text-center"><i class="fas fa-mug-hot text-gold"></i> Iniciar sesión</h1>
        <form method="post" action="<?= e(base_url('login/post')) ?>" class="mt-8 space-y-4">
            <?= ce_csrf_field() ?>
            <input type="email" name="email" required class="input-field" placeholder="Correo">
            <input type="password" name="password" required class="input-field" placeholder="Contraseña">
            <button type="submit" class="btn-primary w-full">Entrar</button>
        </form>
        <p class="text-center text-sm mt-6">¿Nuevo? <a href="<?= e(base_url('register')) ?>" class="text-gold font-semibold">Crear cuenta</a></p>
    </div>
</section>
