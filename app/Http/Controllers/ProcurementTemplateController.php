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

        $ingredientLookup = Ingredient::query()
            ->select(['id', 'name'])
            ->get()
            ->mapWithKeys(fn (Ingredient $ingredient): array => [(int) $ingredient->id => (string) $ingredient->name]);

        $validIngredientIds = $ingredientLookup->keys()->all();

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
                $ingredientName = (string) ($ingredientLookup[$ingredientId] ?? ('Ingredient #' . $ingredientId));

                $quantityRaw = trim((string) ($row['quantity_received'] ?? ''));
                $unitPriceRaw = trim((string) ($row['unit_price'] ?? ''));
                $amountTotalRaw = trim((string) ($row['amount_total'] ?? ($row['unit_cost'] ?? '')));
                $supplierName = trim((string) ($row['supplier_name'] ?? ''));
                $status = trim((string) ($row['status'] ?? 'completed'));
                $receivedAtRaw = trim((string) ($row['received_at'] ?? ''));

                $quantity = $quantityRaw === '' ? 0.0 : (float) $quantityRaw;
                $unitPrice = $unitPriceRaw === '' ? null : (float) $unitPriceRaw;
                $amountTotal = $amountTotalRaw === '' ? 0.0 : (float) $amountTotalRaw;

                $isBlank = $quantityRaw === ''
                    && $unitPriceRaw === ''
                    && $amountTotalRaw === ''
                    && $supplierName === ''
                    && $receivedAtRaw === '';

                if ($isBlank) {
                    $skipped++;
                    continue;
                }

                if (!in_array($ingredientId, $validIngredientIds, true)) {
                    throw new \RuntimeException('Invalid ingredient selected.');
                }

                if ($quantityRaw === '') {
                    throw new \RuntimeException('Quantity is required.');
                }

                if ($quantity <= 0) {
                    throw new \RuntimeException('Quantity must be greater than zero.');
                }

                if ($unitPrice !== null && $unitPrice <= 0) {
                    throw new \RuntimeException('Unit price must be greater than zero when provided.');
                }

                if ($amountTotal <= 0 && $unitPrice !== null && $unitPrice > 0) {
                    $amountTotal = $quantity * $unitPrice;
                }

                if ($amountTotal <= 0) {
                    throw new \RuntimeException('Amount (total) is required, or provide a valid unit price.');
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
                    $amountTotal,
                    $supplierName,
                    $receivedAt,
                    $status,
                    $unitPrice,
                    null,
                    $request->user()?->id,
                );

                $created++;
            } catch (Throwable $exception) {
                $rowIngredientId = (int) ($row['ingredient_id'] ?? 0);
                $rowIngredientName = (string) ($ingredientLookup[$rowIngredientId] ?? ('Ingredient #' . $rowIngredientId));

                $errors[] = 'Row ' . $line . ' (' . $rowIngredientName . '): ' . $exception->getMessage();
            }
        }

        if ($errors !== []) {
            return back()->with('error', 'Imported ' . $created . ' rows, skipped ' . $skipped . '. First error: ' . $errors[0]);
        }

        return back()->with('success', 'Imported ' . $created . ' rows successfully. Skipped ' . $skipped . ' blank rows.');
    }
}
