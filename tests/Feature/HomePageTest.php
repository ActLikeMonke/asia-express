<?php

namespace Tests\Feature;

use Carbon\CarbonImmutable;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_is_german_by_default(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('lang="de"', false);
        $response->assertSee(config('restaurant.name'));
        $response->assertSee('Öffnungszeiten');
        $response->assertSee('href="tel:'.config('restaurant.phone.link').'"', false);
    }

    public function test_home_page_is_english_under_en_prefix(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
        $response->assertSee('lang="en"', false);
        $response->assertSee('Opening hours');
        $response->assertDontSee('Öffnungszeiten');
    }

    public function test_sections_and_anchor_navigation_are_present(): void
    {
        $response = $this->get('/');

        foreach (['menu', 'about', 'contact'] as $anchor) {
            $response->assertSee('href="#'.$anchor.'"', false);
            $response->assertSee('id="'.$anchor.'"', false);
        }

        $response->assertSee(config('restaurant.address.street'));
        $response->assertSee('Lieferdienst');
    }

    public function test_menu_comes_from_the_database_in_the_current_language(): void
    {
        $this->seed(MenuSeeder::class);

        $this->get('/')
            ->assertSeeInOrder(['Vorspeisen', 'Pekingsuppe', '3,00', 'Getränke', 'Weizenbier'])
            ->assertSee('Geschmacksverstärker')
            ->assertSee('Gluten (Weizen)');

        $this->get('/en')
            ->assertSeeInOrder(['Starters', 'Peking soup', '3.00', 'Drinks', 'Wheat beer'])
            ->assertSee('flavour enhancer')
            ->assertDontSee('Pekingsuppe');
    }

    public function test_empty_menu_shows_a_placeholder(): void
    {
        $this->get('/')->assertOk()->assertSee('Die Speisekarte folgt in Kürze.');
    }

    public function test_hero_shows_todays_hours_and_open_state(): void
    {
        // Wednesday, 12:00 Berlin time.
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-07 12:00', 'Europe/Berlin'));
        $this->get('/')->assertSee('Jetzt geöffnet')->assertSeeInOrder(['Heute geöffnet', '11:30–15:00 Uhr', '17:00–22:00 Uhr']);

        // Same day in the afternoon break.
        CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-10-07 16:00', 'Europe/Berlin'));
        $this->get('/')->assertSee('Gerade geschlossen')->assertDontSee('Jetzt geöffnet');
    }

    public function test_map_is_not_embedded_before_consent(): void
    {
        $response = $this->get('/');

        $response->assertDontSee('<iframe', false);
        $response->assertSee('data-map-load', false);
        $response->assertSee('Karte laden');

        // No third-party resources are loaded by the page itself.
        $this->assertDoesNotMatchRegularExpression('/<(script|link|img|iframe)[^>]+(src|href)="https?:\/\/(?!localhost)[^"]*(google|gstatic|googleapis)/i', $response->getContent());
    }

    public function test_seo_basics_are_present(): void
    {
        $response = $this->get('/');

        $response->assertSee('<title>'.config('restaurant.name'), false);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('<link rel="canonical" href="'.route('home').'">', false);
        $response->assertSee('hreflang="en" href="'.route('en.home').'"', false);
        $response->assertSee('property="og:image"', false);

        preg_match('/<script type="application\/ld\+json">(.*?)<\/script>/s', $response->getContent(), $matches);
        $schema = json_decode($matches[1] ?? '', true);

        $this->assertSame('Restaurant', $schema['@type']);
        $this->assertSame(config('restaurant.name'), $schema['name']);
        $this->assertSame(config('restaurant.address.street'), $schema['address']['streetAddress']);
        $this->assertSame(config('restaurant.phone.link'), $schema['telephone']);
        $this->assertCount(13, $schema['openingHoursSpecification']);
    }
}
