<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProcurementResource\Pages;
use App\Models\Procurement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProcurementResource extends Resource
{
    protected static ?string $model = Procurement::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('ingredient_id')
                ->label('Ingredient')
                ->relationship('ingredient', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Forms\Components\TextInput::make('quantity_received')
                ->required()
                ->numeric()
                ->minValue(0.001),

            Forms\Components\TextInput::make('unit_cost')
                ->required()
                ->numeric()
                ->prefix('₦')
                ->minValue(0),

            Forms\Components\TextInput::make('supplier_name')
                ->required()
                ->maxLength(255),

            Forms\Components\FileUpload::make('receipt_attachment')
                ->label('Receipt (Camera / Upload)')
                ->disk('public')
                ->directory('procurements/receipts')
                ->visibility('public')
                ->acceptedFileTypes([
                    'application/pdf',
                    'image/jpeg',
                    'image/jpg',
                    'image/png',
                    'image/webp',
                ])
                ->maxSize(10240)
                ->openable()
                ->downloadable()
                ->helperText('Supports PDF, JPG, PNG, WEBP. On mobile, you can choose Camera while selecting file.'),

            Forms\Components\DateTimePicker::make('received_at')
                ->required()
                ->default(now()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ingredient.name')
                    ->label('Ingredient')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity_received')
                    ->numeric(decimalPlaces: 3)
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit_cost')
                    ->money('NGN')
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier_name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('receipt_attachment')
                    ->label('Receipt')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'View Receipt' : 'No Receipt')
                    ->url(fn (Procurement $record): ?string => $record->receipt_attachment ? Storage::disk('public')->url($record->receipt_attachment) : null)
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('received_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('received_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProcurements::route('/'),
            'create' => Pages\CreateProcurement::route('/create'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['procurement_officer', 'admin', 'super_admin']);
    }

    public static function canCreate(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['procurement_officer', 'admin', 'super_admin']);
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
