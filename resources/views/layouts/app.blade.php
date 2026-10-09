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
    {{-- The cart lives here (dish id => quantity). Every change is sent as a whole to the PreorderForm component. --}}
    <body class="bg-brand-cream font-sans text-brand-ink antialiased"
        x-data="{
            cart: {},
            get count() { return Object.values(this.cart).reduce((sum, quantity) => sum + quantity, 0) },
            change(id, by) {
                const quantity = Math.min(Math.max((this.cart[id] || 0) + by, 0), 20);
                if (quantity) { this.cart[id] = quantity } else { delete this.cart[id] }
                Livewire.dispatch('cart-set', { cart: this.cart });
            },
        }"
        @cart-cleared.window="cart = {}">
        <header class="sticky top-0 z-10 bg-brand-red text-brand-cream shadow">
            {{-- Mobile: brand + order button in the first row, links + language in the second. --}}
            <div class="mx-auto flex max-w-5xl flex-wrap items-center gap-x-5 gap-y-1 px-4 py-2 md:py-3">
                <a href="{{ app()->getLocale() === 'en' ? route('en.home') : route('home') }}" class="font-display text-lg font-bold text-brand-gold sm:text-xl">
                    {{ config('restaurant.name') }}
                </a>

                <a href="#preorder" class="ml-auto flex items-center gap-2 rounded-full bg-brand-gold px-3 py-1.5 text-xs font-semibold whitespace-nowrap text-brand-ink sm:text-sm md:order-last md:ml-0">
                    {{ __('site.nav.checkout') }}
                    <span x-show="count > 0" x-cloak x-text="count" class="min-w-6 rounded-full bg-brand-red px-1.5 text-center text-xs leading-6 text-brand-cream"></span>
                </a>

                <nav aria-label="{{ __('site.nav.label') }}" class="flex gap-4 text-sm font-medium md:ml-auto md:gap-6">
                    <a href="#menu" class="py-1 hover:text-brand-gold-light">{{ __('site.nav.menu') }}</a>
                    <a href="#about" class="py-1 hover:text-brand-gold-light">{{ __('site.nav.about') }}</a>
                    <a href="#contact" class="py-1 hover:text-brand-gold-light">{{ __('site.nav.contact') }}</a>
                </nav>

                <nav aria-label="{{ __('site.language') }}" class="ml-auto flex gap-3 text-sm font-medium md:ml-0">
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
