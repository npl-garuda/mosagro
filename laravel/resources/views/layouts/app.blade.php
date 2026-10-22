<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo-head')
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body class="@yield('body_class')">
<header class="site-header">
    <div class="wrap header-inner">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="МосАгро" width="176" height="82">
        </a>
        <nav class="nav nav-desktop" aria-label="Основное меню">
            <a href="{{ route('about') }}">О компании</a>
            <a href="{{ route('catalog.index') }}">Каталог</a>
            <a href="{{ route('documents.index') }}">Документы</a>
            <a href="{{ route('contacts') }}">Контакты</a>
        </nav>
        <a class="btn btn-header" href="{{ route('price.create') }}">Запросить прайс лист</a>
        <button type="button" class="burger" aria-label="Открыть меню" aria-expanded="false" aria-controls="mobile-menu" data-burger>
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
<div class="mobile-sheet" id="mobile-menu" hidden data-mobile-menu>
    <div class="mobile-sheet-bar">
        <a class="brand" href="{{ route('home') }}">
            <img src="{{ asset('images/logo.png') }}" alt="МосАгро" width="176" height="82">
        </a>
        <button type="button" class="menu-close" aria-label="Закрыть меню" data-menu-close>&times;</button>
    </div>
    <nav class="mobile-nav" aria-label="Мобильное меню">
        <a href="{{ route('home') }}">Главная</a>
        <a href="{{ route('about') }}">О компании</a>
        <a href="{{ route('catalog.index') }}">Каталог</a>
        <a href="{{ route('documents.index') }}">Документы</a>
        <a href="{{ route('contacts') }}">Контакты</a>
    </nav>
    <a class="btn btn-block" href="{{ route('price.create') }}">Запросить прайс лист</a>
</div>
<main>
    @yield('content')
</main>
<footer class="site-footer">
    <div class="wrap">
        <div class="footer-top">
            <a class="footer-brand" href="{{ route('home') }}">
                <img class="footer-logo" src="{{ asset('images/logo.png') }}" alt="МосАгро" width="176" height="82">
            </a>
            <a class="footer-dealer" href="{{ route('dealer.create') }}">Стать дилером</a>
            <div class="footer-meta">
                <a href="mailto:sales@mos-agro.ru">sales@mos-agro.ru</a><br>
                Москва, м. Свиблово, проезд Нансена, 1
            </div>
        </div>
        <div class="footer-bottom">
            <nav class="footer-nav" aria-label="Навигация в подвале">
                <a href="{{ route('about') }}">О компании</a>
                <a href="{{ route('catalog.index') }}">Каталог</a>
                <a href="{{ route('documents.index') }}">Документы</a>
                <a href="{{ route('contacts') }}">Контакты</a>
            </nav>
            <nav class="footer-legal" aria-label="Юридические ссылки">
                <a href="{{ route('requisites') }}">Реквизиты</a>
                <a href="{{ route('privacy') }}">Политика ПДн</a>
                <a href="{{ route('consent') }}">Согласие</a>
            </nav>
        </div>
    </div>
</footer>
<script src="{{ asset('js/menu.js') }}"></script>
@stack('scripts')
@include('partials.metrika')
</body>
</html>
