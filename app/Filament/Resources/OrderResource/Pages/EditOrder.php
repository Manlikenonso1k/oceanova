<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Meal;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$normalizedItems, $total] = $this->normalizeItems($data['items'] ?? []);

        $data['items'] = $normalizedItems;
        $data['total'] = $total;

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncOrderItems($this->record);
    }

    /** @return array{0: array<int, array<string, mixed>>, 1: float} */
    private function normalizeItems(array $items): array
    {
        $normalized = [];
        $total = 0;

        foreach ($items as $item) {
            $name = trim((string) ($item['meal_name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            $meal = Meal::query()->firstOrCreate(
                ['name' => $name],
                [
                    'price' => (float) ($item['unit_price'] ?? 0),
                    'category' => 'Uncategorized',
                ]
            );

            $unitPrice = (float) ($item['unit_price'] ?? $meal->price ?? 0);
            if ($unitPrice <= 0) {
                $unitPrice = (float) $meal->price;
            }

            $lineTotal = $unitPrice * $quantity;

            $normalized[] = [
                'meal_id' => $meal->id,
                'meal_name' => $meal->name,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $lineTotal,
            ];

            $total += $lineTotal;
        }

        return [$normalized, $total];
    }

    private function syncOrderItems(Order $order): void
    {
        $order->orderItems()->delete();

        $payload = collect($order->items ?? [])
            ->map(fn (array $item): array => [
                'meal_id' => $item['meal_id'] ?? null,
                'meal_name' => $item['meal_name'] ?? '',
                'quantity' => (int) ($item['quantity'] ?? 1),
                'unit_price' => (float) ($item['unit_price'] ?? 0),
                'total' => (float) ($item['total'] ?? 0),
            ])
            ->filter(fn (array $item): bool => $item['meal_name'] !== '')
            ->values()
            ->all();

        if (!empty($payload)) {
            $order->orderItems()->createMany($payload);
        }
    }
}
