<?php

namespace Tests\Feature;

use App\Models\MenuCategory;
use App\Models\MenuItem;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_fills_the_menu_from_the_printed_card(): void
    {
        $this->seed(MenuSeeder::class);

        $this->assertSame(13, MenuCategory::count());
        $this->assertSame(89, MenuItem::count());
        $this->assertSame(
            [...array_map('strval', range(1, 70)), 'B1', 'B2', 'B3', 'B4'],
            MenuItem::whereNotNull('number')->orderBy('id')->pluck('number')->all(),
        );
        $this->assertSame(0, MenuItem::where('price_cents', '<=', 0)->count());

        // Every printed code must be explained in the legend, in both languages.
        $codes = MenuItem::whereNotNull('allergens')->pluck('allergens')->flatten()->unique();
        foreach (['de', 'en'] as $locale) {
            $legend = array_keys(trans('menu.additives', locale: $locale) + trans('menu.allergens', locale: $locale));
            $this->assertEmpty($codes->diff(array_map('strval', $legend)), "Legend incomplete for {$locale}");
        }

        $water = MenuItem::where('name_de', 'Mineralwasser')->firstOrFail();
        $this->assertNull($water->number);
        $this->assertNull($water->allergens);

        $soup = MenuItem::where('number', '1')->firstOrFail();
        $this->assertSame('Pekingsuppe', $soup->name_de);
        $this->assertSame(300, $soup->price_cents);
        $this->assertTrue($soup->is_spicy);
        $this->assertSame(['1', '7', 'A'], $soup->allergens);
        $this->assertSame('Vorspeisen', $soup->category->name_de);
    }

    public function test_seeder_does_not_overwrite_an_existing_menu(): void
    {
        $this->seed(MenuSeeder::class);
        MenuItem::where('number', '1')->update(['price_cents' => 350]);

        $this->seed(MenuSeeder::class);

        $this->assertSame(89, MenuItem::count());
        $this->assertSame(350, MenuItem::where('number', '1')->value('price_cents'));
    }

    public function test_item_texts_and_price_follow_the_locale(): void
    {
        $this->seed(MenuSeeder::class);
        $item = MenuItem::where('number', '3')->firstOrFail();

        app()->setLocale('de');
        $this->assertSame('Nudelsuppe', $item->name);
        $this->assertSame('mit Hühnerfleisch & Gemüse', $item->description);
        $this->assertSame('Vorspeisen', $item->category->name);
        $this->assertStringContainsString('4,00', $item->formatted_price);

        app()->setLocale('en');
        $this->assertSame('Noodle soup', $item->name);
        $this->assertSame('with chicken & vegetables', $item->description);
        $this->assertSame('Starters', $item->category->name);
        $this->assertStringContainsString('4.00', $item->formatted_price);
    }
}
