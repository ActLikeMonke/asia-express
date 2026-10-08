<?php

namespace App\Livewire;

use App\Mail\PreorderReceived;
use App\Models\MenuItem;
use App\Support\OpeningHours;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class PreorderForm extends Component
{
    private const PICKUP_FORMAT = 'Y-m-d\TH:i';

    private const MAX_QUANTITY = 20;

    /**
     * Menu item id => quantity. Mirrors the cart kept in the browser (see layouts/app.blade.php).
     *
     * @var array<int, int>
     */
    public array $cart = [];

    public string $name = '';

    public string $phone = '';

    // "now": as soon as possible (ready after the lead time), "later": at the time in $pickup_at.
    public string $pickup_mode = 'now';

    public string $pickup_at = '';

    public string $note = '';

    public bool $privacy = false;

    // Honeypot: hidden from people, bots tend to fill it in.
    public string $website = '';

    public bool $sent = false;

    // Whether the order just sent was for "now" (for the success message).
    public bool $sentForNow = false;

    public function mount(): void
    {
        if (! $this->nowAvailable()) {
            $this->pickup_mode = 'later';
        }
    }

    /**
     * The browser keeps the cart and always sends it as a whole, so quick taps
     * cannot get lost when requests overlap. Only existing dishes are accepted.
     *
     * @param  array<int|string, mixed>  $cart
     */
    #[On('cart-set')]
    public function setCart(array $cart): void
    {
        $ids = MenuItem::whereIn('id', array_filter(array_keys($cart), 'is_numeric'))->pluck('id');

        $this->cart = [];

        foreach ($ids as $id) {
            $quantity = is_numeric($cart[$id]) ? (int) $cart[$id] : 0;

            if ($quantity >= 1) {
                $this->cart[$id] = min($quantity, self::MAX_QUANTITY);
            }
        }

        if ($this->cart !== []) {
            $this->sent = false;
        }
    }

    public function submit(): void
    {
        // Pretend success so that bots do not learn anything.
        if ($this->website !== '') {
            $this->sent = true;

            return;
        }

        $rateLimitKey = 'preorder:'.request()->ip();

        if (RateLimiter::tooManyAttempts($rateLimitKey, config('restaurant.preorder.max_per_hour_per_ip'))) {
            $this->addError('form', __('preorder.errors.rate_limit'));

            return;
        }

        $data = $this->validate();
        $lines = $this->lines();

        // The cart comes from the browser: only accept dishes that exist.
        if ($lines->count() !== count($this->cart)) {
            $this->addError('cart', __('preorder.errors.cart_invalid'));

            return;
        }

        try {
            $this->send($data, $lines);
        } catch (Throwable $exception) {
            // Mail server unreachable or misconfigured: keep the form and ask the guest to call instead.
            report($exception);
            $this->addError('form', __('preorder.errors.send_failed', ['phone' => config('restaurant.phone.display')]));

            return;
        }

        // Only orders that were really sent count towards the limit.
        RateLimiter::hit($rateLimitKey, 3600);

        $this->reset();
        $this->sent = true;
        $this->sentForNow = $data['pickup_mode'] === 'now';
        $this->dispatch('cart-cleared');
    }

    /**
     * @param  array<string, mixed>  $data
     * @param  Collection<int, array{item: MenuItem, quantity: int, total_cents: int}>  $lines
     */
    private function send(array $data, Collection $lines): void
    {
        // The restaurant reads the mail in German, whatever language the guest used.
        Mail::to($this->recipient())->locale(config('restaurant.locales')[0])->send(new PreorderReceived(
            name: $data['name'],
            phone: $data['phone'],
            lines: $lines->map(fn (array $line) => [
                'quantity' => $line['quantity'],
                'number' => $line['item']->number,
                'category' => $line['item']->category->name_de,
                'name' => $line['item']->name_de,
                'description' => $line['item']->description_de,
                'total_cents' => $line['total_cents'],
            ])->all(),
            totalCents: $lines->sum('total_cents'),
            pickupAt: $data['pickup_mode'] === 'now' ? $this->readyAt() : $this->pickupTime($data['pickup_at']),
            note: $data['note'] ?: null,
            asap: $data['pickup_mode'] === 'now',
        ));
    }

    public function startOver(): void
    {
        $this->reset();
        $this->mount();
        $this->dispatch('cart-cleared');
    }

    public function render(): View
    {
        $now = app(OpeningHours::class)->now();
        $lines = $this->lines();

        return view('livewire.preorder-form', [
            'lines' => $lines,
            'totalCents' => $lines->sum('total_cents'),
            'nowAvailable' => $this->nowAvailable(),
            'minPickup' => $now->addMinutes(config('restaurant.preorder.min_lead_minutes'))->format(self::PICKUP_FORMAT),
            'maxPickup' => $now->addDays(config('restaurant.preorder.max_days_ahead'))->endOfDay()->format(self::PICKUP_FORMAT),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function rules(): array
    {
        return [
            'cart' => ['required', 'array', 'min:1'],
            'cart.*' => ['integer', 'min:1', 'max:'.self::MAX_QUANTITY],
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[0-9 +()\/-]{5,25}$/'],
            'pickup_mode' => ['required', 'in:now,later', function (string $attribute, mixed $value, Closure $fail): void {
                if ($value === 'now' && ! $this->nowAvailable()) {
                    $fail(__('preorder.errors.now_closed'));
                }
            }],
            // "bail": the closure can only parse values in the expected format.
            'pickup_at' => $this->pickup_mode === 'now'
                ? ['nullable']
                : ['bail', 'required', 'date_format:'.self::PICKUP_FORMAT, $this->pickupRule()],
            'note' => ['nullable', 'string', 'max:500'],
            'privacy' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'cart.required' => __('preorder.errors.cart_empty'),
            'cart.min' => __('preorder.errors.cart_empty'),
            'cart.*' => __('preorder.errors.cart_invalid'),
            'name.required' => __('preorder.errors.name_required'),
            'name.max' => __('preorder.errors.too_long'),
            'phone.required' => __('preorder.errors.phone_required'),
            'phone.regex' => __('preorder.errors.phone_invalid'),
            'pickup_at.required' => __('preorder.errors.pickup_required'),
            'pickup_at.date_format' => __('preorder.errors.pickup_required'),
            'note.max' => __('preorder.errors.too_long'),
            'privacy.accepted' => __('preorder.errors.privacy_required'),
        ];
    }

    /**
     * Cart as lines with the dish from the database, in menu order.
     *
     * @return Collection<int, array{item: MenuItem, quantity: int, total_cents: int}>
     */
    private function lines(): Collection
    {
        if ($this->cart === []) {
            return collect();
        }

        return MenuItem::with('category')
            ->whereIn('id', array_keys($this->cart))
            ->get()
            ->sortBy(fn (MenuItem $item) => [$item->category->sort_order, $item->sort_order, $item->id])
            ->values()
            ->map(fn (MenuItem $item) => [
                'item' => $item,
                'quantity' => (int) $this->cart[$item->id],
                'total_cents' => (int) $this->cart[$item->id] * $item->price_cents,
            ]);
    }

    /**
     * When an order placed right now is ready for pickup.
     */
    private function readyAt(): CarbonImmutable
    {
        return app(OpeningHours::class)->now()->addMinutes(config('restaurant.preorder.min_lead_minutes'))->startOfMinute();
    }

    /**
     * Ordering for "now" needs the restaurant to be open now and still open when the food is ready.
     */
    private function nowAvailable(): bool
    {
        if (config('restaurant.preorder.demo_order_now_anytime')) {
            return true;
        }

        $openingHours = app(OpeningHours::class);

        return $openingHours->isOpenAt($openingHours->now()) && $openingHours->isOpenAt($this->readyAt());
    }

    /**
     * Pickup must respect the lead time, the booking window and the opening hours.
     */
    private function pickupRule(): Closure
    {
        return function (string $attribute, mixed $value, Closure $fail): void {
            $openingHours = app(OpeningHours::class);
            $now = $openingHours->now();
            $pickup = $this->pickupTime($value);
            $leadMinutes = config('restaurant.preorder.min_lead_minutes');

            if ($pickup->lt($now->addMinutes($leadMinutes)->startOfMinute())) {
                $fail(__('preorder.errors.pickup_too_soon', ['minutes' => $leadMinutes]));
            } elseif ($pickup->gt($now->addDays(config('restaurant.preorder.max_days_ahead'))->endOfDay())) {
                $fail(__('preorder.errors.pickup_too_late', ['days' => config('restaurant.preorder.max_days_ahead')]));
            } elseif (! $openingHours->isOpenAt($pickup)) {
                $fail(__('preorder.errors.pickup_closed'));
            }
        };
    }

    private function pickupTime(string $value): CarbonImmutable
    {
        return CarbonImmutable::createFromFormat(self::PICKUP_FORMAT, $value, config('restaurant.timezone'))->startOfMinute();
    }

    /**
     * Until the owner has named an address (question 2), fall back to the sender
     * address so that the demo works with MAIL_MAILER=log.
     */
    private function recipient(): string
    {
        return config('restaurant.order_email') ?: config('mail.from.address');
    }
}
