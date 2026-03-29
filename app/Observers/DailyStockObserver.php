<?php

namespace App\Observers;

use App\Models\DailyStock;
use App\Models\Ingredient;
use App\Models\Department;
use App\Models\DepartmentStock;
use Illuminate\Support\Facades\DB;

class DailyStockObserver
{
    public function saved(DailyStock $dailyStock): void
    {
        DB::transaction(function () use ($dailyStock): void {
            // Resolve ingredient by id if present, otherwise by name
            $ingredient = null;

            if (! empty($dailyStock->ingredient_id)) {
                $ingredient = Ingredient::find($dailyStock->ingredient_id);
            }

            if (! $ingredient && ! empty($dailyStock->item_name)) {
                $ingredient = Ingredient::query()
                    ->whereRaw('LOWER(name) = ?', [strtolower($dailyStock->item_name)])
                    ->first();
            }

            // If no matching ingredient, abort early
            if (! $ingredient) {
                return;
            }

            // Update master ingredient current_stock to match closing_stock
            $ingredient->current_stock = (float) $dailyStock->closing_stock;
            $ingredient->save();

            // Determine department name keyword from category to update department stock as well
            $category = strtolower((string) $dailyStock->category);

            $department = null;

            if (str_contains($category, 'bar')) {
                $department = Department::query()->where('name', 'like', '%bar%')->first();
            } elseif (str_contains($category, 'kitchen') || str_contains($category, 'chef')) {
                $department = Department::query()->where('name', 'like', '%kitchen%')->first();
            }

            if (! $department) {
                return;
            }

            // Update or create department stock using the closing_stock as current quantity
            $deptStock = DepartmentStock::query()->firstOrCreate([
                'ingredient_id' => $ingredient->id,
                'department_id' => $department->id,
            ], [
                'quantity' => 0,
            ]);

            $deptStock->quantity = (float) $dailyStock->closing_stock;
            $deptStock->save();
        });
    }
}
