@props(['current' => 1, 'total' => 1])
@if($total > 1)
<nav class="pagination mt-10" aria-label="Paginación">
    @for($i = 1; $i <= $total; $i++)
        @if($i === $current)
            <span class="is-active" aria-current="page">{{ $i }}</span>
        @else
            <a href="?page={{ $i }}">{{ $i }}</a>
        @endif
    @endfor
</nav>
@endif
