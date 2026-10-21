<?php

namespace App\Filament\Resources\DocumentSections\Pages;

use App\Filament\Resources\DocumentSections\DocumentSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDocumentSections extends ListRecords
{
    protected static string $resource = DocumentSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
