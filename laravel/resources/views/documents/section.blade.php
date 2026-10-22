@extends('layouts.app')
@section('title', ($section->description ?: ('Документы - '.$section->name)).' | МосАгро')
@section('description', $section->description ?: ('Документы раздела '.$section->name.' - СГР и декларации для дилеров.'))
@section('body_class', 'page-documents-section')
@section('content')
<section class="section page-content">
    <div class="wrap">
        <nav class="crumbs" aria-label="Хлебные крошки">
            <a href="{{ route('home') }}">Главная</a> /
            <a href="{{ route('documents.index') }}">Документы</a> /
            <span class="here">{{ $section->name }}</span>
        </nav>
        <h1>{{ $section->description ?: ('Документы - '.$section->name) }}</h1>
        <a class="docs-back" href="{{ route('documents.index') }}">← Все документы</a>
        <ul class="docs-rows">
            @forelse($section->documents as $document)
                @php
                    $fileUrl = $document->publicFileUrl() ?: $document->external_url;
                @endphp
                <li class="docs-row">
                    @if($fileUrl)
                        <a class="docs-row-main" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">
                            <img class="docs-row-ico" src="{{ asset('images/icon-docs.png') }}" alt="" width="22" height="22">
                            <span class="docs-row-title">{{ $document->title }}</span>
                        </a>
                        <a class="docs-row-link" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">PDF ↓</a>
                    @else
                        <div class="docs-row-main">
                            <img class="docs-row-ico" src="{{ asset('images/icon-docs.png') }}" alt="" width="22" height="22">
                            <span class="docs-row-title">{{ $document->title }}</span>
                        </div>
                        <span class="docs-row-link muted">Файл скоро</span>
                    @endif
                </li>
            @empty
                <li class="docs-row">
                    <div class="docs-row-main">
                        <span class="docs-row-title muted">Документы раздела скоро будут загружены.</span>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>
</section>
@endsection
