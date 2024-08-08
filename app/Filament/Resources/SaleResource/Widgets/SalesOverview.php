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
            Stat::make('Ordenes de artículos', Sale::where('type_sale', 'Artículos')->where('status', true)->where('state','!=','Cancelado')->get()->count())
                ->description('Cantidad de ordenes de artículos')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('danger'),
            Stat::make('Ordenes de regalos', Sale::where('type_sale', 'Regalos')->where('status',true)->where('state','!=','Cancelado')->get()->count())
                ->description('Cantidad de ordenes de regalos')
                ->descriptionIcon('heroicon-m-gift')
                ->color('info'),
        ];
    }
}
