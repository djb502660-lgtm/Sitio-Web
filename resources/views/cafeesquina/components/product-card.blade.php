@php
    $wa = whatsapp_order_url($product['name'], (float) $product['price']);
    $img = $product['image'] ?? 'https://images.unsplash.com/photo-1514432324607-09f969782a96?w=600';
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
        <div class="product-card__actions">
            <button type="button"
                    class="btn btn-primary"
                    data-add-to-cart
                    data-product-id="{{ (int) $product['id'] }}"
                    data-product-name="{{ $product['name'] }}"
                    data-product-price="{{ (float) $product['price'] }}"
                    data-product-image="{{ $img }}">
                <i class="fas fa-cart-plus"></i> Añadir al carrito
            </button>
            <a href="#"
               class="btn btn-whatsapp"
               data-whatsapp-order
               data-product-id="{{ (int) $product['id'] }}"
               data-wa-url="{{ $wa }}"
               data-log-url="{{ base_url('pedido/registrar') }}">
                <i class="fab fa-whatsapp"></i> Comprar por WhatsApp
            </a>
        </div>
        @endif
    </div>
</article>
