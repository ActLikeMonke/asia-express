<?php

// Facts come from docs/RESTAURANT.md. Do not add anything here that is not confirmed there.

return [

    'name' => 'Asia Express',

    'owner' => 'Van Thu Nguyen',

    'address' => [
        'street' => 'Kaiserstraße 85',
        'postal_code' => '52146',
        'city' => 'Würselen',
    ],

    'phone' => [
        'display' => '02405 4719004',
        'link' => '+4924054719004',
    ],

    // Recipient of pre-orders. Still open (question 2 in docs/OPEN_QUESTIONS.md).
    'order_email' => env('RESTAURANT_ORDER_EMAIL'),

    // Pre-order rules. Demo values, not confirmed by the owner yet (question 3 in docs/OPEN_QUESTIONS.md).
    'preorder' => [
        'min_lead_minutes' => 20,
        'max_days_ahead' => 7,
        'max_per_hour_per_ip' => 5,
        // Demo only: allow "order now" outside the opening hours. Must stay off in production.
        'demo_order_now_anytime' => (bool) env('RESTAURANT_DEMO_ORDER_NOW_ANYTIME', false),
    ],

    'timezone' => 'Europe/Berlin',

    // First entry is the default locale and has no URL prefix.
    'locales' => ['de', 'en'],

    /*
    | Opening hours per ISO weekday (1 = Monday … 7 = Sunday), each a list of
    | [open, close] ranges. No closed day.
    */
    'hours' => [
        1 => [['11:30', '15:00'], ['17:00', '22:00']],
        2 => [['11:30', '15:00'], ['17:00', '22:00']],
        3 => [['11:30', '15:00'], ['17:00', '22:00']],
        4 => [['11:30', '15:00'], ['17:00', '22:00']],
        5 => [['11:30', '15:00'], ['17:00', '22:00']],
        6 => [['17:00', '22:00']],
        7 => [['11:30', '15:00'], ['17:00', '22:00']],
    ],

    // Public holidays replace the weekday's hours.
    'holiday_hours' => [['17:00', '22:00']],

];
