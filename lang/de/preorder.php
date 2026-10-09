<?php

return [

    'title' => 'Ihre Bestellung',
    'intro' => 'Bestellen Sie vor und holen Sie Ihr Essen bei uns ab.',
    'submit' => 'Bestellung absenden',
    'sending' => 'Wird gesendet …',
    'now_info' => 'Ihr Essen ist in etwa :minutes Minuten fertig zur Abholung.',

    'cart' => [
        'empty' => 'Ihre Bestellung ist noch leer. Tippen Sie in der Speisekarte auf ein Gericht, um es hinzuzufügen.',
        'to_menu' => 'Zur Speisekarte',
        'help' => 'Weitere Gerichte fügen Sie in der Speisekarte hinzu.',
        'total' => 'Summe',
        'less' => 'Ein :name weniger',
        'more' => 'Ein :name mehr',
    ],

    'fields' => [
        'name' => 'Name',
        'phone' => 'Telefonnummer',
        'phone_help' => 'Für Rückfragen zu Ihrer Bestellung.',
        'pickup_mode' => 'Abholung',
        'pickup_now' => 'Jetzt bestellen',
        'pickup_later' => 'Für später',
        'now_closed' => 'Wir haben gerade geschlossen. Bitte wählen Sie eine spätere Abholzeit.',
        'pickup_at' => 'Abholzeit',
        'pickup_help' => 'Nur während der Öffnungszeiten, frühestens in :minutes Minuten.',
        'note' => 'Anmerkung (optional)',
        'privacy' => 'Ich bin damit einverstanden, dass meine Angaben zur Bearbeitung der Vorbestellung verwendet werden.',
    ],

    'errors' => [
        'name_required' => 'Bitte geben Sie Ihren Namen an.',
        'phone_required' => 'Bitte geben Sie Ihre Telefonnummer an.',
        'phone_invalid' => 'Bitte geben Sie eine gültige Telefonnummer an.',
        'cart_empty' => 'Bitte wählen Sie mindestens ein Gericht aus der Speisekarte.',
        'cart_invalid' => 'Ihre Bestellung enthält ein Gericht, das es nicht mehr gibt. Bitte prüfen Sie die Auswahl.',
        'pickup_required' => 'Bitte wählen Sie Datum und Uhrzeit der Abholung.',
        'pickup_too_soon' => 'Die Abholung ist frühestens in :minutes Minuten möglich.',
        'pickup_too_late' => 'Vorbestellungen sind höchstens :days Tage im Voraus möglich.',
        'now_closed' => 'Wir haben gerade geschlossen. Bitte wählen Sie „Für später“ und eine Abholzeit.',
        'pickup_closed' => 'Zu dieser Zeit haben wir geschlossen. Bitte wählen Sie eine Zeit innerhalb der Öffnungszeiten.',
        'privacy_required' => 'Bitte stimmen Sie der Verwendung Ihrer Angaben zu.',
        'too_long' => 'Die Eingabe ist zu lang.',
        'send_failed' => 'Ihre Bestellung konnte leider nicht gesendet werden. Bitte rufen Sie uns an: :phone',
        'rate_limit' => 'Zu viele Vorbestellungen in kurzer Zeit. Bitte rufen Sie uns an.',
    ],

    'success' => [
        'title' => 'Vielen Dank!',
        'text' => 'Ihre Vorbestellung ist bei uns eingegangen.',
        'questions' => 'Fragen oder Änderungen? Rufen Sie uns an:',
        'again' => 'Weitere Vorbestellung',
    ],

    'mail' => [
        'subject' => 'Vorbestellung: :name, Abholung :time',
        'heading' => 'Neue Vorbestellung',
        'pickup' => 'Abholung',
        'oclock' => 'Uhr',
        'asap' => 'Sofort – so schnell wie möglich',
        'about' => 'ca.',
        'items' => 'Gerichte',
        'number' => 'Nr. :number',
        'total' => 'Summe',
        'note' => 'Anmerkung',
        'customer' => 'Kunde',
        'footer' => 'Gesendet über das Vorbestellungs-Formular der Webseite von :name.',
    ],

];
