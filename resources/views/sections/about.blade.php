<section id="about" class="scroll-mt-24 bg-brand-red px-4 py-12 text-brand-cream">
    <div class="mx-auto max-w-3xl">
        <h2 class="font-display text-3xl font-bold text-brand-gold">{{ __('site.about.title') }}</h2>
        <p class="mt-4">{{ __('site.about.text', ['name' => config('restaurant.name')]) }}</p>
        <p class="mt-3">{{ __('site.about.pickup') }}</p>

        <a href="tel:{{ config('restaurant.phone.link') }}" class="mt-6 inline-block rounded-full bg-brand-gold px-6 py-3 font-semibold text-brand-ink">
            {{ __('site.call') }}: {{ config('restaurant.phone.display') }}
        </a>
    </div>
</section>
