<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="{{ __('site.meta_description') }}">

        <title>{{ config('restaurant.name') }} – {{ __('site.tagline') }}</title>

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-brand-cream font-sans text-brand-ink antialiased">
        <header class="sticky top-0 z-10 bg-brand-red text-brand-cream shadow">
            <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3">
                <a href="{{ app()->getLocale() === 'en' ? route('en.home') : route('home') }}" class="font-display text-xl font-bold text-brand-gold">
                    {{ config('restaurant.name') }}
                </a>

                <nav aria-label="{{ __('site.language') }}" class="flex gap-3 text-sm font-medium">
                    <a href="{{ route('home') }}" lang="de" hreflang="de" @class(['underline underline-offset-4' => app()->getLocale() === 'de'])>DE</a>
                    <a href="{{ route('en.home') }}" lang="en" hreflang="en" @class(['underline underline-offset-4' => app()->getLocale() === 'en'])>EN</a>
                </nav>
            </div>
        </header>

        <main>
            @yield('content')
        </main>

        <footer class="bg-brand-red-dark px-4 py-6 text-center text-sm text-brand-gold-light">
            {{ config('restaurant.name') }} · {{ config('restaurant.address.street') }} · {{ config('restaurant.address.postal_code') }} {{ config('restaurant.address.city') }}
        </footer>
    </body>
</html>
