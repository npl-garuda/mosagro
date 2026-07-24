@extends('layouts.app')
@section('title', 'Заявка на прайс лист принята | МосАгро')
@section('description', 'Спасибо! Отправим актуальный прайс лист на указанный email.')
@section('content')
<section class="section">
    <div class="wrap">
        <h1>Заявка на прайс лист принята</h1>
        <p class="lead">Спасибо! Отправим актуальный прайс лист на указанный email и свяжемся при необходимости.</p>
        <div class="actions">
            <a class="btn" href="{{ route('home') }}">На главную</a>
            <a class="btn btn-secondary" href="{{ route('catalog.index') }}">В каталог</a>
        </div>
    </div>
</section>
@endsection
