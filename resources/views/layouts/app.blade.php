<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ __('site.meta_description') }}">

        <title>{{ config('restaurant.name') }} – {{ __('site.tagline') }}</title>

        @stack('head')

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-brand-cream font-sans text-brand-ink antialiased">
        <header class="sticky top-0 z-10 bg-brand-red text-brand-cream shadow">
            <div class="mx-auto flex max-w-5xl flex-wrap items-center justify-between gap-x-6 gap-y-1 px-4 py-2 sm:py-3">
                <a href="{{ app()->getLocale() === 'en' ? route('en.home') : route('home') }}" class="font-display text-xl font-bold text-brand-gold">
                    {{ config('restaurant.name') }}
                </a>

                <nav aria-label="{{ __('site.nav.label') }}" class="order-3 flex w-full justify-between gap-4 text-sm font-medium sm:order-2 sm:ml-auto sm:w-auto sm:justify-start sm:gap-6">
                    <a href="#menu" class="py-1 hover:text-brand-gold-light">{{ __('site.nav.menu') }}</a>
                    <a href="#about" class="py-1 hover:text-brand-gold-light">{{ __('site.nav.about') }}</a>
                    <a href="#contact" class="py-1 hover:text-brand-gold-light">{{ __('site.nav.contact') }}</a>
                </nav>

                <nav aria-label="{{ __('site.language') }}" class="order-2 flex gap-3 text-sm font-medium sm:order-3">
                    <a href="{{ route('home') }}" lang="de" hreflang="de" @class(['underline underline-offset-4' => app()->getLocale() === 'de'])>DE</a>
                    <a href="{{ route('en.home') }}" lang="en" hreflang="en" @class(['underline underline-offset-4' => app()->getLocale() === 'en'])>EN</a>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="bg-brand-red-dark px-4 py-8 text-center text-sm text-brand-gold-light">
            <p class="font-display text-lg font-bold text-brand-gold">{{ config('restaurant.name') }}</p>
            <p class="mt-1">{{ config('restaurant.address.street') }} · {{ config('restaurant.address.postal_code') }} {{ config('restaurant.address.city') }}</p>
            <p class="mt-1">
                <a href="tel:{{ config('restaurant.phone.link') }}" class="underline underline-offset-4">{{ config('restaurant.phone.display') }}</a>
            </p>
        </footer>
    </body>
</html>
