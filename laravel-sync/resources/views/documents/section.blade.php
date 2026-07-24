@extends('layouts.app')
@section('title', $section->name.' - документы | МосАгро')
@section('description', $section->description ?: ('Документы раздела '.$section->name))
@section('content')
<section class="section">
    <div class="wrap">
        <p class="crumbs"><a href="{{ route('documents.index') }}">Документы</a> / {{ $section->name }}</p>
        <h1>{{ $section->name }}</h1>
        @if($section->description)
            <p class="lead">{{ $section->description }}</p>
        @endif
        <ul class="list-plain">
            @forelse($section->documents as $document)
                <li>
                    @if($document->external_url)
                        <a href="{{ $document->external_url }}" target="_blank" rel="noopener noreferrer">{{ $document->title }}</a>
                    @elseif($document->file_path)
                        <a href="{{ asset('storage/'.$document->file_path) }}">{{ $document->title }}</a>
                    @else
                        {{ $document->title }}
                    @endif
                </li>
            @empty
                <li class="muted">Документы раздела скоро будут загружены.</li>
            @endforelse
        </ul>
    </div>
</section>
@endsection
