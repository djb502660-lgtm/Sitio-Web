@php
    $flash = get_flash();
    $pageTitle = $title ?? config('cafeesquina.app_name');
    $metaDesc = $meta_description ?? 'CAFEESQUINA — Café artesanal, postres y pedidos por WhatsApp.';
    $canonical = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? base_url(''));
@endphp
<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="theme-color" content="#3d2314">
    <meta name="csrf-token" content="{{ ce_csrf_token() }}">
    <link rel="canonical" href="{{ $canonical }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset_url('css/cafeesquina.css') }}?v=5">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>(function(){var t=localStorage.getItem('ce-theme');if(t)document.documentElement.dataset.theme=t;})();</script>
</head>
<body>
    @include('cafeesquina.components.loader')
    @include('cafeesquina.components.navbar')

    @if($flash)
    <script>document.addEventListener('DOMContentLoaded',()=>showToast(@json($flash['type'] === 'success' ? 'success' : 'error'), @json($flash['message'])))</script>
    @endif

    <main id="main-content" class="page-main">@yield('content')</main>

    @include('cafeesquina.components.footer')
    <script src="{{ asset_url('js/app.js') }}?v=5" defer></script>
    @stack('scripts')
</body>
</html>
