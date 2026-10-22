@php
    $searchQuery = $searchQuery ?? '';
    $activeFilter = $activeFilter ?? null;
    $categories = $categories ?? collect();
    $formAction = isset($category)
        ? route('catalog.category', $category->slug)
        : route('catalog.index');
@endphp
<form class="catalog-search-form" action="{{ $formAction }}" method="get" role="search">
    <label class="visually-hidden" for="catalog-q">Поиск по названию</label>
    <input class="catalog-search" id="catalog-q" type="search" name="q" value="{{ $searchQuery }}" placeholder="Поиск по названию" autocomplete="off">
</form>
<nav class="catalog-filters" aria-label="Фильтры каталога">
    <a class="catalog-chip {{ $activeFilter === null ? 'is-active' : '' }}" href="{{ route('catalog.index') }}">Все товары</a>
    @foreach($categories as $filterCategory)
        <a class="catalog-chip {{ $activeFilter === $filterCategory->slug ? 'is-active' : '' }}" href="{{ route('catalog.category', $filterCategory->slug) }}">{{ $filterCategory->name }}<span class="catalog-chip-count">{{ $filterCategory->products_count }}</span></a>
    @endforeach
</nav>
