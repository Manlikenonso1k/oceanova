<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Services\InventoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Throwable;

class ProcurementTemplateController extends Controller
{
    public function index(): View
    {
        $ingredients = Ingredient::query()
            ->select(['id', 'name', 'category', 'unit'])
            ->orderBy('name')
            ->get();

        return view('procurement.live-template', [
            'ingredients' => $ingredients,
            'today' => now()->toDateString(),
        ]);
    }

    public function store(Request $request, InventoryService $inventoryService): RedirectResponse
    {
        $rows = $request->input('rows', []);

        if (!is_array($rows) || $rows === []) {
            return back()->with('error', 'No rows submitted.');
        }

        $ingredientIds = Ingredient::query()->pluck('id')->all();
        $validIngredientIds = array_map('intval', $ingredientIds);

        $created = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            $line = (int) $index + 1;

            try {
                if (!is_array($row)) {
                    $skipped++;
                    continue;
                }

                $ingredientId = (int) ($row['ingredient_id'] ?? 0);
                $quantity = (float) trim((string) ($row['quantity_received'] ?? '0'));
                $unitCost = (float) trim((string) ($row['unit_cost'] ?? '0'));
                $supplierName = trim((string) ($row['supplier_name'] ?? ''));
                $status = trim((string) ($row['status'] ?? 'completed'));
                $receivedAtRaw = trim((string) ($row['received_at'] ?? ''));

                $isBlank = $quantity <= 0
                    && $unitCost <= 0
                    && $supplierName === ''
                    && $receivedAtRaw === '';

                if ($isBlank) {
                    $skipped++;
                    continue;
                }

                if (!in_array($ingredientId, $validIngredientIds, true)) {
                    throw new \RuntimeException('Invalid ingredient selected.');
                }

                if ($quantity <= 0) {
                    throw new \RuntimeException('Quantity must be greater than zero.');
                }

                if ($unitCost < 0) {
                    throw new \RuntimeException('Unit cost cannot be negative.');
                }

                if ($supplierName === '') {
                    throw new \RuntimeException('Supplier name is required.');
                }

                if ($receivedAtRaw === '') {
                    throw new \RuntimeException('Received date is required.');
                }

                $receivedAt = Carbon::parse($receivedAtRaw)->toDateTimeString();
                $status = in_array($status, ['pending', 'approved', 'completed'], true) ? $status : 'completed';

                $inventoryService->stockIn(
                    $ingredientId,
                    $quantity,
                    $unitCost,
                    $supplierName,
                    $receivedAt,
                    $status,
                    null,
                    $request->user()?->id,
                );

                $created++;
            } catch (Throwable $exception) {
                $errors[] = 'Row ' . $line . ': ' . $exception->getMessage();
            }
        }

        if ($errors !== []) {
            return back()->with('error', 'Imported ' . $created . ' rows, skipped ' . $skipped . '. First error: ' . $errors[0]);
        }

        return back()->with('success', 'Imported ' . $created . ' rows successfully. Skipped ' . $skipped . ' blank rows.');
    }
}
