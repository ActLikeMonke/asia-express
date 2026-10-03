<section id="menu" class="mx-auto max-w-3xl scroll-mt-24 px-4 py-12">
    <h2 class="font-display text-3xl font-bold text-brand-red">{{ __('site.menu.title') }}</h2>

    @if ($categories->isEmpty())
        <p class="mt-4">{{ __('site.menu.empty') }}</p>
    @else
        <p class="mt-2 text-sm text-brand-ink/70">{{ __('site.menu.note') }}</p>

        <nav aria-label="{{ __('site.menu.categories') }}" class="-mx-4 mt-5 overflow-x-auto px-4">
            <ul class="flex gap-2 pb-2 sm:flex-wrap">
                @foreach ($categories as $category)
                    <li>
                        <a href="#menu-{{ $category->id }}" class="block whitespace-nowrap rounded-full border border-brand-red px-3 py-1 text-sm font-medium text-brand-red hover:bg-brand-red hover:text-brand-cream">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        @foreach ($categories as $category)
            <section id="menu-{{ $category->id }}" class="scroll-mt-24 pt-8">
                <h3 class="border-b-2 border-brand-gold pb-1 font-display text-xl font-bold text-brand-red">{{ $category->name }}</h3>

                <ul class="divide-y divide-brand-ink/10">
                    @foreach ($category->items as $item)
                        <li class="flex gap-3 py-3">
                            <span class="w-7 shrink-0 text-sm leading-6 tabular-nums text-brand-ink/60">{{ $item->number }}</span>

                            <div class="min-w-0 flex-1">
                                <p class="font-medium">
                                    {{ $item->name }}
                                    @if ($item->is_spicy)
                                        <span class="text-xs font-semibold uppercase tracking-wide text-brand-red">{{ __('menu.spicy') }}</span>
                                    @endif
                                    @if ($item->allergens)
                                        <sup class="text-xs font-normal text-brand-ink/60">{{ implode(', ', $item->allergens) }}</sup>
                                    @endif
                                </p>
                                @if ($item->description)
                                    <p class="text-sm text-brand-ink/70">{{ $item->description }}</p>
                                @endif
                            </div>

                            <span class="shrink-0 whitespace-nowrap text-right font-semibold tabular-nums">{{ $item->formatted_price }}</span>
                        </li>
                    @endforeach
                </ul>
            </section>
        @endforeach

        <div class="mt-10 rounded-lg bg-brand-gold-light/40 p-4 text-sm">
            <h3 class="font-semibold">{{ __('menu.additives_title') }}</h3>
            <p class="mt-1 text-brand-ink/80">
                @foreach (__('menu.additives') as $code => $label)
                    <span class="whitespace-nowrap"><strong>{{ $code }}</strong> {{ $label }}</span>@if (! $loop->last) · @endif
                @endforeach
            </p>

            <h3 class="mt-3 font-semibold">{{ __('menu.allergens_title') }}</h3>
            <p class="mt-1 text-brand-ink/80">
                @foreach (__('menu.allergens') as $code => $label)
                    <span class="whitespace-nowrap"><strong>{{ $code }}</strong> {{ $label }}</span>@if (! $loop->last) · @endif
                @endforeach
            </p>
        </div>
    @endif
</section>
