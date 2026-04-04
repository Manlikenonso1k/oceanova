<?php

namespace App\Filament\Resources\MealResource\Pages;

use App\Filament\Resources\MenuSectionResource;
use App\Filament\Resources\MealResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMeals extends ListRecords
{
    protected static string $resource = MealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Meal'),
            Actions\Action::make('createCategory')
                ->label('Create Category')
                ->icon('heroicon-o-tag')
                ->url(MenuSectionResource::getUrl('create')),
        ];
    }
}
