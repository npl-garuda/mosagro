<?php

namespace App\Filament\Resources\PartnerBrands\Pages;

use App\Filament\Resources\PartnerBrands\PartnerBrandResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartnerBrand extends EditRecord
{
    protected static string $resource = PartnerBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
