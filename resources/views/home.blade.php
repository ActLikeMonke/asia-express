@extends('layouts.app')

@push('head')
    <link rel="canonical" href="{{ app()->getLocale() === 'en' ? route('en.home') : route('home') }}">
    <link rel="alternate" hreflang="de" href="{{ route('home') }}">
    <link rel="alternate" hreflang="en" href="{{ route('en.home') }}">
    <link rel="alternate" hreflang="x-default" href="{{ route('home') }}">

    <meta property="og:type" content="restaurant">
    <meta property="og:site_name" content="{{ config('restaurant.name') }}">
    <meta property="og:title" content="{{ config('restaurant.name') }} – {{ __('site.tagline') }}">
    <meta property="og:description" content="{{ __('site.meta_description') }}">
    <meta property="og:url" content="{{ app()->getLocale() === 'en' ? route('en.home') : route('home') }}">
    <meta property="og:locale" content="{{ app()->getLocale() === 'en' ? 'en_GB' : 'de_DE' }}">
    <meta property="og:image" content="{{ $logoUrl }}">

    <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
    @include('sections.hero')
    @include('sections.menu')
    @include('sections.about')
    @include('sections.preorder')
    @include('sections.contact')
@endsection
