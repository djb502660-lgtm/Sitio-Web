<footer class="site-footer">
    <div class="container site-footer__grid">
        <div>
            <h3 class="font-display" style="font-size:1.5rem;font-weight:700;margin-bottom:0.5rem">CAFEESQUINA</h3>
            <p style="opacity:0.9;max-width:280px">{{ config('cafeesquina.tagline') }}</p>
            <div class="flex gap-3 mt-6" style="font-size:1.25rem">
                <a href="{{ config('cafeesquina.social.instagram') }}" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                <a href="{{ config('cafeesquina.social.facebook') }}" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="https://wa.me/{{ preg_replace('/\D/', '', (string) config('cafeesquina.whatsapp_number')) }}" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
        <div>
            <h4 style="font-weight:600;margin-bottom:0.75rem">Enlaces</h4>
            <p><a href="{{ base_url('menu') }}">Menú</a></p>
            <p class="mt-2"><a href="{{ base_url('login') }}">Iniciar sesión</a></p>
        </div>
        <div>
            <h4 style="font-weight:600;margin-bottom:0.75rem">Horario</h4>
            <p style="opacity:0.9;font-size:0.875rem">{{ site_config('hours') }}</p>
        </div>
    </div>
    <div class="container site-footer__bottom">&copy; {{ date('Y') }} CAFEESQUINA</div>
</footer>
