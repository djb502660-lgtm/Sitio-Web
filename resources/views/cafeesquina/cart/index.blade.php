@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
<section class="section" style="padding-top:2rem" data-cart-page>
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Carrito', 'subtitle' => 'Revisa tu pedido antes de enviarlo'])

        <div data-cart-list class="cart-list"></div>

        <div data-cart-empty class="panel text-center" style="padding:3rem">
            <p class="text-muted"><i class="fas fa-shopping-cart" style="font-size:2rem;color:var(--gold)"></i></p>
            <p class="mt-4">Tu carrito está vacío.</p>
            <a href="{{ base_url('menu') }}" class="btn btn-primary mt-6">Ver menú</a>
        </div>

        <div data-cart-summary class="cart-summary hidden" hidden>
            <p class="cart-summary__total">Total: <span data-cart-total>$0.00</span></p>
            <div class="flex flex-wrap gap-3">
                <button type="button"
                        class="btn btn-whatsapp"
                        data-cart-checkout
                        data-checkout-url="{{ base_url('carrito/checkout') }}">
                    <i class="fab fa-whatsapp"></i> Pedir carrito por WhatsApp
                </button>
                <button type="button" class="btn btn-outline" data-cart-clear>Vaciar carrito</button>
                <a href="{{ base_url('menu') }}" class="btn btn-ghost">Seguir comprando</a>
            </div>
        </div>
    </div>
</section>
@endsection
