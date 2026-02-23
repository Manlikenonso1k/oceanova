<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KitchenInventoryController extends Controller
{
    public function stockLevels(): JsonResponse
    {
        return response()->json(
            Ingredient::query()->orderBy('name')->get()
        );
    }

    public function lowStock(): JsonResponse
    {
        return response()->json(
            Ingredient::query()->lowStock()->orderBy('name')->get()
        );
    }

    public function logWaste(Request $request, InventoryService $inventoryService): JsonResponse
    {
        $data = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'quantity' => ['required', 'numeric', 'gt:0'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        $wasteLog = $inventoryService->logWaste(
            (int) $data['ingredient_id'],
            (float) $data['quantity'],
            (string) $data['reason'],
            $request->user()?->id,
        );

        return response()->json($wasteLog->load('ingredient'), 201);
    }
}
