<?php

namespace App\Filament\Resources\LeadRecipients\Pages;

use App\Filament\Resources\LeadRecipients\LeadRecipientResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeadRecipients extends ListRecords
{
    protected static string $resource = LeadRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
