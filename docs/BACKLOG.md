# Backlog – Asia Express Würselen

Status: `todo` · `doing` · `done` · `blocked`
Priorität: P1 = für Launch nötig · P2 = sollte · P3 = später
Fragen-Nummern beziehen sich auf `OPEN_QUESTIONS.md`.

## Epic 0 – Setup
| ID | Aufgabe | Prio | Status | Akzeptanzkriterien |
|---|---|---|---|---|
| S-01 | Laravel-Projekt mit Livewire + Tailwind anlegen | P1 | done | Startseite lädt lokal, Tailwind-Klassen wirken |
| S-02 | Git-Repo + GitHub-Remote | P1 | done | Erster Commit gepusht, `.env` ignoriert |
| S-03 | `config/restaurant.php` mit Stammdaten | P1 | done | Views lesen Name/Adresse/Telefon/Zeiten nur aus Config |
| S-04 | Lokale Fonts, kein externes CDN | P1 | done | Netzwerk-Tab zeigt keine Drittanbieter-Requests |
| S-05 | Mehrsprachigkeit Deutsch/Englisch | P1 | done | Texte in `lang/de` und `lang/en`, Sprachumschalter im Header, Deutsch ist Standard |
| S-06 | Farbschema Rot/Gold im Tailwind-Theme | P1 | done | Farben als Theme-Tokens, Kontrast für Text ≥ WCAG AA |

## Epic 1 – Inhalte
| ID | Aufgabe | Prio | Status | Akzeptanzkriterien |
|---|---|---|---|---|
| C-01 | Speisekarten-Fotos in `resources/images/speisekarte/` ablegen | P1 | done | Alle Seiten der Karte vorhanden und lesbar (Seite 2 nur in geringer Auflösung, siehe Frage 17) |
| C-02 | Migrationen + Models `MenuCategory`, `MenuItem` | P1 | done | Felder: Nummer, Name und Beschreibung (DE + EN), Preis (Cent, int), Sortierung, Allergene (nullable), scharf |
| C-03 | `MenuSeeder`: Karte abtippen | P1 | done | Alle Gerichte/Preise/Allergene wie auf der Karte, nichts erfunden; englische Namen übersetzt. Legende in `lang/*/menu.php`. Unsichere Kürzel: Frage 17 |
| C-04 | Essensfotos auswählen und optimieren (WebP, ~200 KB) | P2 | blocked (Fotos fehlen) | Liegen in `resources/images/gerichte/`; Originale haben geringe Auflösung, daher nur klein einsetzen |
| C-05 | Texte für Hero und Über uns | P1 | done | Kurz, Deutsch und Englisch, nennt Abholung und „keine Lieferung“ (`site.intro`, `site.about.*`; Einbau der Über-uns-Sektion in P-04) |
| C-06 | Admin-Bereich zur Pflege der Speisekarte (Filament, `/admin`) | P2 | done | Inhaber kann nach Login Kategorien, Gerichte, Preise und Allergene ändern; ohne Login nicht erreichbar. Konto anlegen: `php artisan make:filament-user` |

## Epic 2 – Seite bauen
| ID | Aufgabe | Prio | Status | Akzeptanzkriterien |
|---|---|---|---|---|
| P-01 | Layout: Header (sticky, Anker-Navigation), Footer | P1 | done | Mobil kompakte Navigation (zweizeilig, ohne JavaScript). Footer-Links zu Impressum/Datenschutz folgen mit L-01/L-02; Anker „Vorbestellung“ mit O-01 |
| P-02 | Hero-Sektion | P1 | done | Foto, Name, `tel:`-Button, heutige Öffnungszeit. Als Bild dient vorerst das Logo, bis Essensfotos da sind (C-04) |
| P-03 | Speisekarte-Sektion | P1 | done | Kategorien als Tabs/Sprungmarken, Preise rechtsbündig, mobil lesbar |
| P-04 | Über-uns-Sektion | P2 | done | Frisch zubereitet, Vorbestellung möglich |
| P-05 | Kontakt-Sektion | P1 | done | Adresse, Telefon, Öffnungszeiten-Tabelle, Karte DSGVO-konform (Google Maps erst nach Klick, zusätzlich Link) |
| P-06 | Anzeige „Jetzt geöffnet / geschlossen“ | P3 | done | Aus Config, Zeitzone Europe/Berlin, Samstag und Feiertage (NRW) nur 17–22 Uhr (`App\Support\OpeningHours`; Feiertagszeiten siehe Frage 18) |
| P-07 | SEO-Grundlagen | P2 | done | Title, Description, Open-Graph-Bild (Logo), `schema.org/Restaurant` JSON-LD, Canonical + hreflang |

