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
| C-01 | Speisekarten-Fotos in `resources/images/speisekarte/` ablegen | P1 | todo | Alle Seiten der Karte vorhanden und lesbar |
| C-02 | Migrationen + Models `MenuCategory`, `MenuItem` | P1 | todo | Felder: Nummer, Name und Beschreibung (DE + EN), Preis (Cent, int), Sortierung, Allergene (nullable) |
| C-03 | `MenuSeeder`: Karte abtippen | P1 | blocked (C-01) | Alle Gerichte/Preise/Allergene wie auf der Karte, nichts erfunden; englische Namen übersetzt |
| C-04 | Essensfotos auswählen und optimieren (WebP, ~200 KB) | P2 | todo | Liegen in `resources/images/gerichte/`; Originale haben geringe Auflösung, daher nur klein einsetzen |
| C-05 | Texte für Hero und Über uns | P1 | todo | Kurz, Deutsch und Englisch, nennt Abholung und „keine Lieferung“ |
| C-06 | Admin-Bereich zur Pflege der Speisekarte (z. B. Filament) | P2 | todo | Inhaber kann nach Login Kategorien, Gerichte, Preise und Allergene ändern; ohne Login nicht erreichbar |

## Epic 2 – Seite bauen
| ID | Aufgabe | Prio | Status | Akzeptanzkriterien |
|---|---|---|---|---|
| P-01 | Layout: Header (sticky, Anker-Navigation), Footer | P1 | todo | Mobil kompakte Navigation |
| P-02 | Hero-Sektion | P1 | todo | Foto, Name, `tel:`-Button, heutige Öffnungszeit |
| P-03 | Speisekarte-Sektion | P1 | blocked (C-03) | Kategorien als Tabs/Sprungmarken, Preise rechtsbündig, mobil lesbar |
| P-04 | Über-uns-Sektion | P2 | todo | Frisch zubereitet, Vorbestellung möglich |
| P-05 | Kontakt-Sektion | P1 | todo | Adresse, Telefon, Öffnungszeiten-Tabelle, Karte DSGVO-konform |
| P-06 | Anzeige „Jetzt geöffnet / geschlossen“ | P3 | todo | Aus Config, Zeitzone Europe/Berlin, Samstag und Feiertage (NRW) nur 17–22 Uhr |
| P-07 | SEO-Grundlagen | P2 | todo | Title, Description, Open-Graph-Bild, `schema.org/Restaurant` JSON-LD |

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
| L-02 | Datenschutzerklärung | P1 | todo | Deckt Hosting, Formular, Mailversand ab (Generator nutzen) |
| L-03 | Allergen-Kennzeichnung | P1 | blocked (C-03) | Allergene je Gericht wie auf der Karte, mit Legende |

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
