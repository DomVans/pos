<?php

namespace App\Filament\Resources\SaleItemsResource\Pages;

use App\Filament\Resources\SaleItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSaleItems extends ListRecords
{
    protected static string $resource = SaleItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
