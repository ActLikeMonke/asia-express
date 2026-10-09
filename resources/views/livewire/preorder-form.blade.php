<div>
    @if ($sent)
        {{-- The long form collapses into this short box, so bring it back into view. --}}
        <div role="status" x-init="$nextTick(() => $el.scrollIntoView({ block: 'center' }))" class="rounded-lg border border-brand-gold bg-brand-gold-light/40 p-6 text-center">
            <p class="font-display text-xl font-bold text-brand-red">{{ __('preorder.success.title') }}</p>
            <p class="mt-2">{{ __('preorder.success.text') }}</p>
            @if ($sentForNow)
                <p class="mt-2 font-medium">{{ __('preorder.now_info', ['minutes' => config('restaurant.preorder.min_lead_minutes')]) }}</p>
            @endif
            <p class="mt-2">
                {{ __('preorder.success.questions') }}
                <a href="tel:{{ config('restaurant.phone.link') }}" class="font-medium text-brand-red underline underline-offset-4">{{ config('restaurant.phone.display') }}</a>
            </p>

            <button type="button" wire:click="startOver" class="mt-4 rounded-full border border-brand-red px-5 py-2 font-semibold text-brand-red">
                {{ __('preorder.success.again') }}
            </button>
        </div>
    @elseif ($lines->isEmpty())
        <div class="rounded-lg border border-dashed border-brand-ink/30 p-6 text-center">
            <p>{{ __('preorder.cart.empty') }}</p>
            <a href="#menu" class="mt-4 inline-block rounded-full bg-brand-red px-5 py-2 font-semibold text-brand-cream">{{ __('preorder.cart.to_menu') }}</a>
            @error('cart') <p class="mt-3 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror
        </div>
    @else
        <form wire:submit="submit" novalidate class="space-y-5">
            @error('form')
                <p role="alert" class="rounded-lg border border-brand-red bg-brand-cream p-3 font-medium text-brand-red">{{ $message }}</p>
            @enderror

            <div>
                <ul class="divide-y divide-brand-ink/10 rounded-lg border border-brand-ink/20">
                    @foreach ($lines as $line)
                        <li wire:key="line-{{ $line['item']->id }}" class="flex items-center gap-3 p-3">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium">
                                    @if ($line['item']->number)<span class="text-sm tabular-nums text-brand-ink/60">{{ $line['item']->number }}</span>@endif
                                    {{ $line['item']->name }}
                                </p>
                                <p class="text-sm text-brand-ink/70">{{ $line['item']->category->name }}@if ($line['item']->description) · {{ $line['item']->description }}@endif</p>
                                <p class="mt-1 text-sm font-semibold tabular-nums">{{ Number::currency($line['total_cents'] / 100, in: 'EUR', locale: app()->getLocale()) }}</p>
                            </div>

                            <div class="flex shrink-0 items-center gap-1">
                                <button type="button" @click="change({{ $line['item']->id }}, -1)" aria-label="{{ __('preorder.cart.less', ['name' => $line['item']->name]) }}"
                                    class="size-10 rounded-full border border-brand-red text-xl font-semibold leading-none text-brand-red">−</button>
                                <span class="w-7 text-center font-semibold tabular-nums">{{ $line['quantity'] }}</span>
                                <button type="button" @click="change({{ $line['item']->id }}, 1)" aria-label="{{ __('preorder.cart.more', ['name' => $line['item']->name]) }}"
                                    class="size-10 rounded-full border border-brand-red text-xl font-semibold leading-none text-brand-red">+</button>
                            </div>
                        </li>
                    @endforeach

                    <li class="flex justify-between gap-3 p-3 font-semibold">
                        <span>{{ __('preorder.cart.total') }}</span>
                        <span class="tabular-nums">{{ Number::currency($totalCents / 100, in: 'EUR', locale: app()->getLocale()) }}</span>
                    </li>
                </ul>
                <p class="mt-1 text-sm text-brand-ink/70">{{ __('preorder.cart.help') }}</p>
                @error('cart') <p class="mt-1 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror
            </div>

            <fieldset>
                <legend class="block font-medium">{{ __('preorder.fields.pickup_mode') }}</legend>

                <div class="mt-1 grid grid-cols-2 gap-2">
                    <label @class(['flex items-center gap-2 rounded-lg border border-brand-ink/30 px-3 py-2 has-checked:border-brand-red has-checked:bg-brand-gold-light/40', 'opacity-50' => ! $nowAvailable])>
                        <input type="radio" wire:model.live="pickup_mode" value="now" @disabled(! $nowAvailable) class="size-4 shrink-0 accent-brand-red">
                        <span class="font-medium">{{ __('preorder.fields.pickup_now') }}</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-lg border border-brand-ink/30 px-3 py-2 has-checked:border-brand-red has-checked:bg-brand-gold-light/40">
                        <input type="radio" wire:model.live="pickup_mode" value="later" class="size-4 shrink-0 accent-brand-red">
                        <span class="font-medium">{{ __('preorder.fields.pickup_later') }}</span>
                    </label>
                </div>

                @if (! $nowAvailable)
                    <p class="mt-1 text-sm text-brand-ink/70">{{ __('preorder.fields.now_closed') }}</p>
                @endif
                @error('pickup_mode') <p class="mt-1 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror

                @if ($pickup_mode === 'now')
                    @if ($nowAvailable)
                        <p class="mt-3 rounded-lg bg-brand-gold-light/40 p-3 font-medium">{{ __('preorder.now_info', ['minutes' => config('restaurant.preorder.min_lead_minutes')]) }}</p>
                    @endif
                @else
                    <div class="mt-3">
                        <label for="preorder-pickup" class="block font-medium">{{ __('preorder.fields.pickup_at') }}</label>
                        <input id="preorder-pickup" type="datetime-local" wire:model="pickup_at" min="{{ $minPickup }}" max="{{ $maxPickup }}" required
                            class="mt-1 block w-full rounded-lg border border-brand-ink/30 bg-brand-cream px-3 py-2 focus:border-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red/30">
                        <p class="mt-1 text-sm text-brand-ink/70">{{ __('preorder.fields.pickup_help', ['minutes' => config('restaurant.preorder.min_lead_minutes')]) }}</p>
                        @error('pickup_at') <p class="mt-1 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror
                    </div>
                @endif
            </fieldset>

            <div>
                <label for="preorder-name" class="block font-medium">{{ __('preorder.fields.name') }}</label>
                <input id="preorder-name" type="text" wire:model="name" autocomplete="name" maxlength="100" required
                    class="mt-1 block w-full rounded-lg border border-brand-ink/30 bg-brand-cream px-3 py-2 focus:border-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red/30">
                @error('name') <p class="mt-1 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="preorder-phone" class="block font-medium">{{ __('preorder.fields.phone') }}</label>
                <input id="preorder-phone" type="tel" wire:model="phone" autocomplete="tel" maxlength="25" required
                    class="mt-1 block w-full rounded-lg border border-brand-ink/30 bg-brand-cream px-3 py-2 focus:border-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red/30">
                <p class="mt-1 text-sm text-brand-ink/70">{{ __('preorder.fields.phone_help') }}</p>
                @error('phone') <p class="mt-1 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="preorder-note" class="block font-medium">{{ __('preorder.fields.note') }}</label>
                <textarea id="preorder-note" wire:model="note" rows="2" maxlength="500"
                    class="mt-1 block w-full rounded-lg border border-brand-ink/30 bg-brand-cream px-3 py-2 focus:border-brand-red focus:outline-none focus:ring-2 focus:ring-brand-red/30"></textarea>
                @error('note') <p class="mt-1 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror
            </div>

            {{-- Honeypot: not visible and not reachable by keyboard. --}}
            <div class="hidden" aria-hidden="true">
                <label for="preorder-website">Website</label>
                <input id="preorder-website" type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            <div>
                <label class="flex items-start gap-3">
                    <input type="checkbox" wire:model="privacy" required class="mt-1 size-5 shrink-0 accent-brand-red">
                    <span class="text-sm">{{ __('preorder.fields.privacy') }}</span>
                </label>
                @error('privacy') <p class="mt-1 text-sm font-medium text-brand-red">{{ $message }}</p> @enderror
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="submit" class="w-full rounded-full bg-brand-red px-6 py-3 font-semibold text-brand-cream disabled:opacity-60 sm:w-auto">
                <span wire:loading.remove wire:target="submit">{{ __('preorder.submit') }}</span>
                <span wire:loading wire:target="submit">{{ __('preorder.sending') }}</span>
            </button>
        </form>
    @endif
</div>
