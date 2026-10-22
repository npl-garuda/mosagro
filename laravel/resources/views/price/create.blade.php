@extends('layouts.app')
@section('title', 'Запросить прайс лист МосАгро')
@section('description', 'Актуальный прайс лист препаратов для сада и фермы - для дилеров. Цен на сайте нет, оставьте заявку.')
@section('body_class', 'page-price')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <h1>Запросить прайс лист</h1>
        <p class="page-lead">Прайс отправим после заявки (на email, если укажете).</p>
        <p class="note-footnote">* Для работы с пестицидами и агрохимикатами требуется регистрация в ФГИС «Сатурн». Для работы с удобрениями требуется регистрация в системе «Честный знак».</p>
        <div class="note-cards">
            <a class="note-card" href="https://fgis-saturn.ru/" target="_blank" rel="noopener noreferrer">
                <img class="note-card-ico" src="{{ asset('images/logo-saturn.svg') }}" alt="" width="28" height="28">
                <span class="note-card-text">
                    <span class="note-card-title">ФГИС «Сатурн»</span>
                    <span class="note-card-url">fgis-saturn.ru</span>
                </span>
            </a>
            <a class="note-card" href="https://честныйзнак.рф" target="_blank" rel="noopener noreferrer">
                <img class="note-card-ico" src="{{ asset('images/logo-chestny-znak.svg') }}" alt="" width="28" height="28">
                <span class="note-card-text">
                    <span class="note-card-title">«Честный знак»</span>
                    <span class="note-card-url">честныйзнак.рф</span>
                </span>
            </a>
        </div>
        <p class="form-hint">ИНН и формат работы ускоряют обработку.<br>
            Если вы уже работаете с нами - напишите в комментарий.</p>
        @if($errors->any())
            <div class="error">
                <strong>Проверьте поля формы</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form class="price-form" method="post" action="{{ route('price.store') }}">
            @csrf
            <div class="price-field">
                <label for="organization_name">Название организации</label>
                <input id="organization_name" name="organization_name" type="text" value="{{ old('organization_name') }}" placeholder="ООО «Пример»" autocomplete="organization" maxlength="255">
            </div>
            <div class="price-field">
                <label for="inn">ИНН</label>
                <input id="inn" name="inn" type="text" value="{{ old('inn') }}" placeholder="7700000000" inputmode="numeric" autocomplete="off" maxlength="20">
            </div>
            <div class="price-field">
                <label for="email">Email организации</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="zakupki@example.ru" autocomplete="email" inputmode="email" data-filter="email" maxlength="255">
            </div>
            <div class="price-field">
                <label for="phone">Телефон организации <span class="req">*</span></label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="+7 (900) 000-00-00" required autocomplete="tel" inputmode="tel" data-filter="phone" maxlength="50">
            </div>
            <div class="price-field">
                <label for="work_format">Формат работы</label>
                <select id="work_format" name="work_format">
                    <option value="" @selected(old('work_format') === null || old('work_format') === '')>Выберите формат</option>
                    <option value="опт" @selected(old('work_format') === 'опт')>Опт</option>
                    <option value="розница" @selected(old('work_format') === 'розница')>Розница</option>
                    <option value="сеть" @selected(old('work_format') === 'сеть')>Сеть</option>
                    <option value="маркетплейс" @selected(old('work_format') === 'маркетплейс')>Маркетплейс</option>
                </select>
            </div>
            <div class="price-field">
                <label for="comment">Комментарий</label>
                <textarea id="comment" name="comment" placeholder="Комментарий к заявке" maxlength="5000">{{ old('comment') }}</textarea>
            </div>
            <button class="btn" type="submit">Запросить прайс лист</button>
        </form>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/form-filters.js') }}"></script>
@endpush
