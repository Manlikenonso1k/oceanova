<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ProcurementKpiStats;
use App\Filament\Widgets\PurchaseOrderTrendChart;
use App\Filament\Widgets\SpendAnalysisChart;
use App\Filament\Widgets\SupplierPerformanceScorecard;
use App\Models\Ingredient;
use App\Models\Procurement;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProcurementDashboard extends Page
{
    use HasFiltersForm;

    protected static string $view = 'filament.pages.procurement-dashboard';

    protected static ?string $slug = 'procurement-dashboard';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Procurement Dashboard';

    protected static ?string $navigationGroup = 'Inventory & Procurement';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Procurement Officer Dashboard';

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'procurement_officer']);
    }

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                DatePicker::make('start_date')
                    ->label('Start Date')
                    ->default(fn (): string => (string) (Procurement::query()->min('received_at') ?? now()->subDays(30)->toDateString()))
                    ->live(),

                DatePicker::make('end_date')
                    ->label('End Date')
                    ->default(now()->toDateString())
                    ->live(),

                DatePicker::make('specific_date')
                    ->label('Specific Day')
                    ->placeholder('Optional: filter one date')
                    ->native(false)
                    ->live(),

                Select::make('date_preset')
                    ->label('Quick Date')
                    ->options([
                        'today' => 'Today',
                        'yesterday' => 'Yesterday',
                        'last_7_days' => 'Last 7 Days',
                    ])
                    ->placeholder('No preset')
                    ->live(),

                Select::make('supplier')
                    ->label('Supplier')
                    ->options(fn (): array => Procurement::query()
                        ->select('supplier_name')
                        ->whereNotNull('supplier_name')
                        ->orderBy('supplier_name')
                        ->distinct()
                        ->pluck('supplier_name', 'supplier_name')
                        ->all())
                    ->searchable()
                    ->placeholder('All suppliers')
                    ->live(),

                Select::make('category')
                    ->label('Category')
                    ->options(fn (): array => Ingredient::query()
                        ->select('category')
                        ->whereNotNull('category')
                        ->orderBy('category')
                        ->distinct()
                        ->pluck('category', 'category')
                        ->all())
                    ->searchable()
                    ->placeholder('All categories')
                    ->live(),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'completed' => 'Completed',
                    ])
                    ->placeholder('All statuses')
                    ->live(),
            ])
            ->columns(7);
    }

    public function getHeaderWidgets(): array
    {
        return [
            ProcurementKpiStats::class,
            SpendAnalysisChart::class,
            PurchaseOrderTrendChart::class,
            SupplierPerformanceScorecard::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn (): StreamedResponse => $this->exportCsv()),
            Action::make('exportExcel')
                ->label('Export Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->action(fn (): StreamedResponse => $this->exportExcel()),
        ];
    }

    protected function getFilteredProcurements(): Collection
    {
        $query = Procurement::query()->with('ingredient');

        $startDate = Carbon::parse((string) ($this->filters['start_date'] ?? now()->startOfMonth()->toDateString()));
        $endDate = Carbon::parse((string) ($this->filters['end_date'] ?? now()->toDateString()));
        $specificDate = trim((string) ($this->filters['specific_date'] ?? ''));
        $datePreset = trim((string) ($this->filters['date_preset'] ?? ''));
        $supplier = trim((string) ($this->filters['supplier'] ?? ''));
        $category = trim((string) ($this->filters['category'] ?? ''));
        $status = trim((string) ($this->filters['status'] ?? ''));

        if ($datePreset !== '') {
            [$presetStart, $presetEnd] = $this->resolvePresetRange($datePreset);

            $query->whereBetween('received_at', [
                $presetStart->copy()->startOfDay(),
                $presetEnd->copy()->endOfDay(),
            ]);
        } elseif ($specificDate !== '') {
            $query->whereDate('received_at', Carbon::parse($specificDate)->toDateString());
        } else {
            $query->whereBetween('received_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ]);
        }

        if ($supplier !== '') {
            $query->where('supplier_name', $supplier);
        }

        if ($category !== '') {
            $query->whereHas('ingredient', fn (Builder $builder): Builder => $builder->where('category', $category));
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        return $query->orderByDesc('received_at')->get();
    }

    private function resolvePresetRange(string $preset): array
    {
        return match ($preset) {
            'today' => [now(), now()],
            'yesterday' => [now()->subDay(), now()->subDay()],
            'last_7_days' => [now()->subDays(6), now()],
            default => [now()->startOfMonth(), now()],
        };
    }

    protected function exportCsv(): StreamedResponse
    {
        $filename = 'procurement-dashboard-' . now()->format('Ymd-His') . '.csv';
        $rows = $this->getFilteredProcurements();

        return response()->streamDownload(function () use ($rows): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            fputcsv($stream, ['PO ID', 'Ingredient', 'Category', 'Supplier', 'Quantity', 'Amount', 'Total', 'Status', 'Received At']);

            foreach ($rows as $row) {
                $lineTotal = (float) ($row->unit_cost ?? 0) > 0
                    ? (float) $row->unit_cost
                    : ((float) ($row->unit_price ?? 0) > 0 && (float) ($row->quantity_received ?? 0) > 0
                        ? (float) $row->unit_price * (float) $row->quantity_received
                        : 0.0);

                fputcsv($stream, [
                    $row->id,
                    $row->ingredient?->name,
                    $row->ingredient?->category,
                    $row->supplier_name,
                    $row->quantity_received,
                    $row->unit_cost,
                    $lineTotal,
                    $row->status,
                    optional($row->received_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($stream);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function exportExcel(): StreamedResponse
    {
        $filename = 'procurement-dashboard-' . now()->format('Ymd-His') . '.xls';
        $rows = $this->getFilteredProcurements();

        return response()->streamDownload(function () use ($rows): void {
            echo "<table border='1'>";
            echo '<tr><th>PO ID</th><th>Ingredient</th><th>Category</th><th>Supplier</th><th>Quantity</th><th>Amount</th><th>Total</th><th>Status</th><th>Received At</th></tr>';

            foreach ($rows as $row) {
                $lineTotal = (float) ($row->unit_cost ?? 0) > 0
                    ? (float) $row->unit_cost
                    : ((float) ($row->unit_price ?? 0) > 0 && (float) ($row->quantity_received ?? 0) > 0
                        ? (float) $row->unit_price * (float) $row->quantity_received
                        : 0.0);

                echo '<tr>';
                echo '<td>' . e((string) $row->id) . '</td>';
                echo '<td>' . e((string) ($row->ingredient?->name ?? '')) . '</td>';
                echo '<td>' . e((string) ($row->ingredient?->category ?? '')) . '</td>';
                echo '<td>' . e((string) $row->supplier_name) . '</td>';
                echo '<td>' . e((string) $row->quantity_received) . '</td>';
                echo '<td>' . e((string) $row->unit_cost) . '</td>';
                echo '<td>' . e((string) $lineTotal) . '</td>';
                echo '<td>' . e((string) ($row->status ?? '')) . '</td>';
                echo '<td>' . e((string) optional($row->received_at)->format('Y-m-d H:i:s')) . '</td>';
                echo '</tr>';
            }

            echo '</table>';
        }, $filename, ['Content-Type' => 'application/vnd.ms-excel']);
    }
}
