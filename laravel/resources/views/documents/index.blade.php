@extends('layouts.app')
@section('title', 'Документы на препараты МосАгро - СГР и декларации')
@section('description', 'Госрегистрация и декларации на препараты для сада и фермы. Скачать PDF по разделам производителей.')
@section('body_class', 'page-documents')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> / <span class="here">Документы</span>
        </nav>
        <h1>Документы на препараты для сада и фермы</h1>
        <ul class="docs-sections">
            @forelse($sections as $section)
                <li>
                    <a class="docs-section-link" href="{{ route('documents.section', $section->slug) }}">
                        <span class="docs-section-name">{{ $section->name }}</span>
                        <svg class="docs-section-chevron" viewBox="0 0 16 16" width="14" height="14" aria-hidden="true" focusable="false"><path d="M3.2 5.6 L8 10.4 L12.8 5.6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </li>
            @empty
                <li class="muted">Разделы документов скоро появятся.</li>
            @endforelse
        </ul>
    </div>
</section>
@endsection
