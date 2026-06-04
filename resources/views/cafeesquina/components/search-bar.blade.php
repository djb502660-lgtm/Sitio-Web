@props(['action' => '', 'value' => '', 'placeholder' => 'Buscar productos...', 'instant' => false])
<form method="get" action="{{ $action }}" class="flex flex-wrap gap-3" role="search">
    <div class="relative flex-1 min-w-[12rem] max-w-md">
        <label for="catalog-search" class="sr-only">Buscar</label>
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-sm" style="color:var(--text-muted)" aria-hidden="true"></i>
        <input
            type="search"
            id="catalog-search"
            name="{{ $instant ? '' : 'q' }}"
            value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            class="input-field pl-11"
            @if($instant) data-instant-search @endif
            autocomplete="off"
        >
    </div>
    {{ $slot }}
</form>
