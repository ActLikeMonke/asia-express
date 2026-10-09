<?php

namespace Tests\Feature;

use App\Livewire\PreorderForm;
use App\Mail\PreorderReceived;
use App\Models\MenuItem;
use Carbon\CarbonImmutable;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class PreorderFormTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Wednesday, 12:00 Berlin time: open until 15:00 and from 17:00 to 22:00.
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-07 12:00', 'Europe/Berlin'));
        config(['restaurant.order_email' => 'bestellung@example.com', 'restaurant.preorder.demo_order_now_anytime' => false]);
        $this->seed(MenuSeeder::class);
        Mail::fake();
    }

    private function itemId(string $number): int
    {
        return MenuItem::where('number', $number)->value('id');
    }

    /**
     * Form with 2 × no. 12 (11,00 €) and 1 × no. 49 (12,00 €) in the cart.
     */
    private function form(array $overrides = []): Testable
    {
        return Livewire::test(PreorderForm::class)->set(array_merge([
            'cart' => [$this->itemId('12') => 2, $this->itemId('49') => 1],
            'name' => 'Erika Mustermann',
            'phone' => '0170 1234567',
            'pickup_mode' => 'later',
            'pickup_at' => '2026-10-07T18:30',
            'note' => 'Bitte nicht zu scharf',
            'privacy' => true,
        ], $overrides));
    }

    public function test_ordering_for_now_is_the_default_and_ready_after_the_lead_time(): void
    {
        Livewire::test(PreorderForm::class)
            ->assertSet('pickup_mode', 'now')
            ->set('cart', [$this->itemId('12') => 1])
            ->assertSee('Ihr Essen ist in etwa 20 Minuten fertig zur Abholung.')
            ->assertDontSee('Abholzeit')
            ->set('pickup_mode', 'later')
            ->assertSee('Abholzeit')
            ->assertDontSee('Ihr Essen ist in etwa 20 Minuten');

        $this->form(['pickup_mode' => 'now', 'pickup_at' => ''])
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('sent', true)
            ->assertSee('Ihr Essen ist in etwa 20 Minuten fertig zur Abholung.');

        Mail::assertSent(PreorderReceived::class, function (PreorderReceived $mail) {
            return $mail->asap && $mail->pickupAt->format('Y-m-d H:i') === '2026-10-07 12:20';
        });
    }

    public function test_ordering_for_now_is_not_possible_while_closed(): void
    {
        // Afternoon break.
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-07 16:00', 'Europe/Berlin'));

        Livewire::test(PreorderForm::class)
            ->assertSet('pickup_mode', 'later')
            ->set('cart', [$this->itemId('12') => 1])
            ->assertSee('Wir haben gerade geschlossen.');

        $this->form(['pickup_mode' => 'now'])->call('submit')->assertHasErrors(['pickup_mode']);
        $this->form(['pickup_mode' => 'later', 'pickup_at' => '2026-10-07T18:00'])->call('submit')->assertHasNoErrors();

        // Ten minutes before closing the food would not be ready in time.
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-07 21:50', 'Europe/Berlin'));
        $this->form(['pickup_mode' => 'now'])->call('submit')->assertHasErrors(['pickup_mode']);

        Mail::assertSentCount(1);

        // Demo switch from .env: "now" works at any time.
        config(['restaurant.preorder.demo_order_now_anytime' => true]);
        Livewire::test(PreorderForm::class)->assertSet('pickup_mode', 'now');
        $this->form(['pickup_mode' => 'now'])->call('submit')->assertHasNoErrors();

        Mail::assertSentCount(2);
    }

    public function test_page_contains_menu_buttons_and_the_order_section(): void
    {
        $this->get('/')
            ->assertSee('id="preorder"', false)
            ->assertSee('href="#preorder"', false)
            ->assertSee('Bestellung abschließen')
            ->assertSee('@click="change('.$this->itemId('12').', 1)"', false)
            ->assertSeeLivewire(PreorderForm::class)
            ->assertSee('Ihre Bestellung ist noch leer.');

        $this->get('/en')->assertSee('Complete order')->assertSee('Your order is still empty.');
    }

    public function test_dishes_are_added_and_removed(): void
    {
        $rice = $this->itemId('12');
        $duck = $this->itemId('49');

        Livewire::test(PreorderForm::class)
            ->assertSee('Ihre Bestellung ist noch leer.')
            ->dispatch('cart-set', cart: [$rice => 2, $duck => 1])
            ->assertSet('cart', [$rice => 2, $duck => 1])
            ->assertSeeInOrder(['Gebratener Reis', 'Entengerichte', 'Summe', '34,00'])
            ->dispatch('cart-set', cart: [$duck => 1])
            ->assertSet('cart', [$duck => 1])
            ->assertSeeInOrder(['Entengerichte', 'Summe', '12,00'])
            ->dispatch('cart-set', cart: [])
            ->assertSet('cart', [])
            ->assertSee('Ihre Bestellung ist noch leer.');
    }

    public function test_unknown_dishes_and_huge_quantities_are_rejected(): void
    {
        $rice = $this->itemId('12');

        Livewire::test(PreorderForm::class)
            ->dispatch('cart-set', cart: [999999 => 1, 'abc' => 2, $rice => 500, $this->itemId('49') => 'viele'])
            ->assertSet('cart', [$rice => 20]);

        // Tampered cart from the browser.
        $this->form(['cart' => [999999 => 1]])->call('submit')->assertHasErrors(['cart']);
        $this->form(['cart' => [$this->itemId('12') => 500]])->call('submit')->assertHasErrors(['cart.'.$this->itemId('12')]);
        $this->form(['cart' => []])->call('submit')->assertHasErrors(['cart']);

        Mail::assertNothingSent();
    }

    public function test_valid_preorder_is_mailed_to_the_restaurant(): void
    {
        $this->form()
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('sent', true)
            ->assertSet('name', '')
            ->assertSet('cart', [])
            ->assertDispatched('cart-cleared')
            ->assertSee('Ihre Vorbestellung ist bei uns eingegangen.');

        Mail::assertSent(PreorderReceived::class, function (PreorderReceived $mail) {
            return $mail->hasTo('bestellung@example.com')
                && $mail->name === 'Erika Mustermann'
                && $mail->phone === '0170 1234567'
                && $mail->totalCents === 3400
                && $mail->lines[0] === ['quantity' => 2, 'number' => '12', 'category' => 'Gebratener Reis', 'name' => 'mit Hühnerfleisch', 'description' => 'Ei & Gemüse', 'total_cents' => 2200]
                && $mail->lines[1]['number'] === '49'
                && $mail->pickupAt->format('Y-m-d H:i e') === '2026-10-07 18:30 Europe/Berlin'
                && $mail->note === 'Bitte nicht zu scharf';
        });
        Mail::assertSentCount(1);
    }

    public function test_required_fields_are_validated(): void
    {
        Livewire::test(PreorderForm::class)
            ->call('submit')
            ->assertHasErrors(['cart', 'name', 'phone', 'privacy'])
            ->assertHasNoErrors(['note', 'pickup_at'])
            ->assertSet('sent', false);

        $this->form(['pickup_at' => ''])->call('submit')->assertHasErrors(['pickup_at']);
        $this->form(['pickup_mode' => 'irgendwann'])->call('submit')->assertHasErrors(['pickup_mode']);

        $this->form(['name' => ''])->call('submit')->assertHasErrors(['name'])->assertSee('Bitte geben Sie Ihren Namen an.');
        $this->form(['phone' => 'ruf mich an'])->call('submit')->assertHasErrors(['phone']);
        $this->form(['note' => ''])->call('submit')->assertHasNoErrors();

        Mail::assertSentCount(1);
    }

    public function test_pickup_must_be_within_opening_hours_and_lead_time(): void
    {
        // In 10 minutes: too soon (20 minutes lead time).
        $this->form(['pickup_at' => '2026-10-07T12:10'])->call('submit')
            ->assertHasErrors(['pickup_at'])->assertSee('frühestens in 20 Minuten');

        // Exactly at the lead time is fine.
        $this->form(['pickup_at' => '2026-10-07T12:20'])->call('submit')->assertHasNoErrors();

        // Afternoon break.
        $this->form(['pickup_at' => '2026-10-07T16:00'])->call('submit')
            ->assertHasErrors(['pickup_at'])->assertSee('haben wir geschlossen');

        // Saturday lunchtime is closed, Saturday evening is open.
        $this->form(['pickup_at' => '2026-10-10T12:30'])->call('submit')->assertHasErrors(['pickup_at']);
        $this->form(['pickup_at' => '2026-10-10T18:00'])->call('submit')->assertHasNoErrors();

        // In the past, too far ahead, nonsense.
        $this->form(['pickup_at' => '2026-10-06T18:00'])->call('submit')->assertHasErrors(['pickup_at']);
        $this->form(['pickup_at' => '2026-10-20T18:00'])->call('submit')->assertHasErrors(['pickup_at']);
        $this->form(['pickup_at' => 'morgen'])->call('submit')->assertHasErrors(['pickup_at']);

        Mail::assertSentCount(2);
    }

    public function test_honeypot_swallows_bot_submissions(): void
    {
        $this->form(['website' => 'https://spam.example'])
            ->call('submit')
            ->assertSet('sent', true);

        Mail::assertNothingSent();
    }

    public function test_preorders_are_rate_limited_per_ip(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->form()->call('submit')->assertHasNoErrors();
        }

        $this->form()->call('submit')
            ->assertHasErrors(['form'])
            ->assertSee('Zu viele Vorbestellungen')
            ->assertSet('sent', false);

        Mail::assertSentCount(5);
    }

    public function test_mail_failure_shows_a_message_and_keeps_the_order(): void
    {
        Mail::shouldReceive('to')->andThrow(new \RuntimeException('SMTP down'));

        // Failed attempts must not use up the rate limit.
        for ($i = 0; $i < 6; $i++) {
            $this->form()->call('submit')->assertSee('konnte leider nicht gesendet werden');
        }

        $this->form()->call('submit')
            ->assertHasErrors(['form'])
            ->assertSee('konnte leider nicht gesendet werden')
            ->assertSee(config('restaurant.phone.display'))
            ->assertSet('sent', false)
            ->assertSet('name', 'Erika Mustermann')
            ->assertSet('cart', [$this->itemId('12') => 2, $this->itemId('49') => 1]);
    }

    public function test_mail_is_german_and_readable(): void
    {
        app()->setLocale('en');

        $mail = (new PreorderReceived(
            name: 'Erika Mustermann',
            phone: '0170 / 123-4567',
            lines: [
                ['quantity' => 2, 'number' => '12', 'category' => 'Gebratener Reis', 'name' => 'mit Hühnerfleisch', 'description' => 'Ei & Gemüse', 'total_cents' => 2200],
                ['quantity' => 1, 'number' => null, 'category' => 'Getränke', 'name' => 'Cola', 'description' => '1 l', 'total_cents' => 300],
            ],
            totalCents: 2500,
            pickupAt: CarbonImmutable::parse('2026-10-07 18:30', 'Europe/Berlin'),
            note: 'Bitte nicht zu scharf',
        ))->locale('de');

        $mail->assertHasSubject('Vorbestellung: Erika Mustermann, Abholung Mi, 7.10., 18:30');
        $mail->assertSeeInOrderInHtml([
            'Neue Vorbestellung', 'Mittwoch, 7. Oktober 2026, 18:30 Uhr',
            '2 ×', 'Nr. 12 · Gebratener Reis', 'mit Hühnerfleisch', '22,00',
            '1 ×', 'Getränke', 'Cola', '3,00',
            'Summe', '25,00',
            'Bitte nicht zu scharf', 'Erika Mustermann',
        ]);
        $mail->assertSeeInHtml('href="tel:01701234567"', false);
        $mail->assertSeeInHtml('name="viewport"', false);
    }

    public function test_falls_back_to_sender_address_while_no_order_email_is_set(): void
    {
        config(['restaurant.order_email' => null, 'mail.from.address' => 'demo@example.com']);

        $this->form()->call('submit')->assertHasNoErrors();

        Mail::assertSent(PreorderReceived::class, fn (PreorderReceived $mail) => $mail->hasTo('demo@example.com'));
    }
}
