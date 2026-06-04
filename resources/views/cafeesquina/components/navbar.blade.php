@php
    $currentPath = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $basePath = trim((string) parse_url(base_url(''), PHP_URL_PATH), '/');
    if ($basePath && str_starts_with($currentPath, $basePath)) {
        $currentPath = trim(substr($currentPath, strlen($basePath)), '/');
    }
@endphp
<header class="site-nav" data-site-nav>
    <div class="container site-nav__inner">
        <a href="{{ base_url('') }}" class="brand">
            <span class="brand__icon"><i class="fas fa-mug-hot" aria-hidden="true"></i></span>
            CAFEESQUINA
        </a>

        <ul class="nav-links">
            <li><a href="{{ base_url('') }}#inicio">Inicio</a></li>
            <li><a href="{{ base_url('menu') }}" class="{{ $currentPath === 'menu' ? 'is-active' : '' }}">Menú</a></li>
            <li><a href="{{ base_url('') }}#categorias">Categorías</a></li>
            <li><a href="{{ base_url('') }}#ubicacion">Ubicación</a></li>
        </ul>

        <div class="nav-actions">
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Cambiar tema">
                <i class="fas fa-moon" data-theme-icon aria-hidden="true"></i>
            </button>
            @if(is_admin())
                <a href="{{ base_url('admin') }}" class="btn btn-outline btn-sm"><i class="fas fa-chart-line"></i> Admin</a>
            @endif
            @if(is_logged_in())
                <form method="post" action="{{ base_url('logout') }}" class="inline">
                    {!! ce_csrf_field() !!}
                    <button type="submit" class="btn btn-outline btn-sm">Salir</button>
                </form>
            @else
                <a href="{{ base_url('login') }}" class="btn btn-outline btn-sm">Entrar</a>
                <a href="{{ base_url('register') }}" class="btn btn-primary btn-sm">Registro</a>
            @endif
            <button type="button" class="btn-ghost nav-toggle" data-nav-toggle aria-expanded="false" aria-label="Abrir menú">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    <div class="nav-mobile-panel" data-nav-panel>
        <a href="{{ base_url('menu') }}">Menú</a>
        <a href="{{ base_url('login') }}">Entrar</a>
        <a href="{{ base_url('register') }}" class="btn btn-primary btn-block mt-4">Crear cuenta</a>
    </div>
</header>
