<?php

namespace App\Filament\Resources\ProcurementResource\Pages;

use App\Filament\Resources\ProcurementResource;
use App\Models\Procurement;
use App\Services\InventoryService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProcurement extends CreateRecord
{
    protected static string $resource = ProcurementResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var Procurement $procurement */
        $procurement = app(InventoryService::class)->stockIn(
            (int) $data['ingredient_id'],
            (float) $data['quantity_received'],
            (float) $data['unit_cost'],
            (string) $data['supplier_name'],
            (string) $data['received_at'],
            (string) ($data['status'] ?? 'completed'),
            $data['receipt_attachment'] ?? null,
            auth()->id(),
        );

        return $procurement;
    }
}
