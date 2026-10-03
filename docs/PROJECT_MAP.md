# Projekt-Map – wo liegt was?

Laravel-Standardstruktur, nur projektrelevante Teile. `(geplant)` = existiert noch nicht.
Beim Anlegen einer Datei den Vermerk entfernen.

```
asia-express/
├── CLAUDE.md                  Agenten-Kontext & Regeln (zuerst lesen)
├── AGENTS.md                  Verweis auf CLAUDE.md
├── docs/
│   ├── BACKLOG.md             Aufgaben, Status, Akzeptanzkriterien
│   ├── PROJECT_MAP.md         diese Datei
│   ├── OPEN_QUESTIONS.md      ungeklärte Punkte
│   └── RESTAURANT.md          bestätigte Restaurant-Fakten
├── config/
│   └── restaurant.php         Name, Adresse, Telefon, Öffnungszeiten, Sprachen, Mail-Empfänger
├── lang/
│   ├── de/site.php            UI-Texte Deutsch (Standard)
│   └── en/site.php            UI-Texte Englisch
├── app/
│   ├── Http/Middleware/
│   │   └── SetLocale.php      setzt die Sprache anhand des URL-Präfixes (/en)
│   ├── Models/
│   │   ├── MenuCategory.php   (geplant) Kategorie (Vorspeisen, Ente, Menüs …)
│   │   └── MenuItem.php       (geplant) Gericht: Nummer, Name, Beschreibung, Preis, Allergene
│   ├── Livewire/
│   │   └── PreorderForm.php   (geplant) Vorbestellungs-Formular
│   └── Mail/
│       └── PreorderReceived.php   (geplant) Mail ans Restaurant
├── database/
│   ├── migrations/            (geplant) menu_categories, menu_items
│   └── seeders/
│       └── MenuSeeder.php     (geplant) Karte, abgetippt aus resources/images/speisekarte
├── resources/
│   ├── images/
│   │   ├── logo.jpeg          Logo des Restaurants
│   │   ├── speisekarte/       Fotos der Speisekarte (Quelle zum Abtippen)
│   │   └── gerichte/          Essensfotos für die Webseite
│   ├── views/
│   │   ├── layouts/app.blade.php            Grundlayout, Header mit Sprachumschalter, Footer
│   │   ├── home.blade.php                   One-Pager (bisher Hero + Öffnungszeiten als Platzhalter)
│   │   ├── sections/                        (geplant) hero, menu, about, preorder, contact
│   │   ├── livewire/preorder-form.blade.php (geplant)
│   │   ├── mail/preorder-received.blade.php (geplant)
│   │   ├── impressum.blade.php              (geplant)
│   │   └── datenschutz.blade.php            (geplant)
│   └── css/app.css            Tailwind, Theme-Farben `brand-*` (Rot/Gold), Schriften
├── vite.config.js             Vite, Tailwind, lokale Fonts aus `@fontsource`-Paketen
├── routes/web.php             / (Deutsch), /en (Englisch); geplant: /impressum, /datenschutz
└── tests/Feature/             Seite lädt in beiden Sprachen; geplant: Formular validiert, Mail wird verschickt
```

## Seitenaufbau (One-Pager, Reihenfolge)
1. **Hero** – Name, Essensfoto, „Jetzt anrufen“-Button, heutige Öffnungszeit
2. **Speisekarte** – nach Kategorien, mobil gut lesbar
3. **Über uns** – frisch zubereitet, Vorbestellung zur Abholung, keine Lieferung
4. **Vorbestellung** – Formular
5. **Kontakt** – Adresse, Karte, Telefon, Öffnungszeiten
6. **Footer** – Impressum, Datenschutz

## Datenfluss Vorbestellung
Formular (Livewire) → Validierung → Mail an `config('restaurant.order_email')` → Erfolgsmeldung.
Keine Speicherung in der DB (Phase 1).
