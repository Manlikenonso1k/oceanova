<?php

namespace App\Observers;

use App\Models\DailyStock;
use App\Models\Ingredient;
use App\Models\Department;
use App\Models\DepartmentStock;

class DailyStockObserver
{
    public function saved(DailyStock $dailyStock): void
    {
        // Try to find the ingredient by name
        $ingredient = Ingredient::query()
            ->whereRaw('LOWER(name) = ?', [strtolower($dailyStock->item_name)])
            ->first();

        if (! $ingredient) {
            return;
        }

        // Determine department name keyword from category
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
        $deptStock = DepartmentStock::query()->firstOrCreate(
            [
                'ingredient_id' => $ingredient->id,
                'department_id' => $department->id,
            ],
            [
                'quantity' => 0,
            ],
        );

        $deptStock->quantity = (float) $dailyStock->closing_stock;
        $deptStock->save();
    }
}
