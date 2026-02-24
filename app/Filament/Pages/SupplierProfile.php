<?php

namespace App\Filament\Pages;

use App\Models\Procurement;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SupplierProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.supplier-profile';

    public string $supplier = '';

    public array $metrics = [];

    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->hasAnyRole(['admin', 'super_admin', 'procurement_officer']);
    }

    public static function getSlug(): string
    {
        return 'suppliers/{supplier}';
    }

    public function mount(string $supplier): void
    {
        $this->supplier = urldecode($supplier);

        $query = Procurement::query()->where('supplier_name', $this->supplier);

        $totalSpend = (float) (clone $query)->selectRaw('COALESCE(SUM(quantity_received * unit_cost), 0) as total')->value('total');
        $purchaseOrders = (int) (clone $query)->count();
        $onTimeRate = (float) (clone $query)->selectRaw('COALESCE(AVG(CASE WHEN status = "completed" THEN 100 ELSE 0 END), 0) as rate')->value('rate');
        $avgLeadTime = (float) (clone $query)->selectRaw('COALESCE(AVG(TIMESTAMPDIFF(DAY, created_at, received_at)), 0) as avg_days')->value('avg_days');

        $this->metrics = [
            'total_spend' => $totalSpend,
            'purchase_orders' => $purchaseOrders,
            'on_time_rate' => $onTimeRate,
            'avg_lead_time' => $avgLeadTime,
        ];
    }
}
