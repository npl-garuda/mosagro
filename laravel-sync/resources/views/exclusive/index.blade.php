@extends('layouts.app')
@section('title', 'HB-101 и FLORA для сада и фермы оптом | МосАгро')
@section('description', 'Эксклюзив FLORA (Япония) и HB-101 в РФ. Поставки для сада, огорода и фермерских хозяйств.')
@section('content')
<section class="section">
    <div class="wrap">
        <h1>FLORA и HB-101 - для сада и фермы оптом</h1>
        <p class="lead">Производство FLORA Co. Ltd. (Япония), импортёр в РФ - ООО «Флора». Биологический состав HB-101 и сопутствующие препараты - для тех, кто растит урожай, и для дилеров, которые их снабжают.</p>
        <p class="muted">Эти позиции также есть в общем каталоге с меткой «Эксклюзив».</p>
        <div class="grid" style="margin-top:1.2rem">
            @forelse($products as $product)
                @include('catalog._card', ['product' => $product])
            @empty
                <p class="muted">Эксклюзивные товары скоро появятся.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