## Epic 3 – Vorbestellung
| ID | Aufgabe | Prio | Status | Akzeptanzkriterien |
|---|---|---|---|---|
| O-01 | Livewire-Komponente `PreorderForm` | P1 | todo | Felder: Name, Telefon, Gerichte, Abholzeit, Anmerkung |
| O-02 | Validierung | P1 | blocked (Frage 3) | Pflichtfelder; Abholzeit in Öffnungszeiten + Mindestvorlauf |
| O-03 | Mail `PreorderReceived` ans Restaurant | P1 | blocked (Frage 2) | Übersichtlich, auf dem Handy gut lesbar |
| O-04 | Spam-Schutz | P1 | todo | Honeypot + Rate-Limit pro IP |
| O-05 | Datenschutz-Hinweis am Formular | P1 | todo | Link zur Datenschutzerklärung, Pflicht-Checkbox |
| O-06 | Erfolgsmeldung nach Absenden | P1 | blocked (Frage 4) | Text passt zur Absprache mit Inhaber (Bestätigung ja/nein) |
| O-07 | Feature-Tests | P2 | todo | Validierung + `Mail::fake()` |

## Epic 4 – Recht
| ID | Aufgabe | Prio | Status | Akzeptanzkriterien |
|---|---|---|---|---|
| L-01 | Impressum | P1 | blocked (Frage 6) | Angaben nach § 5 DDG |
| L-02 | Datenschutzerklärung | P1 | todo | Deckt Hosting, Formular, Mailversand und die Google-Maps-Karte (Laden nach Klick) ab (Generator nutzen) |
| L-03 | Allergen-Kennzeichnung | P1 | done | Allergene je Gericht wie auf der Karte, mit Legende (in der Speisekarte-Sektion; unsichere Kürzel siehe Frage 17) |

## Epic 5 – Launch
| ID | Aufgabe | Prio | Status | Akzeptanzkriterien |
|---|---|---|---|---|
| D-01 | netcup-Hosting einrichten | P1 | todo | Tarif mit passender PHP-Version, SMTP und möglichst SSH; SSL aktiv |
| D-02 | Domain registrieren und verbinden | P1 | blocked (Frage 11) | Seite unter Domain mit HTTPS erreichbar |
| D-03 | Produktions-`.env`, SMTP | P1 | todo | `APP_DEBUG=false`, Testmail kommt an |
| D-04 | Deploy-Ablauf dokumentieren | P2 | todo | Schritte in `docs/DEPLOY.md` |
| D-05 | Abnahme mit Inhaber | P1 | todo | Probe-Vorbestellung erfolgreich, Texte freigegeben |
| D-06 | Lighthouse-Check | P2 | todo | Mobil Performance + Accessibility ≥ 90 |
| D-07 | Webseite im Google-Unternehmensprofil eintragen | P2 | todo | Inhaber trägt URL ein |

## Phase 2 – später, nicht anfangen
| ID | Aufgabe | Prio |
|---|---|---|
| X-01 | Warenkorb-Bestellung mit Gerichtauswahl aus DB | P3 |
| X-02 | Bestell-Dashboard fürs Restaurant (neu / angenommen / fertig) | P3 |
| X-04 | Online-Zahlung | P3 |
