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
            Stat::make('Artículos', Product::where('status', true)->get()->count())
                ->description('Total de artículos')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary'),
            Stat::make('Catálago', Product::where('status_wa', true)->get()->count())
                ->description('Catálago de whatsapp')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('success'),
            Stat::make('Artículos', Product::where('status', false)->get()->count())
                ->description('Artículos desactivados')
                ->descriptionIcon('heroicon-m-shield-exclamation')
                ->color('danger'),
        ];
    }
}
