@extends('layouts.app')
@section('title', 'Каталог препаратов для сада и фермы оптом - МосАгро')
@section('description', 'Защита и питание растений оптом от МосАгро и брендов-партнёров. Прайс для дилеров - по заявке.')
@section('content')
<section class="section">
    <div class="wrap">
        <h1>Каталог препаратов для сада и фермы оптом</h1>
        <p class="lead">Всё для защиты и питания растений - от МосАгро и брендов-партнёров. Подходит дилерам, садовым центрам и хозяйствам. Розничные цены на сайте не публикуем: актуальный оптовый прайс отправим по заявке.</p>
        @if($categories->isNotEmpty())
            <div class="grid" style="margin-bottom:1.5rem">
                @foreach($categories as $category)
                    <a class="card-link" href="{{ route('catalog.category', $category->slug) }}">
                        <h3>{{ $category->name }}</h3>
                    </a>
                @endforeach
            </div>
        @endif
        <div class="grid">
            @forelse($products as $product)
                @include('catalog._card', ['product' => $product])
            @empty
                <p class="muted">Товары скоро появятся.</p>
            @endforelse
        </div>
        <div style="margin-top:1.5rem">{{ $products->links() }}</div>
    </div>
</section>
@endsection
