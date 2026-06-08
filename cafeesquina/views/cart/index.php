<section class="section" style="padding-top:2rem" data-cart-page>
    <div class="container">
        <div class="section__head">
            <h1 class="section__title">Carrito</h1>
            <p class="section__subtitle">Revisa tu pedido antes de enviarlo</p>
        </div>

        <div data-cart-list class="cart-list"></div>

        <div data-cart-empty class="panel text-center" style="padding:3rem">
            <p class="text-muted"><i class="fas fa-shopping-cart" style="font-size:2rem;color:var(--gold)"></i></p>
            <p class="mt-4">Tu carrito está vacío.</p>
            <a href="<?= e(base_url('menu')) ?>" class="btn btn-primary mt-6">Ver menú</a>
        </div>

        <div data-cart-summary class="cart-summary hidden" hidden>
            <p class="cart-summary__total">Total: <span data-cart-total>$0.00</span></p>
            <div class="flex flex-wrap gap-3">
                <button type="button"
                        class="btn btn-whatsapp"
                        data-cart-checkout
                        data-checkout-url="<?= e(base_url('carrito/checkout')) ?>">
                    <i class="fab fa-whatsapp"></i> Pedir carrito por WhatsApp
                </button>
                <button type="button" class="btn btn-outline" data-cart-clear>Vaciar carrito</button>
                <a href="<?= e(base_url('menu')) ?>" class="btn btn-ghost">Seguir comprando</a>
            </div>
        </div>
    </div>
</section>
