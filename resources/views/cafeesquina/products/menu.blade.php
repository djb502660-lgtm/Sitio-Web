@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
<section class="section" style="padding-top:2rem">
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Menú', 'subtitle' => 'Todos nuestros productos'])

        <form method="get" action="{{ base_url('menu') }}" class="filter-bar">
            <input type="search" name="q" value="{{ $search ?? '' }}" class="input-field" placeholder="Buscar..." aria-label="Buscar">
            <select name="categoria" class="input-field" aria-label="Categoría">
                <option value="">Todas</option>
                @foreach($categories as $c)
                <option value="{{ $c['id'] }}" @selected(($currentCategory ?? 0) == $c['id'])>{{ $c['name'] }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <div data-catalog-skeleton class="grid grid-4">
            @for($i = 0; $i < 4; $i++)
                @include('cafeesquina.components.skeleton-card')
            @endfor
        </div>

        <div data-catalog-grid class="grid grid-4" hidden>
            @forelse($products as $product)
                @include('cafeesquina.components.product-card')
            @empty
            <div class="panel text-center" style="grid-column:1/-1;padding:3rem">
                <p class="text-muted">No hay productos con esos filtros.</p>
                <a href="{{ base_url('menu') }}" class="btn btn-primary mt-6">Ver todo</a>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
