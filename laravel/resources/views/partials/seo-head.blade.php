<title>@yield('title', 'МосАгро')</title>
<meta name="description" content="@yield('description', 'Средства защиты растений и удобрения оптом от МосАгро.')">
@hasSection('robots')
<meta name="robots" content="@yield('robots')">
@endif
<link rel="canonical" href="@yield('canonical', url()->current())">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:locale" content="ru_RU">
<meta property="og:site_name" content="МосАгро">
<meta property="og:title" content="@yield('title', 'МосАгро')">
<meta property="og:description" content="@yield('description', 'Средства защиты растений и удобрения оптом от МосАгро.')">
<meta property="og:url" content="@yield('canonical', url()->current())">
<meta property="og:image" content="@yield('og_image', asset('images/logo.png'))">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('title', 'МосАгро')">
<meta name="twitter:description" content="@yield('description', 'Средства защиты растений и удобрения оптом от МосАгро.')">
{!! \App\Support\JsonLd::script(\App\Support\JsonLd::organization()) !!}
@stack('jsonld')
