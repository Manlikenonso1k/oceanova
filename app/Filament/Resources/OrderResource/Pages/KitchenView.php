<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class KitchenView extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected static ?string $title = 'Kitchen Prep Queue';

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->whereIn('status', ['pending', 'cooking'])
            ->orderBy('created_at');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getTablePollingInterval(): ?string
    {
        return '15s';
    }

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['kitchen', 'kitchen_manager', 'admin', 'super_admin']);
    }
}
