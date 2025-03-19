<?php

namespace App\Filament\Resources\ProductsStocksResource\Pages;

use App\Filament\Resources\ProductsStocksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductsStocks extends EditRecord
{
    protected static string $resource = ProductsStocksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
