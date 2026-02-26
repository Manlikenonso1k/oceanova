<?php

namespace App\Filament\Resources\ProcurementResource\Pages;

use App\Filament\Resources\ProcurementResource;
use App\Models\Ingredient;
use App\Services\InventoryService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ListProcurements extends ListRecords
{
    protected static string $resource = ProcurementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('openTemplate')
                ->label('Open Live Template')
                ->icon('heroicon-o-document-text')
                ->color('primary')
                ->tooltip('Template now uses live ingredients from your database to avoid stale rows (e.g. unrelated items).')
                ->url(route('procurements.live-template'), shouldOpenInNewTab: true),
            Actions\Action::make('downloadCsvTemplate')
                ->label('Download CSV Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->tooltip('Use Download CSV Template. Template now uses live ingredients from your database to avoid stale rows (e.g. unrelated items).')
                ->action(fn (): StreamedResponse => $this->downloadCsvTemplate()),
            Actions\Action::make('importCsv')
                ->label('Import CSV')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('CSV File')
                        ->disk('local')
                        ->directory('imports/procurements')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->importCsv($data);
                }),
        ];
    }

    private function downloadCsvTemplate(): StreamedResponse
    {
        $filename = 'procurement-template-' . now()->format('Ymd-His') . '.csv';
        $ingredients = Ingredient::query()
            ->select(['id', 'name', 'category', 'unit'])
            ->orderBy('name')
            ->get();

        return response()->streamDownload(function () use ($ingredients): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['ingredient_id', 'ingredient_name', 'category', 'unit', 'quantity_received', 'unit_price', 'amount_total', 'supplier_name', 'status', 'received_at']);

            foreach ($ingredients as $ingredient) {
                fputcsv($stream, [
                    $ingredient->id,
                    $ingredient->name,
                    $ingredient->category,
                    $ingredient->unit,
                    '',
                    '',
                    '',
                    '',
                    'completed',
                    now()->toDateString(),
                ]);
            }

            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function importCsv(array $data): void
    {
        $path = (string) ($data['file'] ?? '');

        if ($path === '') {
            Notification::make()->title('No file selected')->danger()->send();
            return;
        }

        $absolutePath = Storage::disk('local')->path($path);
        $handle = fopen($absolutePath, 'r');

        if ($handle === false) {
            Notification::make()->title('Unable to read CSV file')->danger()->send();
            return;
        }

        $header = fgetcsv($handle) ?: [];
        $header = array_map(fn (?string $value): string => strtolower(trim((string) $value)), $header);

        $required = ['quantity_received', 'supplier_name', 'received_at'];
        foreach ($required as $column) {
            if (!in_array($column, $header, true)) {
                fclose($handle);
                Notification::make()->title("Missing required column: {$column}")->danger()->send();
                return;
            }
        }

        if (!in_array('amount_total', $header, true) && !in_array('unit_cost', $header, true) && !in_array('unit_price', $header, true)) {
            fclose($handle);
            Notification::make()->title('Missing amount column: provide amount_total, unit_cost, or unit_price')->danger()->send();
            return;
        }

        if (!in_array('ingredient_id', $header, true) && !in_array('ingredient_name', $header, true)) {
            fclose($handle);
            Notification::make()->title('Missing ingredient column: ingredient_id or ingredient_name is required')->danger()->send();
            return;
        }

        $index = array_flip($header);
        $ingredientMap = Ingredient::query()
            ->select(['id', 'name'])
            ->get()
            ->mapWithKeys(fn (Ingredient $ingredient): array => [mb_strtolower(trim($ingredient->name)) => $ingredient->id]);

        $created = 0;
        $skipped = 0;
        $errors = [];
        $line = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $line++;

            try {
                $ingredientIdRaw = trim((string) ($row[$index['ingredient_id']] ?? ''));
                $ingredientName = trim((string) ($row[$index['ingredient_name']] ?? ''));
                $quantityRaw = trim((string) ($row[$index['quantity_received']] ?? ''));
                $unitPriceRaw = trim((string) ($row[$index['unit_price']] ?? ''));
                $amountTotalRaw = trim((string) ($row[$index['amount_total']] ?? ($row[$index['unit_cost']] ?? '')));
                $supplierName = trim((string) ($row[$index['supplier_name']] ?? ''));
                $receivedAtRaw = trim((string) ($row[$index['received_at']] ?? ''));
                $status = trim((string) ($row[$index['status']] ?? 'completed'));

                $quantity = $quantityRaw === '' ? 0.0 : (float) $quantityRaw;
                $unitPrice = $unitPriceRaw === '' ? null : (float) $unitPriceRaw;
                $amountTotal = $amountTotalRaw === '' ? 0.0 : (float) $amountTotalRaw;

                if ($ingredientName === '' && $quantityRaw === '' && $unitPriceRaw === '' && $amountTotalRaw === '' && $supplierName === '' && $receivedAtRaw === '') {
                    $skipped++;
                    continue;
                }

                if ($ingredientName === '' || $quantity <= 0 || $supplierName === '' || $receivedAtRaw === '') {
                    throw new \RuntimeException('Invalid required values in row.');
                }

                if ($unitPrice !== null && $unitPrice <= 0) {
                    throw new \RuntimeException('Unit price must be greater than zero when provided.');
                }

                if ($amountTotal <= 0 && $unitPrice !== null && $unitPrice > 0) {
                    $amountTotal = $quantity * $unitPrice;
                }

                if ($amountTotal <= 0) {
                    throw new \RuntimeException('Provide amount_total (or unit_cost) or a valid unit_price.');
                }

                $ingredientId = null;
                if ($ingredientIdRaw !== '' && is_numeric($ingredientIdRaw)) {
                    $ingredientId = (int) $ingredientIdRaw;
                }

                if (!$ingredientId && $ingredientName !== '') {
                    $ingredientId = $ingredientMap[mb_strtolower($ingredientName)] ?? null;
                }

                if (!$ingredientId) {
                    throw new \RuntimeException("Ingredient not found: {$ingredientName}");
                }

                $receivedAt = Carbon::parse($receivedAtRaw)->toDateTimeString();
                $status = in_array($status, ['pending', 'approved', 'completed'], true) ? $status : 'completed';

                app(InventoryService::class)->stockIn(
                    (int) $ingredientId,
                    $quantity,
                    $amountTotal,
                    $supplierName,
                    $receivedAt,
                    $status,
                    $unitPrice,
                    null,
                    auth()->id(),
                );

                $created++;
            } catch (Throwable $exception) {
                $errors[] = 'Line ' . $line . ': ' . $exception->getMessage();
            }
        }

        fclose($handle);
        Storage::disk('local')->delete($path);

        if ($errors !== []) {
            Notification::make()
                ->title('Import completed with errors')
                ->body('Created: ' . $created . ', Skipped: ' . $skipped . '. First error: ' . $errors[0])
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('CSV import successful')
            ->body('Created: ' . $created . ', Skipped: ' . $skipped)
            ->success()
            ->send();
    }
}
