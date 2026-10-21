@extends('layouts.app')
@section('title', 'Контакты МосАгро - опт, Москва')
@section('description', 'Офис и склад в Москве, email для оптовых заявок. Прайс и заказ - через формы на сайте.')
@section('content')
<section class="section">
    <div class="wrap">
        <h1>Контакты МосАгро</h1>
        <p><strong>Офис:</strong> ООО «МосАгро», 129329, г. Москва, ул. Ивовая, д. 2/8, стр. 1, офис 432</p>
        <p><strong>Почта:</strong> 129329, г. Москва, ул. Ивовая, д. 2, получатель: ООО «МосАгро»</p>
        <p><strong>Склад:</strong> г. Москва, ул. Вилюйская, д. 2, стр. 1 (продбаза «Медведково»)</p>
        <p><strong>Email для опта:</strong> <a href="mailto:sales@mos-agro.ru">sales@mos-agro.ru</a></p>
        <p class="muted">Телефоны компании на сайте не публикуются. Для запроса прайс листа используйте форму на сайте или email.</p>
        <p class="muted">Карта офиса/склада будет добавлена на этапе вёрстки.</p>
        <p style="margin-top:1rem"><a class="btn" href="{{ route('price.create') }}">Запросить прайс лист</a></p>
    </div>
</section>
@endsection
