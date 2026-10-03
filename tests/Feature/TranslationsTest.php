<?php

namespace Tests\Feature;

use Illuminate\Support\Arr;
use Tests\TestCase;

class TranslationsTest extends TestCase
{
    public function test_german_and_english_have_the_same_keys(): void
    {
        foreach (['site', 'admin', 'menu'] as $file) {
            $de = array_keys(Arr::dot(require lang_path("de/{$file}.php")));
            $en = array_keys(Arr::dot(require lang_path("en/{$file}.php")));

            $this->assertSame($de, $en, "lang/de/{$file}.php and lang/en/{$file}.php differ");
        }
    }

    public function test_intro_and_about_mention_pickup_and_no_delivery(): void
    {
        $this->assertStringContainsString('Abholung', __('site.intro', locale: 'de'));
        $this->assertStringContainsString('Lieferdienst', __('site.intro', locale: 'de'));
        $this->assertStringContainsString('Lieferdienst', __('site.about.pickup', locale: 'de'));
        $this->assertStringContainsString('delivery', __('site.intro', locale: 'en'));
        $this->assertStringContainsString('delivery', __('site.about.pickup', locale: 'en'));
    }
}
