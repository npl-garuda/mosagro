@extends('layouts.app')
@section('title', $product->name.' оптом | МосАгро')
@section('description', \Illuminate\Support\Str::limit($product->short_description ?: ($product->name.' оптом от МосАгро'), 150))
@section('content')
<section class="section">
    <div class="wrap">
        <p class="crumbs">
            <a href="{{ route('catalog.index') }}">Каталог</a> /
            <a href="{{ route('catalog.category', $category->slug) }}">{{ $category->name }}</a> /
            {{ $product->name }}
        </p>
        <div class="product-layout">
            <div>
                @if($product->publicImageUrl())
                    <img class="product-image" src="{{ $product->publicImageUrl() }}" alt="{{ $product->name }}">
                @else
                    <div class="placeholder">Фото скоро</div>
                @endif
            </div>
            <div>
                <div class="badges">
                    @if($product->is_new)<span class="badge">Новинка</span>@endif
                    @if($product->is_exclusive)<span class="badge">Эксклюзив</span>@endif
                </div>
                <h1>{{ $product->name }}</h1>
                @if($product->brand)
                    <p class="meta">Бренд: {{ $product->brand->name }}</p>
                @endif
                @if($product->short_description)
                    <p>{{ $product->short_description }}</p>
                @endif
                @if($product->packagings->isNotEmpty())
                    <h2>Фасовки</h2>
                    <p class="muted">Справочно</p>
                    <ul class="list-plain">
                        @foreach($product->packagings as $packaging)
                            <li>{{ $packaging->name }}</li>
                        @endforeach
                    </ul>
                @endif
                @if($product->composition)
                    <h2>Состав</h2>
                    <p>{{ $product->composition }}</p>
                @endif
                @if($product->application)
                    <h2>Применение</h2>
                    <p>{{ $product->application }}</p>
                @endif
                @if($product->documents->isNotEmpty())
                    <h2>Документы</h2>
                    <ul class="list-plain">
                        @foreach($product->documents as $document)
                            <li>
                                @if($document->publicFileUrl())
                                    <a href="{{ $document->publicFileUrl() }}" target="_blank" rel="noopener noreferrer">{{ $document->title }}</a>
                                @else
                                    {{ $document->title }}
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
                <p style="margin-top:1.2rem">
                    <a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a>
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
