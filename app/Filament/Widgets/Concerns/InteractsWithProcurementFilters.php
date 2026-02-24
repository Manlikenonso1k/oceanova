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
        $supplier = trim((string) ($this->filters['supplier'] ?? ''));
        $category = trim((string) ($this->filters['category'] ?? ''));
        $status = trim((string) ($this->filters['status'] ?? ''));

        if ($applyDateRange && $startDate && $endDate) {
            $query->whereBetween('received_at', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ]);
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
        $value = $this->filters['start_date'] ?? now()->startOfMonth()->toDateString();

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
}
