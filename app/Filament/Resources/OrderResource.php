<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AlamatRelationManager;
use App\Models\Order;
use App\Models\Produk;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Number;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 5;
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Informasi Pesanan')->schema([
                        Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->required()
                        ->preload(),

                    Select::make('payment_method')
                        ->options([
                            'Cash On Delivery' => 'Cash On Delivery',
                            'Midtrans' => 'Midtrans',
                        ])
                        ->required(),

                    Select::make('payment_status')
                        ->options([
                            'Pending' => 'Pending',
                            'Paid' => 'Terbayar',
                            'Failed' => 'Gagal',
                        ])
                        ->required()
                        ->default('Pending'),

                    ToggleButtons::make('status')
                        ->label('Status Pesanan')
                        ->inline()
                        ->required()
                        ->default('new')
                        ->options([
                            'new' => 'Baru',
                            'processing' => 'Proses',
                            'shipped' => 'Dikirim',
                            'delivered' => 'Diterima',
                            'cancelled' => 'Dibatalkan',
                        ])
                        ->colors([
                            'new' => 'info',
                            'processing' => 'warning',
                            'shipped' => 'info',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                        ])
                        ->icons([
                            'new' => 'heroicon-m-shopping-cart',
                            'processing' => 'heroicon-m-arrow-path',
                            'shipped' => 'heroicon-m-truck',
                            'delivered' => 'heroicon-m-check-circle',
                            'cancelled' => 'heroicon-m-x-circle',
                        ]),

                        Select::make('currency')
                        ->options([
                            'IDR' => 'IDR',
                            'USD' => 'USD',
                        ])
                        ->default('IDR')
                        ->required(),

                        Select::make('shipping_method')
                        ->options([
                            'JNE' => 'JNE',
                            'JNT' => 'JNT',
                            'TIKI' => 'TIKI',
                            'Anter Aja' => 'Anter Aja',
                        ])
                        ->required()
                        ->default('JNT'),

                        Textarea::make('notes')
                        ->label('Catatan')
                        ->columnSpanFull(),
                    ])->columns(2),

                    Section::make('Barang Yang Dipesan')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([
                            Select::make('produk_id')
                                ->relationship('produk', 'name')
                                ->searchable()
                                ->required()
                                ->preload()
                                ->distinct()
                                ->columnSpan(4)
                                ->reactive()
                                ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Produk::find($state)?->price ?? 0))
                                ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Produk::find($state)?->price ?? 0))
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),

                            TextInput::make('quantity')
                                ->label('Jumlah')
                                ->numeric()
                                ->minvalue(1)
                                ->required()
                                ->default(1)
                                ->reactive()
                                ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount')))
                                ->columnSpan(2),

                            TextInput::make('unit_amount')
                                ->label('Harga Satuan')
                                ->required()
                                ->numeric()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(3),

                            TextInput::make('total_amount')
                                ->label('Total Harga')
                                ->numeric()
                                ->required()
                                ->dehydrated()
                                ->columnSpan(3),
                        ])->columns(12),

                        Placeholder::make('grand_total_placeholder')
                            ->label('Total Harga')
                            ->content(function (Get $get, Set $set) {
                                $total = 0;
                                if (!$repeater = $get('items')) {
                                    return $total;
                                }
                                foreach ($repeater as $key => $repeater) {
                                    $total += $get('items.'.$key.'.total_amount');
                                }
                                $set('total', $total);
                                return Number::currency($total, 'IDR');
                            }),

                        Hidden::make('total')
                            ->default(0),
                    ])

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('total')
                    ->label('Total Harga')
                    ->money('Rp.')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Metode Bayar')
                    ->sortable(),

                TextColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->sortable(),
                
                TextColumn::make('currency')
                    ->label('Mata Uang')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('shipping_method')
                    ->label('Nama Ekspedisi')
                    ->sortable()
                    ->searchable(),

                SelectColumn::make('status')
                    ->label('Status Pesanan')
                    ->sortable()
                    ->options([
                        'new' => 'Baru',
                        'processing' => 'Proses',
                        'shipped' => 'Dikirim',
                        'delivered' => 'Diterima',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Diubah Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string {
        return static::getModel()::count();
    }

    public static function getRelations(): array
    {
        return [
            AlamatRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
