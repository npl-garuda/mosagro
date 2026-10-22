@extends('layouts.app')
@section('title', $category->name.' оптом | МосАгро')
@section('description', 'Препараты категории «'.$category->name.'» оптом от МосАгро. Фасовки и документы в карточках, цены - в прайсе.')
@section('body_class', 'page-catalog-category')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> /
            <a href="{{ route('catalog.index') }}">Каталог</a> /
            <span class="here">{{ $category->name }}</span>
        </nav>
        <h1>{{ $category->name }}</h1>
        @include('catalog._filters', ['category' => $category])
        <div id="catalog-results" data-catalog-results aria-live="polite">
            @include('catalog._results')
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script src="{{ asset('js/catalog-search.js') }}" defer></script>
@endpush
