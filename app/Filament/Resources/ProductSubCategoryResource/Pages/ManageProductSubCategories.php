<?php

namespace App\Filament\Resources\ProductSubCategoryResource\Pages;

use App\Filament\Resources\ProductSubCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProductSubCategories extends ManageRecords
{
    protected static string $resource = ProductSubCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
