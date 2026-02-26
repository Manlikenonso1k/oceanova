<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Mail\BookingConfirmed;
use App\Mail\BookingRejected;
use App\Models\Booking;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Mail;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = null;
    protected static ?string $navigationGroup = 'Reservations';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('whatsapp_number'),
            Forms\Components\TextInput::make('table_label'),
            Forms\Components\DatePicker::make('booking_date'),
            Forms\Components\TimePicker::make('booking_time'),
            Forms\Components\Select::make('status')->options([
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'rejected' => 'Rejected',
            ])->required(),
            Forms\Components\Textarea::make('rejection_reason')->columnSpan('full'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Name'),
                Tables\Columns\TextColumn::make('email')->url(fn ($record) => 'mailto:'.$record->email)->label('Email'),
                Tables\Columns\TextColumn::make('whatsapp_number')->label('WhatsApp'),
                Tables\Columns\TextColumn::make('table_label')->label('Table'),
                Tables\Columns\TextColumn::make('guest_count')->label('Guests'),
                Tables\Columns\BadgeColumn::make('status')
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected',
                        default => (string) $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'rejected',
                    ])->label('Status'),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->label('Confirm')
                    ->requiresConfirmation()
                    ->action(function (Booking $record) {
                        $record->status = 'confirmed';
                        $record->save();
                        Mail::to($record->email)->send(new BookingConfirmed($record->toArray()));
                    })
                    ->color('success'),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->action(function (Booking $record, array $data) {
                        $record->status = 'rejected';
                        $record->rejection_reason = $data['rejection_reason'] ?? null;
                        $record->save();
                        Mail::to($record->email)->send(new BookingRejected($record->toArray()));
                    })
                    ->modalHeading('Reject Booking')
                    ->form([
                        Forms\Components\Textarea::make('rejection_reason')->required(),
                    ])
                    ->color('danger'),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
        ];
    }
}
