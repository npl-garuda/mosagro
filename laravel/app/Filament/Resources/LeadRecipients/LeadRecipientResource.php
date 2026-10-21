<?php

namespace App\Filament\Resources\LeadRecipients;

use App\Filament\Resources\LeadRecipients\Pages\CreateLeadRecipient;
use App\Filament\Resources\LeadRecipients\Pages\EditLeadRecipient;
use App\Filament\Resources\LeadRecipients\Pages\ListLeadRecipients;
use App\Filament\Resources\LeadRecipients\Schemas\LeadRecipientForm;
use App\Filament\Resources\LeadRecipients\Tables\LeadRecipientsTable;
use App\Models\LeadRecipient;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LeadRecipientResource extends Resource
{
    protected static ?string $model = LeadRecipient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static ?string $navigationLabel = 'Получатели заявок';
    protected static ?string $modelLabel = 'Получатель';
    protected static ?string $pluralModelLabel = 'Получатели заявок';
    protected static string|\UnitEnum|null $navigationGroup = 'Заявки';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'email';

    public static function form(Schema $schema): Schema
    {
        return LeadRecipientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LeadRecipientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLeadRecipients::route('/'),
            'create' => CreateLeadRecipient::route('/create'),
            'edit' => EditLeadRecipient::route('/{record}/edit'),
        ];
    }
}
