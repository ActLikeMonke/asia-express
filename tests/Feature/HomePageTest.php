<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
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
}
