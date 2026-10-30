@php
    $searchQuery = $searchQuery ?? '';
    $activeFilter = $activeFilter ?? null;
    $categories = $categories ?? collect();
@endphp
<form class="catalog-search-form" action="{{ route('catalog.index') }}" method="get" role="search">
    <label class="visually-hidden" for="catalog-q">Поиск по каталогу</label>
    <input class="catalog-search" id="catalog-q" type="search" name="q" value="{{ $searchQuery }}" placeholder="Поиск по каталогу" autocomplete="off">
</form>
<nav class="catalog-filters" aria-label="Фильтры каталога">
    <a class="catalog-chip {{ $activeFilter === null ? 'is-active' : '' }}" href="{{ route('catalog.index') }}">Все товары</a>
    @foreach($categories as $filterCategory)
        <a class="catalog-chip {{ $activeFilter === $filterCategory->slug ? 'is-active' : '' }}" href="{{ route('catalog.category', $filterCategory->slug) }}">{{ $filterCategory->name }}<span class="catalog-chip-count">{{ $filterCategory->products_count }}</span></a>
    @endforeach
</nav>
