<?php

namespace App\Filament\Resources\PartnerBrands\Pages;

use App\Filament\Resources\PartnerBrands\PartnerBrandResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartnerBrands extends ListRecords
{
    protected static string $resource = PartnerBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
