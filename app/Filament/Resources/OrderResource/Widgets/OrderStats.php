<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Orderan Baru', Order::where('status', 'new')->count()),
            Stat::make('Orderan di proses', Order::where('status', 'processing')->count()),
            Stat::make('Pengiriman Orderan', Order::where('status', 'shipped')->count()),
            Stat::make('Orderan Diterima', Order::where('status', 'delivered')->count()),
            Stat::make('Orderan Dibatalkan', Order::where('status', 'cancelled')->count()),
            Stat::make('Rata-rata Harga', Number::currency(Order::query()->avg('total'),'IDR')),
            
        ];
    }
}
