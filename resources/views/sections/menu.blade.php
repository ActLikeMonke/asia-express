{{-- One category is shown at a time ("all" shows the whole menu); tapping a dish adds it to the pre-order (cart state lives on <body>). --}}
<section id="menu" class="mx-auto max-w-3xl scroll-mt-24 px-4 py-12"
    x-data="{ active: {{ $categories->first()?->id ?? 'null' }}, show(id) { this.active = id; this.$nextTick(() => this.$root.scrollIntoView()) } }">
    <h2 class="font-display text-3xl font-bold text-brand-red">{{ __('site.menu.title') }}</h2>

    @if ($categories->isEmpty())
        <p class="mt-4">{{ __('site.menu.empty') }}</p>
    @else
        <p class="mt-2 text-sm text-brand-ink/70">{{ __('site.menu.order_hint') }}</p>

        <nav aria-label="{{ __('site.menu.categories') }}" class="-mx-4 mt-5 overflow-x-auto px-4">
            <ul class="flex gap-2 pb-2 sm:flex-wrap">
                <li>
                    <button type="button" @click="active = 'all'" :aria-pressed="active === 'all'"
                        class="block whitespace-nowrap rounded-full border border-brand-red px-3 py-1 text-sm font-semibold"
                        :class="active === 'all' ? 'bg-brand-red text-brand-cream' : 'text-brand-red'">
                        {{ __('site.menu.all') }}
                    </button>
                </li>
                @foreach ($categories as $category)
                    <li>
                        <button type="button" @click="active = {{ $category->id }}" :aria-pressed="active === {{ $category->id }}"
                            class="block whitespace-nowrap rounded-full border border-brand-red px-3 py-1 text-sm font-medium"
                            :class="active === {{ $category->id }} ? 'bg-brand-red text-brand-cream' : 'text-brand-red'">
                            {{ $category->name }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </nav>

        @foreach ($categories as $category)
            <section id="menu-{{ $category->id }}" class="pt-6" x-show="active === 'all' || active === {{ $category->id }}" @if (! $loop->first) x-cloak @endif>
                <h3 class="border-b-2 border-brand-gold pb-1 font-display text-xl font-bold text-brand-red">{{ $category->name }}</h3>

                <ul class="divide-y divide-brand-ink/10">
                    @foreach ($category->items as $item)
                        {{-- The whole row adds the dish (the button's click bubbles up); the minus is a button of its own. --}}
                        <li class="flex cursor-pointer gap-3 py-3" @click="change({{ $item->id }}, 1)">
                            <button type="button" class="flex min-w-0 flex-1 gap-3 text-left"
                                aria-label="{{ __('site.menu.add', ['name' => trim($item->number.' '.$item->name)]) }}">
                                <span class="w-7 shrink-0 text-sm leading-6 tabular-nums text-brand-ink/60">{{ $item->number }}</span>

                                <span class="min-w-0 flex-1">
                                    <span class="block font-medium">
                                        {{ $item->name }}
                                        @if ($item->is_spicy)
                                            <span class="text-xs font-semibold uppercase tracking-wide text-brand-red">{{ __('menu.spicy') }}</span>
                                        @endif
                                        @if ($item->allergens)
                                            <sup class="text-xs font-normal text-brand-ink/60">{{ implode(', ', $item->allergens) }}</sup>
                                        @endif
                                    </span>
                                    @if ($item->description)
                                        <span class="block text-sm text-brand-ink/70">{{ $item->description }}</span>
                                    @endif
                                </span>
                            </button>

                            <div class="flex shrink-0 flex-col items-end gap-1">
                                <span class="whitespace-nowrap font-semibold tabular-nums">{{ $item->formatted_price }}</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" x-show="cart[{{ $item->id }}]" x-cloak @click.stop="change({{ $item->id }}, -1)"
                                        aria-label="{{ __('preorder.cart.less', ['name' => $item->name]) }}"
                                        class="size-8 rounded-full border border-brand-red text-lg font-semibold leading-none text-brand-red">−</button>
                                    <span class="flex h-8 min-w-8 items-center justify-center rounded-full px-2 text-sm font-semibold tabular-nums"
                                        :class="cart[{{ $item->id }}] ? 'bg-brand-red text-brand-cream' : 'border border-brand-red text-brand-red'"
                                        x-text="cart[{{ $item->id }}] ? cart[{{ $item->id }}] + ' ×' : '+'">+</span>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-6 flex justify-between gap-3" x-show="active !== 'all'">
                    @if (! $loop->first)
                        <button type="button" @click="show({{ $categories[$loop->index - 1]->id }})" class="rounded-full border border-brand-red px-4 py-2 text-sm font-semibold text-brand-red">
                            ← {{ $categories[$loop->index - 1]->name }}
                        </button>
                    @endif
                    @if (! $loop->last)
                        <button type="button" @click="show({{ $categories[$loop->index + 1]->id }})" class="ml-auto rounded-full bg-brand-red px-4 py-2 text-sm font-semibold text-brand-cream">
                            {{ $categories[$loop->index + 1]->name }} →
                        </button>
                    @endif
                </div>
            </section>
        @endforeach

        <div class="mt-6 text-center" x-show="count > 0" x-cloak>
            <a href="#preorder" class="inline-block rounded-full bg-brand-gold px-6 py-3 font-semibold text-brand-ink">
                {{ __('site.nav.checkout') }} (<span x-text="count"></span>)
            </a>
        </div>

        <div class="mt-10 rounded-lg bg-brand-gold-light/40 p-4 text-sm">
            <p class="text-brand-ink/80">{{ __('site.menu.note') }}</p>

            <h3 class="mt-3 font-semibold">{{ __('menu.additives_title') }}</h3>
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
