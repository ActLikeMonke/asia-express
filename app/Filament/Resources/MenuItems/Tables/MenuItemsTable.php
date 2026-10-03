<?php

namespace App\Filament\Resources\MenuItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenuItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label(__('admin.fields.number'))
                    ->searchable(),
                TextColumn::make('name_de')
                    ->label(__('admin.fields.name_de'))
                    ->description(fn ($record) => $record->description_de)
                    ->searchable(),
                TextColumn::make('category.name_de')
                    ->label(__('admin.fields.category'))
                    ->sortable(),
                TextColumn::make('price_cents')
                    ->label(__('admin.fields.price'))
                    ->money('EUR', divideBy: 100)
                    ->sortable(),
                IconColumn::make('is_spicy')
                    ->label(__('admin.fields.is_spicy'))
                    ->boolean(),
                TextColumn::make('allergens')
                    ->label(__('admin.fields.allergens'))
                    ->badge(),
                TextColumn::make('sort_order')
                    ->label(__('admin.fields.sort_order'))
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                SelectFilter::make('menu_category_id')
                    ->label(__('admin.fields.category'))
                    ->relationship('category', 'name_de'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
