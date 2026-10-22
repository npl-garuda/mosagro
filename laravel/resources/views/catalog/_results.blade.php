@php
    $emptyMessage = $emptyMessage ?? 'Товары скоро появятся.';
@endphp
<div class="prod-list">
    @forelse($products as $product)
        @include('catalog._row', ['product' => $product])
    @empty
        <p class="catalog-empty">{{ $emptyMessage }}</p>
    @endforelse
</div>
@include('catalog._pager')
