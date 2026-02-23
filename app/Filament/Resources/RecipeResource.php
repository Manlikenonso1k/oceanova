<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RecipeResource\Pages;
use App\Models\Recipe;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class RecipeResource extends Resource
{
    protected static ?string $model = Recipe::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('menu_item_id')
                ->label('Menu Item')
                ->relationship('menuItem', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\Select::make('ingredient_id')
                ->label('Ingredient')
                ->relationship('ingredient', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('quantity_required')
                ->required()
                ->numeric()
                ->minValue(0.001),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menuItem.name')
                    ->label('Menu Item')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Ingredient')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity_required')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRecipes::route('/'),
            'create' => Pages\CreateRecipe::route('/create'),
            'edit' => Pages\EditRecipe::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }
}
