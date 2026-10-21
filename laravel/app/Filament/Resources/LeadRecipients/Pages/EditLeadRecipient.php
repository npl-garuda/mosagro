<?php

namespace App\Filament\Resources\LeadRecipients\Pages;

use App\Filament\Resources\LeadRecipients\LeadRecipientResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLeadRecipient extends EditRecord
{
    protected static string $resource = LeadRecipientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
