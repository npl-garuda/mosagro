@extends('layouts.app')
@section('title', 'Контакты МосАгро - опт, Москва')
@section('description', 'Адрес и email МосАгро для оптовых заявок. Прайс - через форму на сайте или email.')
@section('body_class', 'page-contacts')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> / <span class="here">Контакты</span>
        </nav>
        <h1>Контакты МосАгро</h1>
        <div class="contact-block">
            <div class="contact-label">Адрес</div>
            <p class="contact-value">129343, г. Москва, вн.тер.г., муниципальный округ Свиблово, проезд Нансена, д. 1, помещение 6/5</p>
        </div>
        <div class="contact-block">
            <div class="contact-label">Контактный email</div>
            <p class="contact-value"><a href="mailto:sales@mos-agro.ru">sales@mos-agro.ru</a></p>
        </div>
        <p class="contact-note">Для запроса прайс листа используйте форму на сайте или email.</p>
        <div class="actions">
            <a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a>
        </div>
        <div class="contact-map" aria-label="Карта: МосАгро, проезд Нансена, 1">
            <a class="contact-map-credit" href="https://yandex.ru/maps/org/mosagro/156859480460/?utm_medium=mapframe&utm_source=maps" target="_blank" rel="noopener noreferrer">МосАгро</a>
            <a class="contact-map-credit" href="https://yandex.ru/maps/213/moscow/category/plant_protection_products/184107008/?utm_medium=mapframe&utm_source=maps" target="_blank" rel="noopener noreferrer">Средства защиты растений в Москве</a>
            <iframe src="https://yandex.ru/map-widget/v1/?ll=37.653540%2C55.848637&mode=search&oid=156859480460&ol=biz&z=15.78" title="Карта МосАгро на Яндекс Картах" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>
@endsection
