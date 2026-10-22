@extends('layouts.app')
@section('title', $category->seoTitle())
@section('description', $category->seoDescription())
@section('canonical', route('catalog.category', $category->slug))
@section('body_class', 'page-catalog-category')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> /
            <a href="{{ route('catalog.index') }}">Каталог</a> /
            <span class="here">{{ $category->seoH1() }}</span>
        </nav>
        <h1>{{ $category->seoH1() }}</h1>
        @include('catalog._filters', ['category' => $category])
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
    ['name' => $category->seoH1(), 'url' => route('catalog.category', $category->slug)],
])) !!}
@endpush
@push('scripts')
<script src="{{ asset('js/catalog-search.js') }}" defer></script>
@endpush
