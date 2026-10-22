@extends('layouts.app')
@section('title', 'МосАгро - СЗР и удобрения оптом для сада и фермы')
@section('description', 'Средства защиты растений и удобрения оптом от МосАгро. Для дилеров, садовых центров и хозяйств по всей России. Запросите прайс.')
@section('body_class', 'page-home')
@section('content')
<section class="hero-first">
    <div class="wrap hero-copy">
        <h1>Средства защиты растений и удобрения оптом от&nbsp;МосАгро</h1>
        <p class="lead">СЗР и удобрения оптом с 2005. Своя фасовка, документы, отгрузки по РФ.</p>
        <div class="actions">
            <a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a>
            <a class="btn btn-outline" href="{{ route('catalog.index') }}">Смотреть каталог</a>
        </div>
    </div>
</section>
<section class="section">
    <div class="wrap">
        <h2>О компании</h2>
        <div class="about-text">
            <p>Компания МосАгро основана 15 августа 2005 года и за прошедшие годы стала одним из лидеров рынка средств защиты растений для личных подсобных хозяйств.</p>
            <p>Мы предлагаем самые современные, экологичные и эффективные средства.</p>
            <p>Также мы предлагаем ассортимент продукции ведущих фирм для защиты от кровососущих насекомых и вредителей. Ваш отдых на природе станет более комфортным и безопасным.</p>
        </div>
    </div>
</section>
<section class="section">
    <div class="wrap">
        <h2>Преимущества</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <img class="feature-ico" src="{{ asset('images/icon-price.png') }}" alt="" width="40" height="40">
                <div class="feature-title">Честная оптовая цена</div>
            </div>
            <div class="feature-card">
                <img class="feature-ico" src="{{ asset('images/icon-pack.png') }}" alt="" width="40" height="40">
                <div class="feature-title">Своя фасовка и СГР</div>
            </div>
            <div class="feature-card">
                <img class="feature-ico" src="{{ asset('images/icon-docs.png') }}" alt="" width="40" height="40">
                <div class="feature-title">Документы для сетей</div>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="wrap">
        <h2>Как начать работу</h2>
        <ol class="steps">
            <li class="step"><span class="step-num" aria-hidden="true">1</span><span class="step-text">Запросите прайс лист</span></li>
            <li class="step"><span class="step-num" aria-hidden="true">2</span><span class="step-text">Согласуйте условия с менеджером</span></li>
            <li class="step"><span class="step-num" aria-hidden="true">3</span><span class="step-text">Получите отгрузку со склада в Москве / по РФ</span></li>
        </ol>
    </div>
</section>
<section class="section">
    <div class="wrap">
        <h2>Категории</h2>
        <div class="feature-grid category-grid">
            @foreach($categories as $homeCategory)
                @php
                    $icon = match ($homeCategory->slug) {
                        'gryuzuny' => 'icon-rodent.png',
                        'prochee' => 'icon-other.png',
                        'hb-101' => 'icon-leaf.png',
                        default => 'icon-leaf.png',
                    };
                @endphp
                <a class="feature-card feature-card-link" href="{{ route('catalog.category', $homeCategory->slug) }}">
                    <img class="feature-ico" src="{{ asset('images/'.$icon) }}" alt="" width="40" height="40">
                    <div class="feature-title">{{ $homeCategory->name }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
<section class="section exclusive-section">
    <div class="wrap exclusive-inner">
        <div class="exclusive-copy">
            <h2>Эксклюзив</h2>
            <div class="exclusive-text">
                <p>МосАгро является эксклюзивным дистрибьютором на территории РФ препаратов НВ-101. Производство компании FLORA Co. Ltd. Япония.</p>
                <p>Импортер в РФ ООО «Флора», биологический концентрированный питательный состав HB-101.</p>
            </div>
            <a class="btn btn-outline" href="{{ route('exclusive') }}">Подробнее</a>
        </div>
    </div>
</section>
@include('partials.partners')
@endsection
