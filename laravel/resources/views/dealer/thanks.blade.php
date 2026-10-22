@extends('layouts.app')
@section('title', 'Заявка на дилерство принята | МосАгро')
@section('description', 'Спасибо! Менеджер свяжется, уточнит условия сотрудничества и при необходимости отправит прайс.')
@section('body_class', 'page-price-thanks page-dealer-thanks')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <div class="thanks-block">
            <h1>Заявка на дилерство принята</h1>
            <p class="page-lead">Спасибо! Менеджер свяжется, уточнит условия сотрудничества и при необходимости отправит прайс.</p>
            <div class="actions">
                <a class="btn" href="{{ route('home') }}">На главную</a>
                <a class="btn btn-outline" href="{{ route('catalog.index') }}">В каталог</a>
            </div>
        </div>
    </div>
</section>
@endsection
