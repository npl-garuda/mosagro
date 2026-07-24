@extends('layouts.app')
@section('title', $category->name.' оптом | МосАгро')
@section('description', 'Препараты категории «'.$category->name.'» оптом от МосАгро. Фасовки и документы в карточках, цены - в прайсе.')
@section('content')
<section class="section">
    <div class="wrap">
        <p class="crumbs"><a href="{{ route('catalog.index') }}">Каталог</a> / {{ $category->name }}</p>
        <h1>{{ $category->name }}</h1>
        <div class="grid">
            @forelse($products as $product)
                @include('catalog._card', ['product' => $product])
            @empty
                <p class="muted">В этой категории пока нет товаров.</p>
            @endforelse
        </div>
        <div style="margin-top:1.5rem">{{ $products->links() }}</div>
    </div>
</section>
@endsection
