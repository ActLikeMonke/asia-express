# Asia Express Würselen

One-Pager-Webseite für den China-Imbiss Asia Express (Kaiserstraße 85, 52146 Würselen):
Speisekarte, Öffnungszeiten, Kontakt und Vorbestellung zur Abholung.

Stack: Laravel + Livewire, Tailwind CSS (Vite), Filament als Admin-Bereich, SQLite lokal.

## Voraussetzungen

- PHP ≥ 8.3 mit den Erweiterungen `pdo_sqlite`, `mbstring`, `intl`, `gd`
- Composer
- Node.js mit npm

## Erste Einrichtung

Im Projektordner ausführen:

```
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
```

- `copy` ist der Windows-Befehl; unter macOS/Linux `cp .env.example .env`.
- `php artisan migrate --seed` legt die SQLite-Datenbank `database/database.sqlite` an (Nachfrage mit „yes“ bestätigen) und füllt die Speisekarte.

Danach ein Konto für den Admin-Bereich anlegen (fragt nach Name, E-Mail, Passwort):

```
php artisan make:filament-user
```

## Starten

```
composer run dev
```

Das startet den PHP-Server und Vite zusammen. Beenden mit `Strg + C`.

Alternativ in zwei Terminals:

```
php artisan serve
npm run dev
```

Dann im Browser öffnen:

| Adresse | Inhalt |
|---|---|
| http://localhost:8000 | Webseite auf Deutsch |
| http://localhost:8000/en | Webseite auf Englisch |
| http://localhost:8000/admin | Admin-Bereich: Kategorien, Gerichte, Preise, Allergene pflegen |

## Nützliche Befehle

```
php artisan test                  # Tests
npm run build                     # CSS/JS für den Live-Betrieb bauen
php artisan migrate:fresh --seed  # Datenbank leeren und Speisekarte neu einspielen (löscht auch Admin-Konten!)
```

## Häufige Probleme

- **Seite ohne Styling / „Vite manifest not found“:** `npm run dev` läuft nicht. `composer run dev` benutzen oder einmal `npm run build` ausführen.
- **Admin-Bereich ohne Styling:** `php artisan filament:assets` ausführen.
- **„No application encryption key“:** `php artisan key:generate` ausführen.
- **Speisekarte leer:** `php artisan db:seed` ausführen (füllt nur eine leere Karte).

## Dokumentation

- `CLAUDE.md` – Regeln und Kontext für KI-Agenten
- `docs/PROJECT_MAP.md` – wo liegt was
- `docs/BACKLOG.md` – Aufgaben und Status
- `docs/OPEN_QUESTIONS.md` – ungeklärte Punkte
- `docs/RESTAURANT.md` – bestätigte Restaurant-Fakten
