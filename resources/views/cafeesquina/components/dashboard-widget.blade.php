@props(['label', 'value', 'icon' => 'fa-chart-line', 'trend' => null, 'color' => 'gold'])
<div class="widget-card">
    <div class="flex items-start justify-between gap-3">
        <div class="widget-icon"><i class="fas {{ $icon }}" aria-hidden="true"></i></div>
        @if($trend)
        <span class="text-xs font-semibold px-2 py-1 rounded-full" style="background:rgba(22,163,74,0.12);color:#16a34a">{{ $trend }}</span>
        @endif
    </div>
    <p class="mt-4 text-sm font-medium" style="color:var(--text-muted)">{{ $label }}</p>
    <p class="text-3xl font-bold mt-1 font-display" style="color:var(--text)">{{ $value }}</p>
</div>
