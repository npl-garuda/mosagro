@extends('layouts.app')
@section('title', 'Заявка на прайс лист принята | МосАгро')
@section('description', 'Спасибо! Свяжемся по телефону и при необходимости отправим прайс лист на email.')
@section('robots', 'noindex, follow')
@section('body_class', 'page-price-thanks')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <div class="thanks-block">
            <h1>Заявка на прайс лист принята</h1>
            <p class="page-lead">Спасибо! Свяжемся по телефону и при необходимости отправим прайс лист на email.</p>
            <div class="actions">
                <a class="btn" href="{{ route('home') }}">На главную</a>
                <a class="btn btn-outline" href="{{ route('catalog.index') }}">В каталог</a>
            </div>
        </div>
    </div>
</section>
@endsection
