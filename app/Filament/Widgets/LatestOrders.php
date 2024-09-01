<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    public static function getPluralLabel(): string
    {
        return 'Kategori';
    }
    protected int | string | array $columnSpan = 'full';
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                ->label('ID Transaksi')
                ->sortable()
                ->searchable(),

            TextColumn::make('user.name')
                ->label('Pembeli')
                ->searchable(),

            TextColumn::make('status')
                ->label('Status Transaksi')
                ->badge()
                ->color(fn ($record) => match ($record->status) {
                    'new' => 'info',
                    'processing' => 'warning',
                    'shipped' => 'info',
                    'delivered' => 'success',
                    'cancelled' => 'danger',
                })
                ->icon(fn ($record) => match ($record->status) {
                    'new' => 'heroicon-o-shopping-cart',
                    'processing' => 'heroicon-o-clock',
                    'shipped' => 'heroicon-o-truck',
                    'delivered' => 'heroicon-o-check-circle',
                    'cancelled' => 'heroicon-o-x-circle',
                }),

            TextColumn::make('total')
                ->label('Total Transaksi')
                ->money('Rp.'),

            TextColumn::make('created_at')
                ->label('Waktu Transaksi')
                ->dateTime(),
            ])
            
            ->actions([
                \Filament\Tables\Actions\Action::make('Lihat Detail')
                    ->url(fn (Order $record): string => OrderResource::getUrl('view', ['record' => $record]))
                    ->icon('heroicon-o-eye'),
            ]);
    }
}
