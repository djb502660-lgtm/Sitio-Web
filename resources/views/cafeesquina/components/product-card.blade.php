@php
    $wa = whatsapp_order_url($product['name'], (float) $product['price']);
    // Usar imagen local si existe, sino placeholder
    $defaultImg = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="600" height="400"%3E%3Crect fill="%23E8D4C8" width="600" height="400"/%3E%3Ctext x="50%25" y="50%25" dominant-baseline="middle" text-anchor="middle" font-family="system-ui" font-size="24" fill="%235D4037"%3ECafé%3C/text%3E%3C/svg%3E';
    $img = $product['image'] ?? $defaultImg;
    
    // Si la imagen es una ruta relativa (no URL completa), prefixar con base_url
    if (!empty($img) && strpos($img, 'http') !== 0 && strpos($img, 'data:') !== 0) {
        $img = base_url($img);
    }
    
    $desc = (string) ($product['description'] ?? '');
    $available = ($product['status'] ?? 'available') === 'available';
@endphp
<article class="product-card" data-product-card data-search-text="{{ strtolower(($product['name'] ?? '') . ' ' . ($product['category_name'] ?? '')) }}">
    <a href="{{ base_url('producto?id=' . (int) $product['id']) }}" class="product-card__img-wrap">
        <img src="{{ $img }}" alt="{{ $product['name'] }}" class="product-card__img" loading="lazy" decoding="async">
        <span class="product-card__badge {{ $available ? 'product-card__badge--ok' : 'product-card__badge--no' }}">
            {{ $available ? 'Disponible' : 'Agotado' }}
        </span>
    </a>
    <div class="product-card__body">
        <span class="product-card__cat">{{ $product['category_name'] ?? '' }}</span>
        <a href="{{ base_url('producto?id=' . (int) $product['id']) }}" class="product-card__name">{{ $product['name'] }}</a>
        @if($desc !== '')
        <p class="product-card__desc">{{ $desc }}</p>
        @endif
        <p class="product-card__price">${{ number_format((float) $product['price'], 2) }}</p>
        @if($available)
        <a href="#"
           class="btn btn-whatsapp product-card__btn"
           data-whatsapp-order
           data-product-id="{{ (int) $product['id'] }}"
           data-wa-url="{{ $wa }}"
           data-log-url="{{ base_url('pedido/registrar') }}">
            <i class="fab fa-whatsapp"></i> Comprar por WhatsApp
        </a>
        @endif
    </div>
</article>
