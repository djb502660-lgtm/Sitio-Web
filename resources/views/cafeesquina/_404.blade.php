@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
<div class="auth-wrap">
    <div class="text-center">
        <p class="font-display" style="font-size:6rem;font-weight:700;color:var(--gold);line-height:1">404</p>
        <h1 class="mt-4" style="font-size:1.5rem;font-weight:700">Página no encontrada</h1>
        <p class="text-muted mt-2">La ruta no existe.</p>
        <a href="{{ base_url('') }}" class="btn btn-primary mt-8">Inicio</a>
    </div>
</div>
@endsection
