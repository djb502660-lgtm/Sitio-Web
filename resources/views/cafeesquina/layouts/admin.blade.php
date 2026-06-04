@php
    $flash = get_flash();
    $pageTitle = $title ?? 'Admin';
@endphp
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }} | Admin CAFEESQUINA</title>
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset_url('css/cafeesquina.css') }}?v=3">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>(function(){var t=localStorage.getItem('ce-theme');if(t)document.documentElement.dataset.theme=t;})();</script>
</head>
<body>
    @include('cafeesquina.components.loader')
    <div class="admin-layout">
        @include('cafeesquina.components.sidebar')
        <div class="admin-main">
            <header class="admin-topbar">
                <div>
                    <p class="text-sm text-muted">Panel administrativo</p>
                    <h1 class="font-display" style="font-size:1.25rem;font-weight:700">{{ $pageTitle }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="theme-toggle" data-theme-toggle aria-label="Tema"><i class="fas fa-moon" data-theme-icon></i></button>
                    <a href="{{ base_url('') }}" class="btn btn-outline btn-sm"><i class="fas fa-external-link-alt"></i> Sitio</a>
                </div>
            </header>
            @if($flash)
            <script>document.addEventListener('DOMContentLoaded',()=>showToast(@json($flash['type'] === 'success' ? 'success' : 'error'), @json($flash['message'])))</script>
            @endif
            <div class="admin-content">@yield('content')</div>
        </div>
    </div>
    <script src="{{ asset_url('js/app.js') }}?v=3" defer></script>
    @stack('scripts')
</body>
</html>
