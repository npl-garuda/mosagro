@extends('layouts.app')
@section('title', ($section->description ?: ('Документы - '.$section->name)).' | МосАгро')
@section('description', $section->description ?: ('Документы раздела '.$section->name.' - СГР и декларации для дилеров.'))
@section('canonical', route('documents.section', $section->slug))
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
        @forelse($documentGroups as $group)
            <section class="docs-subgroup" @if($group['product']) id="product-{{ $group['product']->slug }}" @endif>
                <div class="docs-subgroup-head">
                    @if($group['product'])
                        <a class="docs-subgroup-link" href="{{ $group['product']->catalogUrl() }}">
                            <span class="docs-subgroup-title">{{ $group['title'] }}</span>
                            <svg class="docs-section-chevron" viewBox="0 0 16 16" width="14" height="14" aria-hidden="true" focusable="false"><path d="M3.2 5.6 L8 10.4 L12.8 5.6" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                    @else
                        <p class="docs-subgroup-title">{{ $group['title'] }}</p>
                    @endif
                </div>
                <ul class="docs-rows">
                    @foreach($group['documents'] as $document)
                        @include('documents._row', ['document' => $document])
                    @endforeach
                </ul>
            </section>
        @empty
            <ul class="docs-rows">
                <li class="docs-row">
                    <div class="docs-row-main">
                        <span class="docs-row-title muted">Документы раздела скоро будут загружены.</span>
                    </div>
                </li>
            </ul>
        @endforelse
    </div>
</section>
@endsection
@push('jsonld')
{!! \App\Support\JsonLd::script(\App\Support\JsonLd::breadcrumbs([
    ['name' => 'Главная', 'url' => route('home')],
    ['name' => 'Документы', 'url' => route('documents.index')],
    ['name' => $section->name, 'url' => route('documents.section', $section->slug)],
])) !!}
@endpush
