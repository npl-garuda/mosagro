@extends('layouts.app')
@section('title', 'МосАгро - СЗР и удобрения оптом для сада и фермы')
@section('description', 'Средства защиты растений и удобрения оптом от МосАгро. Для дилеров, садовых центров и хозяйств по всей России. Запросите прайс.')
@section('content')
<section class="hero">
    <div class="wrap">
        <h1>Средства защиты растений и удобрения оптом от МосАгро</h1>
        <p class="lead">Помогаем выращивать урожай - препаратами для сада, огорода и фермерских хозяйств. Оптом от производителя, с документами для дилеров и сетей по всей России.</p>
        <div class="actions">
            <a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a>
            <a class="btn btn-secondary" href="{{ route('catalog.index') }}">Смотреть каталог</a>
        </div>
    </div>
</section>
<section class="section">
    <div class="wrap">
        <h2>Для кого</h2>
        <ul class="list-plain">
            <li>Дилеры и оптовики СЗР</li>
            <li>Садовые центры и торговые сети</li>
            <li>Фермерские и личные подсобные хозяйства (оптовые закупки)</li>
        </ul>
    </div>
</section>
<section class="section">
    <div class="wrap">
        <h2>О компании</h2>
        <p class="lead">С 2005 года производим и фасуем средства защиты растений для садоводов и хозяйств. Для партнёров по опту - стабильное качество, своя фасовка, госрегистрация и отгрузки в регионы РФ.</p>
        <h2>Преимущества</h2>
        <ul class="list-plain">
            <li>Качество при конкурентной оптовой цене</li>
            <li>Своя фасовка и госрегистрация</li>
            <li>Документы для сетей + отгрузки по РФ</li>
        </ul>
        <h2>Как начать работу</h2>
        <ol class="list-plain">
            <li>Запросите прайс лист</li>
            <li>Согласуйте условия с менеджером</li>
            <li>Получите отгрузку со склада в Москве / по РФ</li>
        </ol>
    </div>
</section>
@if($categories->isNotEmpty())
<section class="section">
    <div class="wrap">
        <h2>Категории</h2>
        <div class="grid">
            @foreach($categories as $category)
                <a class="card-link" href="{{ route('catalog.category', $category->slug) }}">
                    <h3>{{ $category->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@if($exclusive->isNotEmpty())
<section class="section">
    <div class="wrap">
        <h2>Эксклюзив</h2>
        <p class="lead">HB-101 и продукция FLORA (Япония) - эксклюзивная дистрибуция в РФ</p>
        <div class="grid">
            @foreach($exclusive as $product)
                @include('catalog._card', ['product' => $product])
            @endforeach
        </div>
        <p style="margin-top:1rem"><a href="{{ route('exclusive') }}">Все эксклюзивные позиции</a></p>
    </div>
</section>
@endif
@if($newProducts->isNotEmpty())
<section class="section">
    <div class="wrap">
        <h2>Новинки</h2>
        <div class="grid">
            @foreach($newProducts as $product)
                @include('catalog._card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif
@if($partners->isNotEmpty())
<section class="section">
    <div class="wrap">
        <h2>Бренды-партнёры</h2>
        <div class="grid">
            @foreach($partners as $partner)
                <div class="card-link">
                    <h3>{{ $partner->name }}</h3>
                    @if($partner->url)
                        <p class="meta"><a href="{{ $partner->url }}" rel="noopener noreferrer" target="_blank">Сайт бренда</a></p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
<section class="section">
    <div class="wrap">
        <h2>Станьте дилером МосАгро</h2>
        <p class="lead">Запросите актуальный прайс лист</p>
        <a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a>
    </div>
</section>
@endsection
