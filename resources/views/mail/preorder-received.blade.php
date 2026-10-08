<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ __('preorder.mail.heading') }}</title>
    </head>
    {{-- Inline styles only: mail clients ignore stylesheets. Colours are brand-red / brand-ink / brand-cream. --}}
    <body style="margin:0; padding:16px; background:#fdf6e3; color:#2a1708; font-family:Arial, Helvetica, sans-serif; font-size:18px; line-height:1.4;">
        <h1 style="margin:0 0 16px; font-size:22px; color:#a8121a;">{{ __('preorder.mail.heading') }}</h1>

        <p style="margin:0 0 4px; font-size:14px; text-transform:uppercase;">{{ __('preorder.mail.pickup') }}</p>
        @if ($asap)
            <p style="margin:0 0 4px; font-size:22px; font-weight:bold; color:#a8121a;">{{ __('preorder.mail.asap') }}</p>
        @endif
        <p style="margin:0 0 16px; font-size:22px; font-weight:bold;">{{ $asap ? __('preorder.mail.about') : '' }} {{ $pickup }} {{ __('preorder.mail.oclock') }}</p>

        <p style="margin:0 0 4px; font-size:14px; text-transform:uppercase;">{{ __('preorder.mail.items') }}</p>
        <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%; margin:0 0 16px; background:#ffffff; border-left:4px solid #a8121a; border-collapse:collapse;">
            @foreach ($lines as $line)
                <tr>
                    <td style="padding:10px 6px 10px 12px; vertical-align:top; white-space:nowrap; font-weight:bold; border-bottom:1px solid #fdf6e3;">{{ $line['quantity'] }} ×</td>
                    <td style="padding:10px 6px; vertical-align:top; border-bottom:1px solid #fdf6e3;">
                        <strong>@if ($line['number']){{ __('preorder.mail.number', ['number' => $line['number']]) }} · @endif{{ $line['category'] }}</strong><br>
                        {{ $line['name'] }}@if ($line['description']) <span style="font-size:15px;">{{ $line['description'] }}</span>@endif
                    </td>
                    <td style="padding:10px 12px 10px 6px; vertical-align:top; text-align:right; white-space:nowrap; border-bottom:1px solid #fdf6e3;">{{ $money($line['total_cents']) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" style="padding:10px 6px 10px 12px; font-weight:bold;">{{ __('preorder.mail.total') }}</td>
                <td style="padding:10px 12px 10px 6px; text-align:right; white-space:nowrap; font-weight:bold;">{{ $money($totalCents) }}</td>
            </tr>
        </table>

        @if ($note)
            <p style="margin:0 0 4px; font-size:14px; text-transform:uppercase;">{{ __('preorder.mail.note') }}</p>
            <p style="margin:0 0 16px; padding:12px; background:#ffffff; border-left:4px solid #e6b422; white-space:pre-line;">{{ $note }}</p>
        @endif

        <p style="margin:0 0 4px; font-size:14px; text-transform:uppercase;">{{ __('preorder.mail.customer') }}</p>
        <p style="margin:0 0 4px; font-weight:bold;">{{ $name }}</p>
        <p style="margin:0 0 16px;"><a href="tel:{{ $phoneLink }}" style="color:#a8121a; font-weight:bold;">{{ $phone }}</a></p>

        <p style="margin:0; font-size:13px;">{{ __('preorder.mail.footer', ['name' => config('restaurant.name')]) }}</p>
    </body>
</html>
