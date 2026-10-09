{{-- Dish photo as background; the dark layer keeps the text readable. bg-brand-red-dark shows while the photo loads. --}}
<section class="relative isolate bg-brand-red-dark bg-cover bg-center px-4 py-10 text-center text-brand-cream sm:py-14" style="background-image: url('{{ $heroUrl }}')">
    <div class="absolute inset-0 -z-10 bg-brand-ink/65"></div>

    <img src="{{ $logoUrl }}" alt="{{ __('site.hero.logo_alt', ['name' => config('restaurant.name')]) }}" width="160" height="160"
        class="mx-auto size-32 rounded-full border-4 border-brand-gold object-cover shadow-lg sm:size-40">

    <h1 class="mt-5 font-display text-4xl font-bold text-brand-gold sm:text-5xl">{{ config('restaurant.name') }}</h1>
    <p class="mt-2 text-lg text-brand-gold-light">{{ __('site.tagline') }}</p>
    <p class="mx-auto mt-4 max-w-md">{{ __('site.intro') }}</p>

    <a href="tel:{{ config('restaurant.phone.link') }}" class="mt-6 inline-block rounded-full bg-brand-gold px-6 py-3 font-semibold text-brand-ink">
        {{ __('site.call') }}: {{ config('restaurant.phone.display') }}
    </a>

    <div class="mt-6 text-sm">
        <span @class([
            'inline-block rounded-full px-3 py-1 font-semibold',
            'bg-brand-gold-light text-brand-ink' => $isOpen,
            'bg-brand-red-dark text-brand-gold-light' => ! $isOpen,
        ])>
            {{ $isOpen ? __('site.hero.open_now') : __('site.hero.closed_now') }}
        </span>

        <p class="mt-2 text-brand-gold-light">
            @if ($todayRanges)
                {{ __('site.hero.today') }}:
                @foreach ($todayRanges as [$from, $to])
                    <span class="whitespace-nowrap">{{ __('site.hours_range', ['from' => $from, 'to' => $to]) }}</span>@if (! $loop->last) · @endif
                @endforeach
            @else
                {{ __('site.hero.closed_today') }}
            @endif
        </p>
    </div>
</section>
