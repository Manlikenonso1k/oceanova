<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ProcurementDashboard;
use App\Filament\Widgets\ClickTrendChart;
use App\Filament\Widgets\DepartmentTransferBalanceWidget;
use App\Filament\Widgets\LowStockAlertsTable;
use App\Filament\Widgets\MealPopularityChart;
use App\Filament\Widgets\OrderStatsOverviewWidget;
use App\Filament\Widgets\OrderStatusChart;
use App\Filament\Widgets\SalesPieChartWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('')
            ->brandLogo(asset('images/oceanova.png'))
            ->darkModeBrandLogo(asset('images/oceanova.png'))
            ->favicon(asset('oceanova-fav-icon.png'))
            ->brandLogoHeight('2.5rem')
            ->topbar(true)
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                ProcurementDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                OrderStatsOverviewWidget::class,
                LowStockAlertsTable::class,
                MealPopularityChart::class,
                SalesPieChartWidget::class,
                OrderStatusChart::class,
                ClickTrendChart::class,
                DepartmentTransferBalanceWidget::class,
                \App\Filament\Widgets\TopConsumedWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
