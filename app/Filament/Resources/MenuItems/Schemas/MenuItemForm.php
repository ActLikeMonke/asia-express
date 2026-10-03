<?php

namespace App\Filament\Resources\MenuItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('menu_category_id')
                    ->label(__('admin.fields.category'))
                    ->relationship('category', 'name_de')
                    ->required(),
                TextInput::make('number')
                    ->label(__('admin.fields.number'))
                    ->maxLength(10),
                TextInput::make('name_de')
                    ->label(__('admin.fields.name_de'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_en')
                    ->label(__('admin.fields.name_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('description_de')
                    ->label(__('admin.fields.description_de'))
                    ->maxLength(255),
                TextInput::make('description_en')
                    ->label(__('admin.fields.description_en'))
                    ->maxLength(255),
                // Entered in euros, stored in cents.
                TextInput::make('price_cents')
                    ->label(__('admin.fields.price'))
                    ->required()
                    ->numeric()
                    ->step(0.01)
                    ->minValue(0)
                    ->suffix('€')
                    ->formatStateUsing(fn ($state) => $state === null ? null : number_format($state / 100, 2, '.', ''))
                    ->dehydrateStateUsing(fn ($state) => (int) round((float) $state * 100)),
                TextInput::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->helperText(__('admin.fields.sort_order_help'))
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->default(0),
                Toggle::make('is_spicy')
                    ->label(__('admin.fields.is_spicy')),
                TagsInput::make('allergens')
                    ->label(__('admin.fields.allergens'))
                    ->helperText(__('admin.fields.allergens_help')),
            ]);
    }
}
