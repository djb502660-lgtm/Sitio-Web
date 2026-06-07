@php
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
    $base = trim((string) parse_url(base_url(''), PHP_URL_PATH), '/');
    if ($base && str_starts_with($path, $base)) {
        $path = trim(substr($path, strlen($base)), '/');
    }
    $links = [
        ['admin', 'Dashboard', 'fa-chart-pie'],
        ['admin/productos', 'Productos', 'fa-mug-hot'],
        ['admin/categorias', 'Categorías', 'fa-tags'],
        ['admin/promociones', 'Promociones', 'fa-percent'],
        ['admin/usuarios', 'Usuarios', 'fa-users'],
        ['admin/ubicacion', 'Ubicación', 'fa-map-marker-alt'],
    ];
@endphp
<aside class="admin-sidebar" aria-label="Panel administrativo">
    <div class="mb-8">
        <a href="{{ base_url('admin') }}" class="font-display text-xl font-bold flex items-center gap-2">
            <i class="fas fa-coffee text-[var(--gold-light)]"></i> Admin
        </a>
        <p class="text-xs opacity-70 mt-1">CAFEESQUINA SaaS</p>
    </div>
    <nav class="flex-1 space-y-1">
        @foreach($links as [$href, $label, $icon])
        <a href="{{ base_url($href) }}" class="{{ $path === $href || str_starts_with($path, $href . '/') ? 'is-active' : '' }}">
            <i class="fas {{ $icon }} w-5 text-center opacity-80" aria-hidden="true"></i>
            {{ $label }}
        </a>
        @endforeach
        <hr class="border-white/15 my-4">
        <a href="{{ base_url('') }}"><i class="fas fa-store w-5 text-center opacity-80"></i> Ver sitio</a>
        <a href="{{ base_url('perfil') }}"><i class="fas fa-cog w-5 text-center opacity-80"></i> Configuración</a>
        <form method="post" action="{{ base_url('logout') }}" class="block">
            {!! ce_csrf_field() !!}
            <button type="submit" class="w-full text-left flex items-center gap-2 hover:text-gold bg-transparent border-0 p-0 cursor-pointer text-inherit font-inherit">
                <i class="fas fa-sign-out-alt w-5 text-center opacity-80"></i> Cerrar sesión
            </button>
        </form>
    </nav>
</aside>
