@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
<div class="auth-wrap">
    <div class="auth-card">
        <h1 class="auth-card__title">Iniciar sesión</h1>
        <p class="auth-card__sub">Accede a tu cuenta CAFEESQUINA</p>
        <form method="post" action="{{ base_url('login/post') }}" class="mt-8" data-validate-form novalidate>
            {!! ce_csrf_field() !!}
            <div class="form-group">
                <label class="form-label" for="email">Correo</label>
                <input type="email" id="email" name="email" required class="input-field" data-validate="required|email" autocomplete="email">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Contraseña</label>
                <div class="input-wrap">
                    <input type="password" id="password" name="password" required class="input-field" data-validate="required" autocomplete="current-password">
                    <button type="button" class="btn-ghost" data-password-toggle="password" aria-label="Mostrar"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-muted mb-6" style="cursor:pointer">
                <input type="checkbox" name="remember" value="1"> Recordarme
            </label>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
        <p class="text-center text-sm text-muted mt-6">
            ¿Sin cuenta? <a href="{{ base_url('register') }}" style="color:var(--gold);font-weight:600">Regístrate</a>
        </p>
    </div>
</div>
@endsection
