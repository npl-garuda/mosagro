@php
    $url = $product->catalogUrl();
    $imageUrl = $product->publicImageUrl();
@endphp
@if($url)
<a class="card-link" href="{{ $url }}">
@else
<div class="card-link">
@endif
    @if($imageUrl)
        <img class="card-image" src="{{ $imageUrl }}" alt="{{ $product->name }}">
    @else
        <div class="placeholder card-image">Фото скоро</div>
    @endif
    @if($product->is_new || $product->is_exclusive)
        <div class="badges">
            @if($product->is_new)<span class="badge badge-new">Новинка</span>@endif
            @if($product->is_exclusive)<span class="badge badge-exclusive">Эксклюзив</span>@endif
        </div>
    @endif
    <h3>{{ $product->name }}</h3>
    @if($product->brand)
        <p class="meta">{{ $product->brand->name }}</p>
    @endif
    @if($product->short_description)
        <p class="meta">{{ \Illuminate\Support\Str::limit($product->short_description, 110) }}</p>
    @endif
@if($url)
</a>
@else
</div>
@endif
