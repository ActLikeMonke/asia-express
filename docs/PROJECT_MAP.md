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
│   ├── de/site.php            UI-Texte Deutsch (Standard), inkl. Hero (`intro`) und Über uns (`about.*`)
│   ├── en/site.php            UI-Texte Englisch
│   ├── {de,en}/menu.php       Legende der Zusatzstoffe (1–13) und Allergene (A–N) wie auf der Karte
│   └── {de,en}/admin.php      Beschriftungen im Admin-Bereich
├── app/
│   ├── Http/Controllers/
│   │   └── HomeController.php lädt Speisekarte, heutige Öffnungszeit und JSON-LD für den One-Pager
│   ├── Http/Middleware/
│   │   └── SetLocale.php      setzt die Sprache anhand des URL-Präfixes (/en)
│   ├── Support/
│   │   └── OpeningHours.php   heutige Zeiten, „jetzt geöffnet“, Feiertage NRW (aus config/restaurant.php)
│   ├── Models/
│   │   ├── MenuCategory.php   Kategorie (Vorspeisen, Ente, Menüs …); `name` liefert die aktuelle Sprache
│   │   ├── MenuItem.php       Gericht: Nummer, Name, Beschreibung, Preis (Cent), scharf, Allergene; `name`, `description`, `formatted_price`
│   │   └── User.php           Login für den Admin-Bereich (anlegen mit `php artisan make:filament-user`)
│   ├── Filament/Resources/    Admin-Bereich `/admin` (Filament): MenuCategories, MenuItems
│   ├── Providers/Filament/
│   │   └── AdminPanelProvider.php   Konfiguration des Admin-Bereichs (Pfad, Farbe, Login)
│   ├── Livewire/
│   │   └── PreorderForm.php   (geplant) Vorbestellungs-Formular
│   └── Mail/
│       └── PreorderReceived.php   (geplant) Mail ans Restaurant
├── database/
│   ├── migrations/            menu_categories, menu_items
│   └── seeders/
│       └── MenuSeeder.php     Karte, abgetippt aus resources/images/speisekarte; füllt nur eine leere Karte
├── resources/
│   ├── images/
│   │   ├── logo.jpeg          Logo des Restaurants
│   │   ├── speisekarte/       Fotos der Speisekarte (Quelle zum Abtippen)
│   │   └── gerichte/          Essensfotos für die Webseite
│   ├── views/
│   │   ├── layouts/app.blade.php            Grundlayout, Header mit Anker-Navigation und Sprachumschalter, Footer
│   │   ├── home.blade.php                   One-Pager: SEO-Tags, JSON-LD, bindet die Sektionen ein
│   │   ├── sections/                        hero, menu, about, contact; (geplant) preorder
│   │   ├── livewire/preorder-form.blade.php (geplant)
│   │   ├── mail/preorder-received.blade.php (geplant)
│   │   ├── impressum.blade.php              (geplant)
│   │   └── datenschutz.blade.php            (geplant)
│   ├── js/app.js              Karte erst nach Klick laden; macht das Logo für `Vite::asset()` verfügbar
│   └── css/app.css            Tailwind, Theme-Farben `brand-*` (Rot/Gold), Schriften
├── vite.config.js             Vite, Tailwind, lokale Fonts aus `@fontsource`-Paketen
├── routes/web.php             / (Deutsch), /en (Englisch); geplant: /impressum, /datenschutz
└── tests/                     Unit: Öffnungszeiten/Feiertage. Feature: Seite lädt in beiden Sprachen, Sektionen, SEO, Karte ohne Einbettung, Seeder/Models, Admin-Bereich nur mit Login, Sprachdateien vollständig; geplant: Formular validiert, Mail wird verschickt
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
