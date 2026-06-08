@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
@php
    $heroImg = upload_url('hero.jpg');
    $waNum = preg_replace('/\D/', '', (string) config('cafeesquina.whatsapp_number'));
    $waGeneral = 'https://wa.me/' . $waNum . '?text=' . rawurlencode('Hola, quiero comprar este producto');
    $catIcons = ['fa-mug-hot', 'fa-cookie-bite', 'fa-glass-water', 'fa-bread-slice', 'fa-ice-cream', 'fa-leaf'];
@endphp

@if(!empty($promotions))
<div class="promo-strip">
    <i class="fas fa-bolt"></i>
    <strong>{{ $promotions[0]['title'] }}</strong> — {{ mb_substr($promotions[0]['description'] ?? '', 0, 60) }}
</div>
@endif

<section id="inicio" class="hero" style="background-image:url('{{ $heroImg }}')">
    <div class="container hero__content">
        <span class="hero__badge">Cafetería de especialidad</span>
        <h1 class="hero__title">CAFEESQUINA</h1>
        <p class="hero__tagline">{{ config('cafeesquina.tagline') }}</p>
        <p class="hero__desc">Menú digital, pedidos por WhatsApp y sabores que valen la pena.</p>
        <div class="hero__actions">
            <a href="{{ base_url('menu') }}" class="btn btn-gold"><i class="fas fa-utensils"></i> Ver menú</a>
            <a href="{{ $waGeneral }}" target="_blank" rel="noopener" class="btn btn-whatsapp"><i class="fab fa-whatsapp"></i> Pedir ahora</a>
        </div>
    </div>
</section>

<section id="categorias" class="section">
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Categorías', 'subtitle' => 'Elige lo que se te antoje'])
        <div class="grid grid-6">
            @foreach($categories as $i => $cat)
            <a href="{{ base_url('menu?categoria=' . $cat['id']) }}" class="cat-chip">
                <span class="cat-chip__icon"><i class="fas {{ $catIcons[$i % count($catIcons)] }}"></i></span>
                <span class="cat-chip__label">{{ $cat['name'] }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Destacados', 'subtitle' => 'Lo mejor de la casa'])
        <div class="grid grid-3">
            @foreach($featured as $product)
                @include('cafeesquina.components.product-card')
            @endforeach
        </div>
        <p class="text-center mt-8">
            <a href="{{ base_url('menu') }}" class="btn btn-primary">Ver catálogo completo</a>
        </p>
    </div>
</section>

@if(count($bestSellers ?? []) > 0)
<section class="section">
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Más pedidos', 'subtitle' => 'Favoritos de la comunidad'])
        <div class="grid grid-4">
            @foreach($bestSellers as $product)
                @include('cafeesquina.components.product-card')
            @endforeach
        </div>
    </div>
</section>
@endif

<section id="promociones" class="section section--alt">
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Promociones', 'subtitle' => 'Por tiempo limitado'])
        <div class="grid grid-2">
            @forelse($promotions as $promo)
            <article class="promo-card">
                <img src="{{ media_url($promo['image'] ?? null) }}" alt="" class="promo-card__img" loading="lazy">
                <div class="promo-card__body">
                    <h3 class="font-display" style="font-size:1.25rem;font-weight:700">{{ $promo['title'] }}</h3>
                    <p class="text-muted text-sm mt-2">{{ $promo['description'] }}</p>
                    <p class="text-sm mt-3" style="color:var(--gold);font-weight:600">Hasta {{ $promo['end_date'] }}</p>
                </div>
            </article>
            @empty
            <p class="text-center text-muted" style="grid-column:1/-1">Próximamente nuevas ofertas.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Clientes felices', 'subtitle' => ''])
        <div class="grid grid-3">
            @foreach([
                ['María G.', 'El mejor capuchino de la ciudad.'],
                ['Carlos R.', 'Pido por WhatsApp y llega rapidísimo.'],
                ['Ana P.', 'Ambiente perfecto para trabajar.'],
            ] as $t)
            <blockquote class="quote-card">
                <div class="quote-card__stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                <p class="quote-card__text">"{{ $t[1] }}"</p>
                <cite class="quote-card__author">— {{ $t[0] }}</cite>
            </blockquote>
            @endforeach
        </div>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="cta-box">
            <h2 class="cta-box__title">¿Antojo de café?</h2>
            <p class="mt-4" style="opacity:0.9">Escríbenos por WhatsApp y te tomamos el pedido al instante.</p>
            <a href="{{ $waGeneral }}" target="_blank" rel="noopener" class="btn btn-whatsapp mt-6"><i class="fab fa-whatsapp"></i> Comprar por WhatsApp</a>
        </div>
    </div>
</section>

<section id="ubicacion" class="section">
    <div class="container">
        <div class="grid grid-2" style="align-items:center;gap:2.5rem">
            <div>
                @include('cafeesquina.components.section-heading', ['title' => 'Ubicación', 'subtitle' => ''])
                <p class="mt-4"><i class="fas fa-map-marker-alt" style="color:var(--gold)"></i> {{ site_config('address') }}</p>
                <p class="mt-2 text-muted"><i class="fas fa-clock" style="color:var(--gold)"></i> {{ site_config('hours') }}</p>
            </div>
            <iframe class="w-full" style="border:0;border-radius:var(--radius-lg);min-height:280px;box-shadow:var(--shadow)" src="{{ site_config('map_embed') }}" loading="lazy" title="Mapa"></iframe>
        </div>
    </div>
</section>
@endsection
