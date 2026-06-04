@extends($extendsLayout ?? 'cafeesquina.layouts.main')

@section('content')
<section class="section">
    <div class="container">
        @include('cafeesquina.components.section-heading', ['title' => 'Mis pedidos', 'subtitle' => ''])
        <div class="panel panel--flush mt-8" style="max-width:720px;margin:2rem auto 0">
            <table class="data-table">
                <thead><tr><th>Producto</th><th>Precio</th><th>Fecha</th></tr></thead>
                <tbody>
                @forelse($orders as $o)
                <tr><td>{{ $o['product_name'] }}</td><td>${{ number_format((float) $o['price'], 2) }}</td><td class="text-muted">{{ $o['created_at'] }}</td></tr>
                @empty
                <tr><td colspan="3" class="text-center text-muted" style="padding:2rem">Sin pedidos aún.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
