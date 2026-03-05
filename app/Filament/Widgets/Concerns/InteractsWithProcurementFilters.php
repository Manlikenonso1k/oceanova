<?php

namespace App\Filament\Widgets\Concerns;

use App\Models\Procurement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait InteractsWithProcurementFilters
{
    protected function applyProcurementFilters(Builder $query, bool $applyDateRange = true): Builder
    {
        $startDate = $this->getFilterStartDate();
        $endDate = $this->getFilterEndDate();
        $specificDate = trim((string) ($this->filters['specific_date'] ?? ''));
        $datePreset = trim((string) ($this->filters['date_preset'] ?? ''));
        $supplier = trim((string) ($this->filters['supplier'] ?? ''));
        $category = trim((string) ($this->filters['category'] ?? ''));
        $status = trim((string) ($this->filters['status'] ?? ''));

        if ($applyDateRange) {
            if ($datePreset !== '') {
                [$presetStart, $presetEnd] = $this->resolvePresetRange($datePreset);

                $query->whereBetween('received_at', [
                    $presetStart->copy()->startOfDay(),
                    $presetEnd->copy()->endOfDay(),
                ]);
            } elseif ($specificDate !== '') {
                $query->whereDate('received_at', Carbon::parse($specificDate)->toDateString());
            } elseif ($startDate && $endDate) {
                $query->whereBetween('received_at', [
                    $startDate->copy()->startOfDay(),
                    $endDate->copy()->endOfDay(),
                ]);
            }
        }

        if ($supplier !== '') {
            $query->where('supplier_name', $supplier);
        }

        if ($category !== '') {
            $query->whereHas('ingredient', fn (Builder $ingredientQuery): Builder => $ingredientQuery->where('category', $category));
        }

        if ($status !== '') {
            $query->where('status', $status);
        }

        return $query;
    }

    protected function getFilterStartDate(): Carbon
    {
        $value = $this->filters['start_date'] ?? Procurement::query()->min('received_at') ?? now()->subDays(30)->toDateString();

        return Carbon::parse((string) $value);
    }

    protected function getFilterEndDate(): Carbon
    {
        $value = $this->filters['end_date'] ?? now()->toDateString();

        return Carbon::parse((string) $value);
    }

    protected function baseProcurementQuery(): Builder
    {
        return $this->applyProcurementFilters(
            Procurement::query()->with('ingredient')
        );
    }

    protected function resolvePresetRange(string $preset): array
    {
        return match ($preset) {
            'today' => [now(), now()],
            'yesterday' => [now()->subDay(), now()->subDay()],
            'last_7_days' => [now()->subDays(6), now()],
            default => [now()->startOfMonth(), now()],
        };
    }
}
