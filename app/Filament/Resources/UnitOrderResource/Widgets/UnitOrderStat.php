<?php

namespace App\Filament\Resources\UnitOrderResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\UnitOrder;

class UnitOrderStat extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('New Orders', UnitOrder::where('status', 'pending')->count())
                ->chart([
                    UnitOrder::where('status', 'pending')
                        ->whereDate('created_at', '>', now()->subDays(7))
                        ->count(),
                    UnitOrder::where('status', 'pending')
                        ->whereDate('created_at', '<=', now()->subDays(7))
                        ->whereDate('created_at', '>', now()->subDays(14))
                        ->count(),
                    UnitOrder::where('status', 'pending')
                        ->whereDate('created_at', '<=', now()->subDays(14))
                        ->whereDate('created_at', '>', now()->subDays(21))
                        ->count(),
                ])
                ->description('Last 7, 14, 21 days')
                ->color('success'),
            Stat::make('Processing Orders', UnitOrder::where('status', 'processing')->count())
                ->chart([
                    UnitOrder::where('status', 'processing')
                        ->whereDate('created_at', '>', now()->subDays(7))
                        ->count(),
                    UnitOrder::where('status', 'processing')
                        ->whereDate('created_at', '<=', now()->subDays(7))
                        ->whereDate('created_at', '>', now()->subDays(14))
                        ->count(),
                ])
                ->description('Last 7, 14 days')
                ->color('warning'),
            Stat::make('Confirmed Orders', UnitOrder::where('status', 'confirmed')->count())
                ->chart([
                    UnitOrder::where('status', 'confirmed')
                        ->whereDate('created_at', '>', now()->subDays(7))
                        ->count(),
                    UnitOrder::where('status', 'confirmed')
                        ->whereDate('created_at', '<=', now()->subDays(7))
                        ->whereDate('created_at', '>', now()->subDays(14))
                        ->count(),
                ])
                ->description('Last 7, 14 days')
                ->color('success'),
            Stat::make('Completed Orders', UnitOrder::where('status', 'completed')->count())
                ->chart([
                    UnitOrder::where('status', 'completed')
                        ->whereDate('created_at', '>', now()->subDays(7))
                        ->count(),
                    UnitOrder::where('status', 'completed')
                        ->whereDate('created_at', '<=', now()->subDays(7))
                        ->whereDate('created_at', '>', now()->subDays(14))
                        ->count(),
                ])
                ->description('Last 7, 14 days')
                ->color('success'),
        ];
    }
}
