@extends('layouts.app')
@section('title', 'Стать дилером МосАгро')
@section('description', 'Заявка на дилерство МосАгро. Оставьте контакты - менеджер свяжется и отправит условия.')
@section('body_class', 'page-dealer')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <h1>Стать дилером МосАгро</h1>
        <p class="page-lead">Оставьте заявку - менеджер свяжется, уточнит условия и отправит прайс.</p>
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
        <form class="price-form" method="post" action="{{ route('dealer.store') }}">
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
            <button class="btn" type="submit">Отправить заявку</button>
        </form>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/form-filters.js') }}"></script>
@endpush
