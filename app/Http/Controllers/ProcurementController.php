<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProcurementController extends Controller
{
    public function index(): JsonResponse
    {
        $records = Ingredient::query()
            ->with('procurements')
            ->orderBy('name')
            ->get();

        return response()->json($records);
    }

    public function store(Request $request, InventoryService $inventoryService): JsonResponse
    {
        $data = $request->validate([
            'ingredient_id' => ['required', 'exists:ingredients,id'],
            'quantity_received' => ['required', 'numeric', 'gt:0'],
            'unit_cost' => ['required', 'numeric', 'gte:0'],
            'supplier_name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,approved,completed'],
            'received_at' => ['required', 'date'],
        ]);

        $procurement = $inventoryService->stockIn(
            (int) $data['ingredient_id'],
            (float) $data['quantity_received'],
            (float) $data['unit_cost'],
            (string) $data['supplier_name'],
            (string) $data['received_at'],
            (string) ($data['status'] ?? 'completed'),
            null,
            $request->user()?->id,
        );

        return response()->json($procurement->load('ingredient'), 201);
    }
}
