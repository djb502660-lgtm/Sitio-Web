@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
@php
    $wa = whatsapp_order_url($product['name'], (float) $product['price']);
    $defaultImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="800" height="600"%3E%3Crect fill="%23E8D4C8" width="800" height="600"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="system-ui" font-size="32" fill="%235D4037"%3ECafé Premium%3C/text%3E%3C/svg%3E';
    $img = media_url($product['image'] ?? null, $defaultImg);
    $available = ($product['status'] ?? 'available') === 'available';
@endphp
<section class="section" style="padding-top:2rem">
    <div class="container">
        <p class="mb-6"><a href="{{ base_url('menu') }}" class="text-muted text-sm"><i class="fas fa-arrow-left"></i> Volver al menú</a></p>
        <div class="grid grid-2" style="gap:2.5rem;align-items:start">
            <div class="product-card" style="border:0">
                <div class="product-card__img-wrap" style="aspect-ratio:1">
                    <img src="{{ $img }}" alt="{{ $product['name'] }}" class="product-card__img">
                </div>
            </div>
            <div>
                <span class="product-card__cat">{{ $product['category_name'] }}</span>
                <h1 class="font-display" style="font-size:2.25rem;font-weight:700;margin-top:0.5rem">{{ $product['name'] }}</h1>
                <p class="text-muted mt-4" style="font-size:1.0625rem;line-height:1.7">{{ $product['description'] }}</p>
                <p class="product-card__price" style="font-size:2rem;margin-top:1.5rem">${{ number_format((float) $product['price'], 2) }}</p>
                @if($available)
                <a href="#"
                   class="btn btn-whatsapp mt-6"
                   data-whatsapp-order
                   data-product-id="{{ (int) $product['id'] }}"
                   data-wa-url="{{ $wa }}"
                   data-log-url="{{ base_url('pedido/registrar') }}">
                    <i class="fab fa-whatsapp"></i> Comprar por WhatsApp
                </a>
                @else
                <p class="mt-6 text-muted">Producto no disponible por el momento.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
