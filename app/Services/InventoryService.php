<?php

namespace App\Services;

use App\Models\BarStockSheet;
use App\Models\Ingredient;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Procurement;
use App\Models\Recipe;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    public function calculateBarStockMetrics(
        float $openingStock,
        float $addedStock,
        float $transIn,
        float $transOut,
        float $sales,
        float $physicalClosing
    ): array {
        $totalStock = ($openingStock + $addedStock + $transIn) - $transOut;
        $expectedClosing = $totalStock - $sales;
        $variance = $physicalClosing - $expectedClosing;

        return [
            'total_stock' => round($totalStock, 3),
            'expected_closing' => round($expectedClosing, 3),
            'variance' => round($variance, 3),
        ];
    }

    public function createBarStockSheet(
        int $ingredientId,
        CarbonInterface $periodStart,
        CarbonInterface $periodEnd,
        float $openingStock,
        float $closingStock,
        ?int $recordedBy = null
    ): BarStockSheet {
        /** @var Ingredient $ingredient */
        $ingredient = Ingredient::query()->findOrFail($ingredientId);

        $addedStock = (float) Procurement::query()
            ->where('ingredient_id', $ingredientId)
            ->whereBetween('received_at', [$periodStart, $periodEnd])
            ->sum('quantity_received');

        $transIn = (float) InventoryLog::query()
            ->where('ingredient_id', $ingredientId)
            ->where('type', 'in')
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->where('reason', 'like', 'Transfer In:%')
            ->sum('quantity');

        $transOut = (float) InventoryLog::query()
            ->where('ingredient_id', $ingredientId)
            ->where('type', 'out')
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->where('reason', 'like', 'Transfer Out:%')
            ->sum('quantity');

        $sales = (float) InventoryLog::query()
            ->where('ingredient_id', $ingredientId)
            ->where('type', 'out')
            ->whereBetween('created_at', [$periodStart, $periodEnd])
            ->where('reason', 'like', 'Order #%')
            ->sum('quantity');

        $metrics = $this->calculateBarStockMetrics(
            $openingStock,
            $addedStock,
            $transIn,
            $transOut,
            $sales,
            $closingStock,
        );

        return BarStockSheet::query()->create([
            'ingredient_id' => $ingredient->id,
            'period_start' => $periodStart->toDateString(),
            'period_end' => $periodEnd->toDateString(),
            'opening_stock' => $openingStock,
            'added_stock' => $addedStock,
            'trans_in' => $transIn,
            'trans_out' => $transOut,
            'sales' => $sales,
            'total_stock' => $metrics['total_stock'],
            'expected_closing' => $metrics['expected_closing'],
            'closing_stock' => $closingStock,
            'variance' => $metrics['variance'],
            'recorded_by' => $recordedBy,
        ]);
    }

    public function stockIn(
        int $ingredientId,
        float $quantityReceived,
        float $unitCost,
        string $supplierName,
        string $receivedAt,
        string $status = 'completed',
        ?string $receiptAttachment = null,
        ?int $userId = null
    ): Procurement {
        if ($quantityReceived <= 0) {
            throw new RuntimeException('Quantity received must be greater than zero.');
        }

        return DB::transaction(function () use ($ingredientId, $quantityReceived, $unitCost, $supplierName, $receivedAt, $status, $receiptAttachment, $userId) {
            $ingredient = Ingredient::query()->lockForUpdate()->findOrFail($ingredientId);

            $procurement = Procurement::query()->create([
                'ingredient_id' => $ingredient->id,
                'quantity_received' => $quantityReceived,
                'unit_cost' => $unitCost,
                'supplier_name' => $supplierName,
                'status' => $status,
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
