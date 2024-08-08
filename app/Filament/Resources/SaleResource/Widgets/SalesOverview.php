<?php

namespace App\Filament\Resources\SaleResource\Widgets;

use App\Models\Sale;
use Illuminate\Support\Number;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class SalesOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $grand_total =Number::currency(Sale::query()->where('status', true)->where('state','Aprobado')->sum('total_amount'), 'UYU');
        return [
            Stat::make('Ventas', $grand_total)
                ->description('Total general de ventas')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Ordenes de compra', Sale::where('status', true)->where('state','!=','Anulado')->get()->count())
                ->description('Cantidad de ordenes de compra')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            Stat::make('Ordenes pendiente', Sale::where('status',true)->where('state','Pendiente')->get()->count())
                ->description('Cantidad de ordenes pendiente')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color('danger'),
        ];
    }
}
