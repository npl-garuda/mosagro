@extends('layouts.app')
@section('title', 'Документы на препараты МосАгро - СГР и декларации')
@section('description', 'Госрегистрация и декларации на препараты для сада и фермы. Скачать PDF или перейти к производителю.')
@section('content')
<section class="section">
    <div class="wrap">
        <h1>Документы на препараты для сада и фермы</h1>
        <p class="lead">Свидетельства о госрегистрации и декларации - чтобы дилеры и сети могли спокойно выводить продукцию в продажу.</p>
        <div class="grid">
            @forelse($sections as $section)
                <a class="card-link" href="{{ route('documents.section', $section->slug) }}">
                    <h3>{{ $section->name }}</h3>
                    @if($section->description)
                        <p class="meta">{{ \Illuminate\Support\Str::limit($section->description, 120) }}</p>
                    @endif
                </a>
            @empty
                <p class="muted">Разделы документов скоро появятся.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
