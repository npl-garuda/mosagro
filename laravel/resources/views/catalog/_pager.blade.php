@if($products->hasPages())
<nav class="catalog-pager" aria-label="Страницы">
    @if($products->onFirstPage())
        <span class="catalog-page" aria-disabled="true">‹</span>
    @else
        <a class="catalog-page" href="{{ $products->previousPageUrl() }}" rel="prev">‹</a>
    @endif
    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
        @if($page == $products->currentPage())
            <span class="catalog-page is-active" aria-current="page">{{ $page }}</span>
        @else
            <a class="catalog-page" href="{{ $url }}">{{ $page }}</a>
        @endif
    @endforeach
    @if($products->hasMorePages())
        <a class="catalog-page" href="{{ $products->nextPageUrl() }}" rel="next">›</a>
    @else
        <span class="catalog-page" aria-disabled="true">›</span>
    @endif
</nav>
@endif
