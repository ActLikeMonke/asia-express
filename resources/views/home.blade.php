@extends('layouts.app')

@section('content')
    <section class="bg-brand-red px-4 py-12 text-center text-brand-cream">
        <h1 class="font-display text-4xl font-bold text-brand-gold sm:text-5xl">{{ config('restaurant.name') }}</h1>
        <p class="mt-2 text-lg text-brand-gold-light">{{ __('site.tagline') }}</p>
        <p class="mx-auto mt-4 max-w-md">{{ __('site.intro') }}</p>

        <a href="tel:{{ config('restaurant.phone.link') }}" class="mt-6 inline-block rounded-full bg-brand-gold px-6 py-3 font-semibold text-brand-ink">
            {{ __('site.call') }}: {{ config('restaurant.phone.display') }}
        </a>
    </section>

    <section class="mx-auto max-w-md px-4 py-10">
        <h2 class="font-display text-2xl font-bold text-brand-red">{{ __('site.opening_hours') }}</h2>

        <dl class="mt-4 divide-y divide-brand-ink/10">
            @foreach (config('restaurant.hours') as $day => $ranges)
                <div class="flex justify-between gap-4 py-2">
                    <dt class="font-medium">{{ __('site.days.'.$day) }}</dt>
                    <dd class="text-right">
                        @foreach ($ranges as [$from, $to])
                            <div>{{ __('site.hours_range', ['from' => $from, 'to' => $to]) }}</div>
                        @endforeach
                    </dd>
                </div>
            @endforeach

            <div class="flex justify-between gap-4 py-2">
                <dt class="font-medium">{{ __('site.holidays') }}</dt>
                <dd class="text-right">
                    @foreach (config('restaurant.holiday_hours') as [$from, $to])
                        <div>{{ __('site.hours_range', ['from' => $from, 'to' => $to]) }}</div>
                    @endforeach
                </dd>
            </div>
        </dl>
    </section>
@endsection
