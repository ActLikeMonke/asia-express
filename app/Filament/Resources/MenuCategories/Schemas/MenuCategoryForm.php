<?php

namespace App\Filament\Resources\MenuCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MenuCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name_de')
                    ->label(__('admin.fields.name_de'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_en')
                    ->label(__('admin.fields.name_en'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->helperText(__('admin.fields.sort_order_help'))
                    ->required()
                    ->integer()
                    ->minValue(0)
                    ->default(0),
            ]);
    }
}
