<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Str;

class OrderRelationManager extends RelationManager
{
    protected static string $relationship = 'order';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                ->label('ID Transaksi')
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
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Tables\Actions\Action::make('Lihat Orderan')
                    ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
