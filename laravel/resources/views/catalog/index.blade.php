@extends('layouts.app')
@section('title', 'Каталог препаратов для дома, сада и огорода - МосАгро')
@section('description', 'Защита и питание растений оптом от МосАгро и брендов-партнёров. Прайс для дилеров - по заявке.')
@section('body_class', 'page-catalog')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> / <span class="here">Каталог</span>
        </nav>
        <h1>Каталог препаратов для дома, сада и огорода</h1>
        @include('catalog._filters')
        <div id="catalog-results" data-catalog-results aria-live="polite">
            @include('catalog._results')
        </div>
    </div>
</section>
@endsection
@push('jsonld')
{!! \App\Support\JsonLd::script(\App\Support\JsonLd::breadcrumbs([
    ['name' => 'Главная', 'url' => route('home')],
    ['name' => 'Каталог', 'url' => route('catalog.index')],
])) !!}
@endpush
@push('scripts')
<script src="{{ asset('js/catalog-search.js') }}" defer></script>
@endpush
