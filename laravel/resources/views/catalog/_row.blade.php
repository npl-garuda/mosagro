@php
    $url = $product->catalogUrl();
    $imageUrl = $product->publicImageUrl();
    $packs = $product->relationLoaded('packagings')
        ? $product->packagings->pluck('name')->filter()->values()
        : collect();
    $packText = $packs->isNotEmpty() ? 'Фасовки: '.$packs->implode(', ') : null;
    $type = null;
    if ($product->relationLoaded('categories') && $product->categories->isNotEmpty()) {
        $primary = $product->categories->firstWhere('pivot.is_primary', true) ?? $product->categories->first();
        $type = $primary?->name;
    }
    $metaParts = array_values(array_filter([$type, $packText]));
    if ($metaParts === [] && $product->short_description) {
        $metaParts[] = \Illuminate\Support\Str::limit($product->short_description, 90);
    }
@endphp
<article class="prod-row">
    <a class="prod-thumb" href="{{ $url }}" aria-label="{{ $product->name }}">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $product->name }}">
        @else
            Фото скоро
        @endif
    </a>
    <div class="prod-body">
        <h2 class="prod-name">
            <a href="{{ $url }}">{{ $product->name }}</a>
        </h2>
        @if($product->is_new || $product->is_exclusive)
            <div class="badges">
                @if($product->is_new)<span class="badge badge-new">Новинка</span>@endif
                @if($product->is_exclusive)<span class="badge badge-exclusive">Эксклюзив</span>@endif
            </div>
        @endif
        @if($metaParts !== [])
            <p class="prod-meta">{{ implode(' · ', $metaParts) }}</p>
        @endif
        <a class="prod-docs" href="{{ $url }}#documents">Документы ↓</a>
    </div>
    <div class="prod-action">
        <a class="btn btn-outline" href="{{ route('price.create') }}">Запросить прайс</a>
    </div>
</article>
