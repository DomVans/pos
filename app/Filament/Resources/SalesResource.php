<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesResource\Pages;
use App\Filament\Resources\SalesResource\RelationManagers;
use App\Models\Sales;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static ?string $navigationGroup = 'Sales Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Sale Details')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('User')
                        ->relationship('user', 'name')
                        ->default(auth()->id())  
                        ->disabled()  
                        ->dehydrated()
                        ->required(),

                    Forms\Components\TextInput::make('customer_mobile_number')
                        ->label('Customer Mobile Number')
                        ->required()
                        ->maxLength(15),

                    Forms\Components\TextInput::make('total_amount')
                        ->label('Total Amount')
                        ->numeric()
                        ->dehydrated()
                        ->disabled(0)
                        ->live()
                        ,

                    Forms\Components\TextInput::make('discount')
                        ->label('Discount')
                        ->numeric()
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                        $set('final_amount', ($get('total_amount') ?? 0) - $state))
                        ->live(),

                    Forms\Components\TextInput::make('final_amount')
                        ->label('Final Amount')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                ]),

                Forms\Components\Repeater::make('saleItems')
                ->relationship('saleItems')
                ->schema([
                    Forms\Components\Select::make('product_stock_id')
                        ->label('Product & Stock')
                        ->options(function () {
                            return \App\Models\ProductsStocks::with('product', 'stock')
                                ->get()
                                ->mapWithKeys(function ($productStock) {
                                    return [
                                        $productStock->id => "{$productStock->product->name} - Stock Number {$productStock->stock->stock_number} (Barcode: {$productStock->product->barcode})"
                                    ];
                                });
                        })
                        ->searchable()
                        ->required()
                        ->live() 
                        ->afterStateUpdated(fn ($state, callable $set) => 
                            $set('unit_price', \App\Models\ProductsStocks::find($state)?->price ?? 0)
                        ),
            
                    
                    Forms\Components\TextInput::make('unit_price')
                        ->label('Unit Price')
                        ->numeric()
                        ->disabled() 
                        ->dehydrated(),
            
                    
                        Forms\Components\TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set, callable $get) {
                            $availableStock = \App\Models\ProductsStocks::find($get('product_stock_id'))?->quantity ?? 0;
                    
                            if ($state > $availableStock) {
                                $set('quantity', $availableStock); 
                                $set('error_message', "Only $availableStock items available in stock!");
                            } else {
                                $set('error_message', null);
                            }
                    
                            
                            $set('subtotal', ($get('unit_price') ?? 0) * $get('quantity'));
                        }),

                        Forms\Components\TextInput::make('error_message')
                        ->label('Error')
                        ->disabled()
                        ->hidden(fn ($state) => $state === null),
            
                    
                    Forms\Components\TextInput::make('subtotal')
                        ->label('Subtotal')
                        ->numeric()
                        ->disabled()
                        ->dehydrated(), 
                ])
                ->columns(4)
                ->live()
                ->afterStateUpdated(fn ($state, callable $set) => 
                $set('total_amount', collect($state)->sum('subtotal')),
       ) ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\TextColumn::make('id')
                        ->label('Sale ID')
                        ->sortable(),
        
                    Tables\Columns\TextColumn::make('user.name')
                        ->label('User')
                        ->sortable()
                        ->searchable(),
        
                    Tables\Columns\TextColumn::make('customer_mobile_number')
                        ->label('Customer Mobile')
                        ->searchable(),
        
                    Tables\Columns\TextColumn::make('total_amount')
                        ->label('Total Amount')
                        ->sortable()
                        ->money('INR'),
        
                    Tables\Columns\TextColumn::make('discount')
                        ->label('Discount')
                        ->sortable()
                        ->money('INR'),
        
                    Tables\Columns\TextColumn::make('final_amount')
                        ->label('Final Amount')
                        ->sortable()
                        ->money('INR'),
        
                    Tables\Columns\TextColumn::make('created_at')
                        ->label('Sale Date')
                        ->sortable()
                        ->dateTime('F j, Y, g:i a'),
                ])
            ->filters([
                Tables\Filters\Filter::make('Today’s Sales')
                ->query(fn (Builder $query) => $query->whereDate('created_at', now()))
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
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSales::route('/create'),
            'edit' => Pages\EditSales::route('/{record}/edit'),
        ];
    }
}
