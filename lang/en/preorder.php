<?php

return [

    'title' => 'Your order',
    'intro' => 'Order ahead and pick up your meal at our restaurant.',
    'submit' => 'Send order',
    'sending' => 'Sending …',
    'now_info' => 'Your food will be ready for pickup in about :minutes minutes.',

    'cart' => [
        'empty' => 'Your order is still empty. Tap a dish in the menu to add it.',
        'to_menu' => 'Go to the menu',
        'help' => 'Add more dishes in the menu.',
        'total' => 'Total',
        'less' => 'One :name less',
        'more' => 'One more :name',
    ],

    'fields' => [
        'name' => 'Name',
        'phone' => 'Phone number',
        'phone_help' => 'In case we have questions about your order.',
        'pickup_mode' => 'Pickup',
        'pickup_now' => 'Order now',
        'pickup_later' => 'For later',
        'now_closed' => 'We are closed right now. Please choose a later pickup time.',
        'pickup_at' => 'Pickup time',
        'pickup_help' => 'During opening hours only, at the earliest in :minutes minutes.',
        'note' => 'Note (optional)',
        'privacy' => 'I agree that my details are used to process the pre-order.',
    ],

    'errors' => [
        'name_required' => 'Please enter your name.',
        'phone_required' => 'Please enter your phone number.',
        'phone_invalid' => 'Please enter a valid phone number.',
        'cart_empty' => 'Please choose at least one dish from the menu.',
        'cart_invalid' => 'Your order contains a dish that is no longer available. Please check your selection.',
        'pickup_required' => 'Please choose the date and time of pickup.',
        'pickup_too_soon' => 'Pickup is possible in :minutes minutes at the earliest.',
        'pickup_too_late' => 'Pre-orders can be placed at most :days days in advance.',
        'now_closed' => 'We are closed right now. Please choose “For later” and a pickup time.',
        'pickup_closed' => 'We are closed at that time. Please choose a time within our opening hours.',
        'privacy_required' => 'Please agree to the use of your details.',
        'too_long' => 'This entry is too long.',
        'send_failed' => 'Sorry, your order could not be sent. Please call us: :phone',
        'rate_limit' => 'Too many pre-orders in a short time. Please call us.',
    ],

    'success' => [
        'title' => 'Thank you!',
        'text' => 'We have received your pre-order.',
        'questions' => 'Questions or changes? Call us:',
        'again' => 'Another pre-order',
    ],

    'mail' => [
        'subject' => 'Pre-order: :name, pickup :time',
        'heading' => 'New pre-order',
        'pickup' => 'Pickup',
        'oclock' => '',
        'asap' => 'Now – as soon as possible',
        'about' => 'approx.',
        'items' => 'Dishes',
        'number' => 'No. :number',
        'total' => 'Total',
        'note' => 'Note',
        'customer' => 'Customer',
        'footer' => 'Sent via the pre-order form on the :name website.',
    ],

];
