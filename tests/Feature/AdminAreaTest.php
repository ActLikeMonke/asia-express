<?php

namespace Tests\Feature;

use App\Filament\Resources\MenuItems\Pages\EditMenuItem;
use App\Models\MenuItem;
use App\Models\User;
use Database\Seeders\MenuSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAreaTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_area_requires_login(): void
    {
        foreach (['/admin', '/admin/menu-categories', '/admin/menu-items'] as $url) {
            $this->get($url)->assertRedirect('/admin/login');
        }
    }

    public function test_logged_in_user_sees_the_menu(): void
    {
        $this->seed(MenuSeeder::class);
        $this->actingAs(User::factory()->create());

        $this->get('/admin/menu-categories')->assertOk()->assertSee('Vorspeisen');
        $this->get('/admin/menu-items')->assertOk()->assertSee('Pekingsuppe');
    }

    public function test_price_is_edited_in_euros_and_stored_in_cents(): void
    {
        $this->seed(MenuSeeder::class);
        $this->actingAs(User::factory()->create());
        $item = MenuItem::where('number', '1')->firstOrFail();

        Livewire::test(EditMenuItem::class, ['record' => $item->getRouteKey()])
            ->assertSchemaStateSet(['price_cents' => '3.00'])
            ->fillForm(['price_cents' => '3.50', 'allergens' => ['7', 'A']])
            ->call('save')
            ->assertHasNoFormErrors();

        $item->refresh();
        $this->assertSame(350, $item->price_cents);
        $this->assertSame(['7', 'A'], $item->allergens);
    }
}
