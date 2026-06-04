@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-card__title">Crear cuenta</h1>
        <p class="auth-card__sub">Registro rápido y seguro</p>
        <form method="post" action="{{ base_url('register/post') }}" class="mt-8" data-validate-form novalidate>
            {!! ce_csrf_field() !!}
            <div class="form-group">
                <label class="form-label" for="full_name">Nombre completo</label>
                <input type="text" id="full_name" name="full_name" required class="input-field" data-validate="required">
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Correo</label>
                <input type="email" id="email" name="email" required class="input-field" data-validate="required|email">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" required class="input-field" data-validate="required|min:8" data-password-strength>
                    <button type="button" class="btn-ghost" data-password-toggle="password"><i class="fas fa-eye"></i></button>
                </div>
                <div class="pwd-strength"><div class="pwd-strength-bar" data-pwd-bar></div></div>
                <p class="input-hint" data-pwd-label>Mínimo 8 caracteres</p>
            </div>
            <div class="form-group">
                <label class="form-label" for="password_confirm">Confirmar</label>
                <input type="password" id="password_confirm" name="password_confirm" required class="input-field" data-validate="required|match" data-confirm="password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Registrarme</button>
        </form>
        <p class="text-center text-sm text-muted mt-6">
            <a href="{{ base_url('login') }}" style="color:var(--gold);font-weight:600">Ya tengo cuenta</a>
        </p>
    </div>
</div>
@endsection
