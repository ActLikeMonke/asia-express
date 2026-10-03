# CLAUDE.md – Asia Express Würselen

Kontext für KI-Agenten (Claude Code u. a.). Kurz halten, bei Änderungen aktualisieren.

## Projekt
One-Pager-Webseite für den China-Imbiss **Asia Express**, Kaiserstraße 85, 52146 Würselen.
Ziel: Speisekarte, Öffnungszeiten, Kontakt und ein einfaches **Vorbestellungs-Formular** (Abholung), das als E-Mail beim Restaurant ankommt.
Kein Lieferdienst, keine Online-Zahlung (siehe Phase 2 im Backlog).

## Stack
- Laravel (aktuelle Version) + Livewire
- Tailwind CSS (über Vite)
- Datenbank: SQLite lokal, MySQL/MariaDB auf dem Hoster
- Mail: Laravel Mail (SMTP des Hosters)
- Ziel-Hosting: netcup (Shared Hosting mit PHP)

## Wichtige Dateien
Vollständige Übersicht: `docs/PROJECT_MAP.md`
- Backlog: `docs/BACKLOG.md`
- Offene Fragen: `docs/OPEN_QUESTIONS.md`
- Restaurant-Fakten (Adresse, Telefon usw.): `docs/RESTAURANT.md`
- Speisekarten-Fotos (Quelle zum Abtippen): `resources/images/speisekarte/`

## Befehle
```
composer install && npm install     # Abhängigkeiten
php artisan migrate --seed          # DB inkl. Speisekarte
composer run dev                    # Server + Vite (falls im Starter-Kit vorhanden)
php artisan serve / npm run dev     # alternativ getrennt
php artisan test                    # Tests
```

## Regeln für Agenten
- UI-Texte zweisprachig (**Deutsch** als Standard, Englisch) über Sprachdateien, nicht hart in Views. Code und Bezeichner auf Englisch.
- Sprache per URL: `/` ist Deutsch, `/en` Englisch (`SetLocale`-Middleware). Texte in `lang/de/site.php` und `lang/en/site.php`.
- Design: Rot und Gold, passend zur chinesischen Einrichtung des Lokals. Nur die `brand-*`-Farben aus `resources/css/app.css` verwenden.
- Mobile first: jede Sektion muss auf ~375 px Breite gut aussehen.
- Telefonnummer immer als `tel:`-Link.
- **Keine** externen Einbettungen (Google Maps, Google Fonts, Analytics) ohne Einwilligung (DSGVO). Karte: statisches Bild oder Klick-zum-Laden.
- Fonts lokal einbinden.
- Restaurantdaten nicht hart in Views schreiben, sondern aus `config/restaurant.php` lesen.
- Speisekarte kommt aus der Datenbank (Seeder als Erstbefüllung, danach Pflege durch den Inhaber im Admin-Bereich), nicht aus Views. Preise in Cent als Integer.
- Keine Fakten erfinden (Preise, Öffnungszeiten, Gerichte). Fehlt etwas → in `docs/OPEN_QUESTIONS.md` eintragen.
- Nach erledigter Aufgabe Status in `docs/BACKLOG.md` aktualisieren.
- Aufgaben aus „Phase 2“ im Backlog nicht anfangen, solange nicht ausdrücklich verlangt.
