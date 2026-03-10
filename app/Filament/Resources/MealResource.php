<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MealResource\Pages;
use App\Models\Meal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MealResource extends Resource
{
    protected static ?string $model = Meal::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';

    protected static ?string $navigationGroup = 'Restaurant';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\Select::make('menu_section_id')
                ->label('Menu Section')
                ->relationship('menuSection', 'title')
                ->searchable()
                ->preload(),

            Forms\Components\TextInput::make('price')
                ->required()
                ->numeric()
                ->prefix('₦')
                ->minValue(0),

            Forms\Components\Textarea::make('description')
                ->rows(3),

            Forms\Components\TagsInput::make('tags')
                ->placeholder('Add tags like V, L, P, S'),

            Forms\Components\TextInput::make('category')
                ->maxLength(255),

            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),

            Forms\Components\Toggle::make('is_active')
                ->default(true),

            Forms\Components\FileUpload::make('image')
                ->image()
                ->disk('public')
                ->directory('meals')
                ->visibility('public'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('menuSection.title')
                    ->label('Section')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->money('NGN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('trackPublicLink')
                    ->label('Track Public Link')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Meal $record): string => route('track.redirect', [
                        'link_name' => 'meal-'.$record->id,
                        'url' => route('menu').'#'.str($record->name)->slug(),
                    ]), shouldOpenInNewTab: true),
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
            'index' => Pages\ListMeals::route('/'),
            'create' => Pages\CreateMeal::route('/create'),
            'edit' => Pages\EditMeal::route('/{record}/edit'),
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
