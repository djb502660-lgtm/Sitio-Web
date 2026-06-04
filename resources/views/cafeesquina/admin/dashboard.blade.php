@extends($extendsLayout ?? 'cafeesquina.layouts.admin')

@section('content')
@php
    $salesSim = (int) ($stats['orders'] * 12);
    $chartMonths = ['Ene','Feb','Mar','Abr','May','Jun'];
    $chartHeights = [40, 55, 35, 70, 50, 85];
@endphp

<div class="grid grid-4 mb-6">
    <div class="widget"><span class="widget__label">Productos</span><p class="widget__value">{{ $stats['products'] }}</p></div>
    <div class="widget"><span class="widget__label">Pedidos</span><p class="widget__value">{{ $stats['orders'] }}</p></div>
    <div class="widget"><span class="widget__label">Usuarios</span><p class="widget__value">{{ $stats['users'] }}</p></div>
    <div class="widget"><span class="widget__label">Ventas (demo)</span><p class="widget__value">${{ $salesSim }}</p></div>
</div>

<div class="grid grid-2 gap-4">
    <div class="panel">
        <h2 class="font-display" style="font-size:1.125rem;font-weight:700">Ventas mensuales</h2>
        <div class="chart-bars">
            @foreach($chartHeights as $i => $h)
            <div style="flex:1;text-align:center">
                <div class="chart-bar" style="height:{{ $h }}%"></div>
                <span class="text-sm text-muted">{{ $chartMonths[$i] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <div class="panel">
        <h2 class="font-display" style="font-size:1.125rem;font-weight:700">Top productos</h2>
        <ul class="mt-4">
            @forelse($topSelling as $t)
            <li class="flex justify-between py-2 text-sm" style="border-bottom:1px solid var(--border)">
                <span>{{ $t['product_name'] }}</span>
                <strong>{{ (int) $t['total'] }}</strong>
            </li>
            @empty
            <li class="text-muted text-sm">Sin datos</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="panel mt-6">
    <h2 class="font-display" style="font-size:1.125rem;font-weight:700">Pedidos recientes</h2>
    <table class="data-table mt-4">
        <thead><tr><th>Producto</th><th>Precio</th><th>Usuario</th></tr></thead>
        <tbody>
        @foreach($recentOrders as $o)
        <tr>
            <td>{{ $o['product_name'] }}</td>
            <td>${{ number_format((float) $o['price'], 2) }}</td>
            <td class="text-muted">{{ $o['username'] ?? 'invitado' }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
