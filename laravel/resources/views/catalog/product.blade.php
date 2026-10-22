@extends('layouts.app')
@section('title', $product->name.' оптом | МосАгро')
@section('description', \Illuminate\Support\Str::limit($product->short_description ?: ($product->name.' оптом от МосАгро'), 150))
@section('body_class', 'page-product')
@php
    $gallery = $product->galleryImageUrls();
    $imageUrl = $gallery->first();
    $packs = $product->packagings->pluck('name')->filter()->values();
    $docs = $product->documents;
    $docLabels = $docs->pluck('title')->filter()->values();
@endphp
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> /
            <a href="{{ route('catalog.index') }}">Каталог</a> /
            @if(($category->slug ?? null) === 'all')
                <span class="here">{{ $product->name }}</span>
            @else
                <a href="{{ route('catalog.category', $category->slug) }}">{{ $category->name }}</a> /
                <span class="here">{{ $product->name }}</span>
            @endif
        </nav>
        <div class="product-layout">
            <div class="product-gallery" @if($gallery->count() > 1) data-product-gallery @endif>
                <div class="product-photo" data-gallery-main>
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $product->name }}" data-gallery-image>
                        @if($gallery->count() > 1)
                            <button type="button" class="gallery-nav gallery-prev" data-gallery-prev aria-label="Предыдущее фото">‹</button>
                            <button type="button" class="gallery-nav gallery-next" data-gallery-next aria-label="Следующее фото">›</button>
                        @endif
                    @else
                        Фото скоро
                    @endif
                </div>
                @if($gallery->count() > 1)
                    <div class="product-thumbs" role="list" aria-label="Фотографии товара">
                        @foreach($gallery as $index => $thumbUrl)
                            <button
                                type="button"
                                class="product-thumb{{ $index === 0 ? ' is-active' : '' }}"
                                role="listitem"
                                data-gallery-thumb
                                data-index="{{ $index }}"
                                aria-label="Фото {{ $index + 1 }}"
                                aria-pressed="{{ $index === 0 ? 'true' : 'false' }}"
                            >
                                <img src="{{ $thumbUrl }}" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="product-info">
                <h1>{{ $product->name }}</h1>
                @if($product->is_new || $product->is_exclusive)
                    <div class="badges">
                        @if($product->is_new)<span class="badge badge-new">Новинка</span>@endif
                        @if($product->is_exclusive)<span class="badge badge-exclusive">Эксклюзив</span>@endif
                    </div>
                @endif
                <div class="product-meta">
                    @if($product->short_description)
                        <p class="product-meta-line">{{ \Illuminate\Support\Str::limit($product->short_description, 120) }}</p>
                    @endif
                    @if($product->brand)
                        <p class="product-meta-line"><b>Бренд:</b> {{ $product->brand->name }}</p>
                    @endif
                    @if($packs->isNotEmpty())
                        <p class="product-meta-line"><b>Фасовки:</b> {{ $packs->implode(' · ') }}</p>
                    @endif
                    @if($docLabels->isNotEmpty())
                        <p class="product-meta-line"><b>Документы:</b> {{ $docLabels->implode(' · ') }}</p>
                    @endif
                </div>
                <div class="product-tabs" id="documents" role="tablist" aria-label="Разделы карточки">
                    <button type="button" class="product-tab is-active" role="tab" aria-selected="true" data-tab="description">Описание</button>
                    <button type="button" class="product-tab" role="tab" aria-selected="false" data-tab="application">Применение</button>
                    <button type="button" class="product-tab" role="tab" aria-selected="false" data-tab="documents">Документы</button>
                </div>
                <div class="product-panel is-active" id="panel-description" role="tabpanel" data-panel="description">
                    @if($product->composition)
                        <p>{{ $product->composition }}</p>
                    @elseif($product->short_description)
                        <p>{{ $product->short_description }}</p>
                    @else
                        <p class="muted">Описание скоро появится.</p>
                    @endif
                </div>
                <div class="product-panel" id="panel-application" role="tabpanel" data-panel="application" hidden>
                    @if($product->application)
                        <p>{{ $product->application }}</p>
                    @else
                        <p class="muted">Инструкция по применению скоро появится.</p>
                    @endif
                </div>
                <div class="product-panel" id="panel-documents" role="tabpanel" data-panel="documents" hidden>
                    @if($docs->isNotEmpty())
                        <ul class="product-docs">
                            @foreach($docs as $document)
                                <li class="product-doc">
                                    <span class="product-doc-title">{{ $document->title }}</span>
                                    @if($document->publicFileUrl())
                                        <a class="product-doc-link" href="{{ $document->publicFileUrl() }}" target="_blank" rel="noopener noreferrer">PDF ↓</a>
                                    @elseif($document->external_url)
                                        <a class="product-doc-link" href="{{ $document->external_url }}" target="_blank" rel="noopener noreferrer">Открыть ↓</a>
                                    @else
                                        <span class="product-doc-link muted">Файл скоро</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="muted">Документы скоро появятся.</p>
                    @endif
                </div>
                <div class="product-actions actions">
                    <a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a>
                    <a class="btn btn-outline" href="#documents" data-open-tab="documents">Скачать документы</a>
                </div>
            </div>
        </div>
    </div>
</section>
@if($related->isNotEmpty())
<section class="section related-section">
    <div class="wrap">
        <h2>Сопутствующие товары</h2>
        <div class="related-slider" data-related-slider>
            <button type="button" class="related-nav related-prev" data-related-prev aria-label="Назад">‹</button>
            <div class="related-track" data-related-track>
                @foreach($related as $item)
                    @php
                        $itemUrl = $item->catalogUrl();
                        $itemImage = $item->publicImageUrl();
                        $itemType = optional($item->categories->firstWhere('pivot.is_primary', true) ?? $item->categories->first())->name;
                    @endphp
                    <a class="related-card" href="{{ $itemUrl ?? route('catalog.index') }}">
                        <div class="related-ph">
                            @if($itemImage)
                                <img src="{{ $itemImage }}" alt="">
                            @else
                                Фото скоро
                            @endif
                        </div>
                        <div class="related-name">{{ $item->name }}</div>
                        @if($itemType)
                            <div class="related-sub">{{ $itemType }}</div>
                        @endif
                    </a>
                @endforeach
            </div>
            <button type="button" class="related-nav related-next" data-related-next aria-label="Вперёд">›</button>
        </div>
    </div>
</section>
@endif
@endsection
@push('scripts')
<script src="{{ asset('js/product.js') }}?v={{ @filemtime(public_path('js/product.js')) ?: '1' }}"></script>
@endpush
