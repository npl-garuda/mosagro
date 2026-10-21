<?php

namespace App\Filament\Resources\DocumentSections\Pages;

use App\Filament\Resources\DocumentSections\DocumentSectionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDocumentSection extends EditRecord
{
    protected static string $resource = DocumentSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
