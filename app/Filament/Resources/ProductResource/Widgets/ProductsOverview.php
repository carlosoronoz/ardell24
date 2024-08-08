<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProductsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Artículos', Product::where('type', 'Artículo')->get()->count())
                ->description('Total de artículos')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('success'),
            Stat::make('Servicios', Product::where('type', 'Servicio')->get()->count())
                ->description('Total de servicios')
                ->descriptionIcon('heroicon-m-heart')
                ->color('danger'),
            Stat::make('Regalos', Product::where('type', 'Regalo de artículo')->orWhere('type', 'Regalo de servicio')->get()->count())
                ->description('Total de regalos')
                ->descriptionIcon('heroicon-m-gift')
                ->color('info'),
        ];
    }
}
