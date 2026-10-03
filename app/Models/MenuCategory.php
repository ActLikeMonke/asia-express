<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name_de', 'name_en', 'sort_order'])]
class MenuCategory extends Model
{
    /**
     * @return HasMany<MenuItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    /**
     * Name in the current locale.
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn () => app()->getLocale() === 'en' ? $this->name_en : $this->name_de);
    }
}
