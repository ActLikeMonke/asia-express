@php
    $address = config('restaurant.address.street').', '.config('restaurant.address.postal_code').' '.config('restaurant.address.city');
    $mapQuery = urlencode(config('restaurant.name').', '.$address);
@endphp

<section id="contact" class="mx-auto max-w-5xl scroll-mt-24 px-4 py-12">
    <h2 class="font-display text-3xl font-bold text-brand-red">{{ __('site.contact.title') }}</h2>

    <div class="mt-6 grid gap-10 md:grid-cols-2">
        <div>
            <h3 class="font-semibold">{{ __('site.contact.address') }}</h3>
            <address class="mt-1 not-italic">
                {{ config('restaurant.name') }}<br>
                {{ config('restaurant.address.street') }}<br>
                {{ config('restaurant.address.postal_code') }} {{ config('restaurant.address.city') }}
            </address>

            <h3 class="mt-5 font-semibold">{{ __('site.contact.phone') }}</h3>
            <p class="mt-1">
                <a href="tel:{{ config('restaurant.phone.link') }}" class="font-medium text-brand-red underline underline-offset-4">{{ config('restaurant.phone.display') }}</a>
            </p>

            <h3 class="mt-5 font-semibold">{{ __('site.opening_hours') }}</h3>
            <dl class="mt-1 divide-y divide-brand-ink/10">
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
        </div>

        <div>
            {{-- No external request until the visitor clicks the button (see resources/js/app.js). --}}
            <div data-map data-src="https://www.google.com/maps?q={{ $mapQuery }}&output=embed" data-title="{{ __('site.contact.map_title', ['address' => $address]) }}"
                class="flex aspect-4/3 w-full flex-col items-center justify-center gap-4 overflow-hidden rounded-lg border border-brand-gold bg-brand-gold-light/40 p-6 text-center">
                <p class="max-w-xs text-sm">{{ __('site.contact.map_notice') }}</p>
                <button type="button" data-map-load class="rounded-full bg-brand-red px-5 py-2 font-semibold text-brand-cream">
                    {{ __('site.contact.map_load') }}
                </button>
            </div>

            <p class="mt-3 text-sm">
                <a href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}" target="_blank" rel="noopener noreferrer" class="font-medium text-brand-red underline underline-offset-4">
                    {{ __('site.contact.map_open') }}
                </a>
            </p>
        </div>
    </div>
</section>
