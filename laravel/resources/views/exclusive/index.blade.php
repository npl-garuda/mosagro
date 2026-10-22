@extends('layouts.app')
@section('title', 'НВ-101 - для сада и фермы оптом | МосАгро')
@section('description', 'Эксклюзив FLORA (Япония) и НВ-101 в РФ. Поставки для сада, огорода и фермерских хозяйств.')
@section('body_class', 'page-exclusive')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> / <span class="here">Эксклюзив</span>
        </nav>
        <h1>НВ-101 - для сада и фермы оптом</h1>
        <p class="page-lead">Производство FLORA Co. Ltd. (Япония), импортёр в РФ - ООО «Флора».</p>
        <div class="exclusive-group">
            <h2>Ассортимент FLORA</h2>
            <div class="prod-list">
                @forelse($products as $product)
                    @php
                        $url = $product->catalogUrl();
                        $imageUrl = $product->publicImageUrl();
                        $packs = $product->relationLoaded('packagings')
                            ? $product->packagings->pluck('name')->filter()->values()
                            : collect();
                        $packText = $packs->isNotEmpty() ? 'фасовки '.$packs->implode(' / ') : null;
                    @endphp
                    <article class="prod-row">
                        <a class="prod-thumb" href="{{ $url }}" aria-label="{{ $product->name }}">
                            @if($imageUrl)
                                <img src="{{ $imageUrl }}" alt="">
                            @else
                                Фото скоро
                            @endif
                        </a>
                        <div class="prod-body">
                            <h3 class="prod-name">
                                <a href="{{ $url }}">{{ $product->name }}</a>
                            </h3>
                            @if($product->is_new || $product->is_exclusive)
                                <div class="badges">
                                    @if($product->is_new)<span class="badge badge-new">Новинка</span>@endif
                                    @if($product->is_exclusive)<span class="badge badge-exclusive">Эксклюзив</span>@endif
                                </div>
                            @endif
                            @if($packText)
                                <p class="prod-meta">{{ $packText }}</p>
                            @elseif($product->short_description)
                                <p class="prod-meta">{{ \Illuminate\Support\Str::limit($product->short_description, 80) }}</p>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="catalog-empty">Позиции FLORA скоро появятся.</p>
                @endforelse
            </div>
        </div>
        <div class="actions">
            <a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a>
            <a class="btn btn-outline" href="{{ route('catalog.category', \App\Support\FloraBrand::CATEGORY_SLUG) }}">Смотреть в каталоге</a>
        </div>
    </div>
</section>
@endsection
