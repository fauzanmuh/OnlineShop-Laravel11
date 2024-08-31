<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AlamatRelationManager extends RelationManager
{
    protected static string $relationship = 'alamat';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('Nama Depan')
                    ->required()
                    ->maxLength(250),
                
                Forms\Components\TextInput::make('last_name')
                    ->label('Nama Belakang')
                    ->required()
                    ->maxLength(250),

                Forms\Components\TextInput::make('street_address')
                    ->label('Alamat')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('Nomor Telepon')
                    ->required()
                    ->tel()
                    ->maxLength(20),

                Forms\Components\TextInput::make('city')
                    ->label('Kota')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('state')
                    ->label('Provinsi')
                    ->required()
                    ->maxLength(200),
                
                Forms\Components\TextInput::make('zip_code')
                    ->label('Kode Pos')
                    ->columns(1)
                    ->required()
                    ->maxLength(10),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                Tables\Columns\TextColumn::make('street_address')
                    ->label('Alamat')
                    ->sortable(),

                Tables\Columns\TextColumn::make('fullname')
                    ->label('Nama Penerima')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Nomor Telepon'),

                Tables\Columns\TextColumn::make('city')
                    ->label('Kota')
                    ->sortable(),

                Tables\Columns\TextColumn::make('state')
                    ->label('Provinsi')
                    ->sortable(),

                Tables\Columns\TextColumn::make('zip_code')
                    ->label('Kode Pos')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Buat Alamat Baru'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label('Lihat Alamat'),
                    Tables\Actions\EditAction::make()
                        ->label('Edit Alamat'),
                    Tables\Actions\DeleteAction::make()
                        ->label('Hapus Alamat'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Semua Alamat'),
                ]),
            ]);
    }
}
