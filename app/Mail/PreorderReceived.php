<?php

namespace App\Mail;

use Carbon\CarbonImmutable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Number;

class PreorderReceived extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @param  list<array{quantity: int, number: ?string, category: string, name: string, description: ?string, total_cents: int}>  $lines
     */
    public function __construct(
        public string $name,
        public string $phone,
        public array $lines,
        public int $totalCents,
        public CarbonImmutable $pickupAt,
        public ?string $note = null,
        public bool $asap = false,
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('preorder.mail.subject', [
                'name' => $this->name,
                'time' => $this->pickupAt->isoFormat('dd, D.M., HH:mm'),
            ]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.preorder-received',
            with: [
                'pickup' => $this->pickupAt->isoFormat('dddd, D. MMMM YYYY, HH:mm'),
                'phoneLink' => preg_replace('/[^0-9+]/', '', $this->phone),
                'money' => fn (int $cents) => Number::currency($cents / 100, in: 'EUR', locale: app()->getLocale()),
            ],
        );
    }
}
