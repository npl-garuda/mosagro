@extends('layouts.app')
@section('title', 'Реквизиты ООО «МосАгро»')
@section('description', 'Реквизиты ООО «МосАгро»: наименование, адреса, ИНН, банковские реквизиты, контакты.')
@section('body_class', 'page-legal page-requisites')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> / <span class="here">Реквизиты</span>
        </nav>
        <h1>Реквизиты ООО «МосАгро»</h1>
        <div class="legal-block">
            <div class="legal-k">Полное наименование</div>
            <div class="legal-v">Общество с ограниченной ответственностью «МосАгро»</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Краткое наименование</div>
            <div class="legal-v">ООО «МосАгро»</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Юридический адрес</div>
            <div class="legal-v">129343, г. Москва, вн.тер.г., муниципальный округ Свиблово, проезд Нансена, д. 1, помещение 6/5</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Фактический адрес</div>
            <div class="legal-v">129343, г. Москва, вн.тер.г., муниципальный округ Свиблово, проезд Нансена, д. 1, помещение 6/5</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Почтовый адрес</div>
            <div class="legal-v">129343, г. Москва, вн.тер.г., муниципальный округ Свиблово, проезд Нансена, д. 1, помещение 6/5</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">ИНН</div>
            <div class="legal-v">7716531100</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">КПП</div>
            <div class="legal-v">771601001</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">ОГРН</div>
            <div class="legal-v">1057747879342</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">ОКПО</div>
            <div class="legal-v">78490146</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Расчётный счёт</div>
            <div class="legal-v">40702810218100000552</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Корреспондентский счёт</div>
            <div class="legal-v">3010181014520000411</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">БИК</div>
            <div class="legal-v">044525411</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Банк</div>
            <div class="legal-v">Филиал «Центральный» Банка ВТБ (ПАО)</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Генеральный директор</div>
            <div class="legal-v">Москвин Николай Владимирович</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Главный бухгалтер</div>
            <div class="legal-v">Москвин Николай Владимирович</div>
        </div>
        <div class="legal-block">
            <div class="legal-k">Email</div>
            <div class="legal-v"><a href="mailto:sales@mos-agro.ru">sales@mos-agro.ru</a></div>
        </div>
        <div class="actions">
            <a class="btn" href="{{ asset('files/kartochka-klienta-mosagro-vtb.xls') }}" download="Kartochka-organizacii-MosAgro-VTB.xls">Скачать карточку организации</a>
        </div>
    </div>
</section>
@endsection
