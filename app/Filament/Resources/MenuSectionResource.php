<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuSectionResource\Pages;
use App\Models\MenuSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;

class MenuSectionResource extends Resource
{
    protected static ?string $model = MenuSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Restaurant';
    protected static ?string $label = 'Menu Category';
    protected static ?string $pluralLabel = 'Menu Categories';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('title')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->maxLength(255),
            Forms\Components\TextInput::make('subtitle')->maxLength(255),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable(),
                TextColumn::make('subtitle')->sortable(),
                TextColumn::make('sort_order')->sortable(),
                IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuSections::route('/'),
            'create' => Pages\CreateMenuSection::route('/create'),
            'edit' => Pages\EditMenuSection::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'general_order_person', 'procurement_officer']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'general_order_person', 'procurement_officer']);
    }

    public static function canEdit($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'general_manager', 'general_order_person', 'procurement_officer']);
    }

    public static function canDelete($record): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin']);
    }
}
