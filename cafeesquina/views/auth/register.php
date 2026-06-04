<section class="min-h-[70vh] flex items-center justify-center px-4 py-16">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
        <h1 class="text-2xl font-bold text-center">Crear cuenta</h1>
        <form method="post" action="<?= e(base_url('register/post')) ?>" class="mt-8 space-y-4">
            <?= ce_csrf_field() ?>
            <input type="text" name="username" required pattern="[a-zA-Z0-9_]{3,50}" class="input-field" placeholder="Usuario">
            <input type="email" name="email" required class="input-field" placeholder="Correo">
            <input type="password" name="password" required minlength="8" class="input-field" placeholder="Contraseña">
            <input type="password" name="password_confirm" required minlength="8" class="input-field" placeholder="Confirmar">
            <button type="submit" class="btn-primary w-full">Registrarme</button>
        </form>
    </div>
</section>
