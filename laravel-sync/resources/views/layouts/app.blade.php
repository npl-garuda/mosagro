<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'МосАгро')</title>
    <meta name="description" content="@yield('description', 'Средства защиты растений и удобрения оптом от МосАгро.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@400;600;700&family=Source+Serif+4:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
<header class="site-header">
    <div class="wrap header-inner">
        <a class="brand" href="{{ route('home') }}">МосАгро</a>
        <nav class="nav" aria-label="Основное меню">
            <a href="{{ route('home') }}">Главная</a>
            <a href="{{ route('about') }}">О компании</a>
            <a href="{{ route('catalog.index') }}">Каталог</a>
            <a href="{{ route('exclusive') }}">Эксклюзив</a>
            <a href="{{ route('documents.index') }}">Документы</a>
            <a href="{{ route('price.create') }}">Прайс</a>
            <a href="{{ route('contacts') }}">Контакты</a>
        </nav>
    </div>
</header>
<main>
    @yield('content')
</main>
<footer class="site-footer">
    <div class="wrap footer-grid">
        <div>
            <strong>МосАгро</strong>
            <p class="muted">СЗР и удобрения оптом для сада и фермы</p>
            <p><a href="mailto:sales@mos-agro.ru">sales@mos-agro.ru</a></p>
        </div>
        <div>
            <p><a href="{{ route('about') }}">О компании</a></p>
            <p><a href="{{ route('catalog.index') }}">Каталог</a></p>
            <p><a href="{{ route('price.create') }}">Запросить прайс лист</a></p>
            <p><a href="{{ route('contacts') }}">Контакты</a></p>
        </div>
        <div>
            <p><a href="{{ route('requisites') }}">Реквизиты</a></p>
            <p><a href="{{ route('privacy') }}">Политика ПДн</a></p>
            <p><a href="{{ route('consent') }}">Согласие на обработку ПДн</a></p>
        </div>
        <div>
            <p class="muted">Офис: г. Москва, ул. Ивовая, д. 2/8, стр. 1</p>
            <p class="muted">Склад: г. Москва, ул. Вилюйская, д. 2, стр. 1</p>
        </div>
    </div>
</footer>
</body>
</html>
