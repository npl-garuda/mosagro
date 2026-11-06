@php
    $fileUrl = $document->publicFileUrl() ?: $document->external_url;
@endphp
<li class="docs-row">
    @if($fileUrl)
        <a class="docs-row-main" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">
            <img class="docs-row-ico" src="{{ asset('images/icon-docs.png') }}" alt="" width="22" height="22">
            <span class="docs-row-title">{{ $document->title }}</span>
        </a>
        <a class="docs-row-link" href="{{ $fileUrl }}" target="_blank" rel="noopener noreferrer">{{ $document->downloadLabel() }}</a>
    @else
        <div class="docs-row-main">
            <img class="docs-row-ico" src="{{ asset('images/icon-docs.png') }}" alt="" width="22" height="22">
            <span class="docs-row-title">{{ $document->title }}</span>
        </div>
        <span class="docs-row-link muted">Файл скоро</span>
    @endif
</li>
