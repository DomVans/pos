<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsStocksResource\Pages;
use App\Filament\Resources\ProductsStocksResource\RelationManagers;
use App\Models\ProductsStocks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Products;
use App\Models\Stocks;

class ProductsStocksResource extends Resource
{
    protected static ?string $model = ProductsStocks::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Inventory Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Products::all()->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\Select::make('stock_id')
                    ->label('Stock Number')
                    ->options(Stocks::all()->pluck('stock_number', 'id'))
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->minValue(1)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('stock.stock_number')
                    ->label('Stock Number')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('product.barcode')
                    ->label('Barcode')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('product')
                    ->relationship('product', 'name')
                    ->label('Filter by Product'),

                Tables\Filters\SelectFilter::make('stock')
                    ->relationship('stock', 'stock_number')
                    ->label('Filter by Stock'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductsStocks::route('/'),
            'create' => Pages\CreateProductsStocks::route('/create'),
            'edit' => Pages\EditProductsStocks::route('/{record}/edit'),
        ];
    }
}
