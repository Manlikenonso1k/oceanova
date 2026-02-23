<?php

namespace App\Services;

use App\Models\Ingredient;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Procurement;
use App\Models\Recipe;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    public function stockIn(
        int $ingredientId,
        float $quantityReceived,
        float $unitCost,
        string $supplierName,
        string $receivedAt,
        ?string $receiptAttachment = null,
        ?int $userId = null
    ): Procurement {
        if ($quantityReceived <= 0) {
            throw new RuntimeException('Quantity received must be greater than zero.');
        }

        return DB::transaction(function () use ($ingredientId, $quantityReceived, $unitCost, $supplierName, $receivedAt, $receiptAttachment, $userId) {
            $ingredient = Ingredient::query()->lockForUpdate()->findOrFail($ingredientId);

            $procurement = Procurement::query()->create([
                'ingredient_id' => $ingredient->id,
                'quantity_received' => $quantityReceived,
                'unit_cost' => $unitCost,
                'supplier_name' => $supplierName,
                'receipt_attachment' => $receiptAttachment,
                'received_at' => $receivedAt,
            ]);

            $ingredient->increment('current_stock', $quantityReceived);

            InventoryLog::query()->create([
                'ingredient_id' => $ingredient->id,
                'user_id' => $userId,
                'type' => 'in',
                'quantity' => $quantityReceived,
                'reason' => 'Procurement received from ' . $supplierName,
            ]);

            return $procurement;
        });
    }

    public function logWaste(int $ingredientId, float $quantity, string $reason, ?int $userId = null): InventoryLog
    {
        if ($quantity <= 0) {
            throw new RuntimeException('Waste quantity must be greater than zero.');
        }

        return DB::transaction(function () use ($ingredientId, $quantity, $reason, $userId) {
            $ingredient = Ingredient::query()->lockForUpdate()->findOrFail($ingredientId);

            if ((float) $ingredient->current_stock < $quantity) {
                throw new RuntimeException("Insufficient stock for {$ingredient->name}. Requested waste: {$quantity}, available: {$ingredient->current_stock}");
            }

            $ingredient->decrement('current_stock', $quantity);

            return InventoryLog::query()->create([
                'ingredient_id' => $ingredient->id,
                'user_id' => $userId,
                'type' => 'waste',
                'quantity' => $quantity,
                'reason' => $reason,
            ]);
        });
    }

    public function processOrderStockOut(Order $order, ?int $userId = null): void
    {
        $requirements = $this->buildIngredientRequirements($order->items ?? []);

        if ($requirements->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($requirements, $order, $userId) {
            foreach ($requirements as $ingredientId => $requiredQuantity) {
                /** @var Ingredient $ingredient */
                $ingredient = Ingredient::query()->lockForUpdate()->findOrFail($ingredientId);

                if ((float) $ingredient->current_stock < $requiredQuantity) {
                    throw new RuntimeException("Insufficient stock for {$ingredient->name}. Required: {$requiredQuantity}, available: {$ingredient->current_stock}");
                }

                $ingredient->decrement('current_stock', $requiredQuantity);

                InventoryLog::query()->create([
                    'ingredient_id' => $ingredient->id,
                    'user_id' => $userId,
                    'type' => 'out',
                    'quantity' => $requiredQuantity,
                    'reason' => 'Order #' . $order->id . ' stock out',
                ]);
            }
        });
    }

    public function processOrderStockAdjustment(Order $order, array $oldItems, array $newItems, ?int $userId = null): void
    {
        $oldRequirements = $this->buildIngredientRequirements($oldItems);
        $newRequirements = $this->buildIngredientRequirements($newItems);

        $ingredientIds = $oldRequirements->keys()->merge($newRequirements->keys())->unique()->values();

        if ($ingredientIds->isEmpty()) {
            return;
        }

        DB::transaction(function () use ($ingredientIds, $oldRequirements, $newRequirements, $order, $userId) {
            foreach ($ingredientIds as $ingredientId) {
                $oldQty = (float) ($oldRequirements[$ingredientId] ?? 0);
                $newQty = (float) ($newRequirements[$ingredientId] ?? 0);
                $delta = $newQty - $oldQty;

                if ($delta == 0.0) {
                    continue;
                }

                /** @var Ingredient $ingredient */
                $ingredient = Ingredient::query()->lockForUpdate()->findOrFail($ingredientId);

                if ($delta > 0) {
                    if ((float) $ingredient->current_stock < $delta) {
                        throw new RuntimeException("Insufficient stock for {$ingredient->name} during order update. Required: {$delta}, available: {$ingredient->current_stock}");
                    }

                    $ingredient->decrement('current_stock', $delta);

                    InventoryLog::query()->create([
                        'ingredient_id' => $ingredient->id,
                        'user_id' => $userId,
                        'type' => 'out',
                        'quantity' => $delta,
                        'reason' => 'Order #' . $order->id . ' adjustment stock out',
                    ]);
                } else {
                    $restock = abs($delta);
                    $ingredient->increment('current_stock', $restock);

                    InventoryLog::query()->create([
                        'ingredient_id' => $ingredient->id,
                        'user_id' => $userId,
                        'type' => 'in',
                        'quantity' => $restock,
                        'reason' => 'Order #' . $order->id . ' adjustment restock',
                    ]);
                }
            }
        });
    }

    private function buildIngredientRequirements(array $orderItems): Collection
    {
        $mealQuantities = collect($orderItems)
            ->map(fn (array $item): array => [
                'meal_id' => (int) ($item['meal_id'] ?? 0),
                'quantity' => max(0, (int) ($item['quantity'] ?? 0)),
            ])
            ->filter(fn (array $item): bool => $item['meal_id'] > 0 && $item['quantity'] > 0)
            ->groupBy('meal_id')
            ->map(fn (Collection $items): int => $items->sum('quantity'));

        if ($mealQuantities->isEmpty()) {
            return collect();
        }

        $recipes = Recipe::query()
            ->whereIn('menu_item_id', $mealQuantities->keys()->all())
            ->get(['menu_item_id', 'ingredient_id', 'quantity_required']);

        $requirements = collect();

        foreach ($recipes as $recipe) {
            $mealQuantity = (int) ($mealQuantities[$recipe->menu_item_id] ?? 0);
            if ($mealQuantity <= 0) {
                continue;
            }

            $required = $mealQuantity * (float) $recipe->quantity_required;
            $existing = (float) ($requirements[$recipe->ingredient_id] ?? 0);
            $requirements[$recipe->ingredient_id] = $existing + $required;
        }

        return $requirements;
    }
}
