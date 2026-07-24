@extends('layouts.app')
@section('title', 'Запросить прайс лист МосАгро')
@section('description', 'Актуальный прайс лист препаратов для сада и фермы - для дилеров. Цен на сайте нет, оставьте заявку.')
@section('content')
<section class="section">
    <div class="wrap">
        <h1>Запросить прайс лист</h1>
        <p class="lead">Розничные цены на сайте не публикуем. Актуальный прайс лист препаратов для сада, огорода и хозяйств отправим на email после заявки.</p>
        <p>Уже работаете с нами - напишите об этом в комментарии.</p>
        <div class="note">
            <p>Без регистрации в госсистемах могут быть сложности с продажей препаратов и удобрений.</p>
        </div>
        <div class="note">
            <h3>ФГИС «Сатурн»</h3>
            <p>Для оборота пестицидов и агрохимикатов нужна регистрация во ФГИС «Сатурн».</p>
            <p><a href="https://fgis-saturn.ru/" target="_blank" rel="noopener noreferrer">fgis-saturn.ru</a></p>
        </div>
        <div class="note">
            <h3>«Честный знак» (ГИС МТ)</h3>
            <p>Для оборота удобрений нужна регистрация в системе «Честный знак».</p>
            <p><a href="https://markirovka.crpt.ru/" target="_blank" rel="noopener noreferrer">markirovka.crpt.ru</a></p>
        </div>
        <div class="note">
            <h3>Что нужно для старта сотрудничества</h3>
            <ul class="list-plain">
                <li>Укажите ИНН и город - это ускорит проверку и подготовку коммерческого предложения.</li>
                <li>Напишите формат работы (опт, розница, сеть, маркетплейс) и систему налогообложения.</li>
                <li>После отправки заявки менеджер запросит карту контрагента и уточнит условия поставки.</li>
            </ul>
        </div>
        <div class="note">
            <h3>Чеклист перед отправкой</h3>
            <ol class="list-plain">
                <li>Проверил корректность email - на него отправим прайс лист.</li>
                <li>Указал ИНН и город для быстрой обработки.</li>
                <li>Добавил в комментарий профиль закупки и ожидаемые категории товаров.</li>
            </ol>
        </div>
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
        <form class="form" method="post" action="{{ route('price.store') }}">
            @csrf
            <label>
                ФИО *
                <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" autocomplete="name">
            </label>
            <label>
                Телефон *
                <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="50" autocomplete="tel">
            </label>
            <label>
                Email *
                <input type="email" name="email" value="{{ old('email') }}" required maxlength="255" autocomplete="email">
            </label>
            <label>
                ИНН
                <input type="text" name="inn" value="{{ old('inn') }}" maxlength="20">
            </label>
            <label>
                Город
                <input type="text" name="city" value="{{ old('city') }}" maxlength="255" autocomplete="address-level2">
            </label>
            <label>
                Как удобнее связаться
                <input type="text" name="contact_preference" value="{{ old('contact_preference') }}" maxlength="255" placeholder="Email / телефон / мессенджер">
            </label>
            <label>
                Комментарий
                <textarea name="comment" rows="5" maxlength="5000">{{ old('comment') }}</textarea>
            </label>
            <label class="check">
                <input type="checkbox" name="consent" value="1" @checked(old('consent')) required>
                <span>Согласен на <a href="{{ route('consent') }}" target="_blank">обработку персональных данных</a> *</span>
            </label>
            <button class="btn" type="submit">Запросить прайс лист</button>
        </form>
    </div>
</section>
@endsection
