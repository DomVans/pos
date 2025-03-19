<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StocksResource\Pages;
use App\Filament\Resources\StocksResource\RelationManagers;
use App\Models\Stocks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StocksResource extends Resource
{
    protected static ?string $model = Stocks::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationGroup = 'Inventory Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('stock_name')
                    ->label('Stock Name')
                    ->required()
                    ->unique(ignoreRecord: true) 
                    ->maxLength(255),

                Forms\Components\TextInput::make('stock_number')
                    ->label('Stock Number')
                    ->required()
                    ->dehydrated(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stock_name')
                ->label('Stock Name')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('stock_number')
                ->label('Stock Number')
                ->sortable()
                ->searchable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStocks::route('/create'),
            'edit' => Pages\EditStocks::route('/{record}/edit'),
        ];
    }
}
