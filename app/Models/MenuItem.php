<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

#[Fillable([
    'menu_category_id',
    'number',
    'name_de',
    'name_en',
    'description_de',
    'description_en',
    'price_cents',
    'is_spicy',
    'allergens',
    'sort_order',
])]
class MenuItem extends Model
{
    /**
     * @return BelongsTo<MenuCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'menu_category_id');
    }

    /**
     * Name in the current locale.
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn () => app()->getLocale() === 'en' ? $this->name_en : $this->name_de);
    }

    /**
     * Description in the current locale.
     */
    protected function description(): Attribute
    {
        return Attribute::get(fn () => app()->getLocale() === 'en' ? $this->description_en : $this->description_de);
    }

    /**
     * Price formatted for the current locale, e.g. "11,00 €".
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::get(fn () => Number::currency($this->price_cents / 100, in: 'EUR', locale: app()->getLocale()));
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'is_spicy' => 'boolean',
            'allergens' => 'array',
            'sort_order' => 'integer',
        ];
    }
}
