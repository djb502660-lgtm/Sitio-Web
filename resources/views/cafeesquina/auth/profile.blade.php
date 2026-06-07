@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
<section class="section">
    <div class="container" style="max-width:480px">
        @include('cafeesquina.components.section-heading', ['title' => 'Mi perfil', 'subtitle' => !empty($_GET['compra']) ? 'Agrega tu teléfono para completar la compra' : ''])
        <form method="post" action="{{ base_url('perfil/actualizar') }}" class="auth-card mt-6" style="max-width:none">
            {!! ce_csrf_field() !!}
            @if(!empty($_GET['compra']))
            <input type="hidden" name="compra" value="1">
            @endif
            <div class="form-group"><label class="form-label">Nombre</label><input type="text" name="full_name" value="{{ $user['full_name'] ?? '' }}" class="input-field"></div>
            <div class="form-group"><label class="form-label">Teléfono</label><input type="tel" name="phone" value="{{ $user['phone'] ?? '' }}" class="input-field"></div>
            <div class="form-group"><label class="form-label">Correo</label><input type="email" name="email" value="{{ $user['email'] }}" required class="input-field"></div>
            <button type="submit" class="btn btn-primary btn-block">Guardar</button>
        </form>
        <p class="text-center mt-6"><a href="{{ base_url('pedidos') }}" style="color:var(--gold);font-weight:600">Ver pedidos</a></p>
    </div>
</section>
@endsection
