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
            'unit_cost' => ['nullable', 'numeric', 'gte:0'],
            'unit_price' => ['nullable', 'numeric', 'gt:0'],
            'supplier_name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'in:pending,approved,completed'],
            'received_at' => ['required', 'date'],
        ]);

        $quantityReceived = (float) $data['quantity_received'];
        $amountTotal = (float) ($data['unit_cost'] ?? 0);
        $unitPrice = isset($data['unit_price']) ? (float) $data['unit_price'] : null;

        if ($amountTotal <= 0 && $unitPrice !== null && $unitPrice > 0) {
            $amountTotal = $quantityReceived * $unitPrice;
        }

        $procurement = $inventoryService->stockIn(
            (int) $data['ingredient_id'],
            $quantityReceived,
            $amountTotal,
            (string) $data['supplier_name'],
            (string) $data['received_at'],
            (string) ($data['status'] ?? 'completed'),
            $unitPrice,
            null,
            $request->user()?->id,
        );

        return response()->json($procurement->load('ingredient'), 201);
    }
}
