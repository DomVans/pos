<?php

namespace App\Filament\Resources\SaleItemsResource\Pages;

use App\Filament\Resources\SaleItemsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSaleItems extends EditRecord
{
    protected static string $resource = SaleItemsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
