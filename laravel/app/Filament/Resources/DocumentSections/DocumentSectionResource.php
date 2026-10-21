<?php

namespace App\Filament\Resources\DocumentSections;

use App\Filament\Resources\DocumentSections\Pages\CreateDocumentSection;
use App\Filament\Resources\DocumentSections\Pages\EditDocumentSection;
use App\Filament\Resources\DocumentSections\Pages\ListDocumentSections;
use App\Filament\Resources\DocumentSections\Schemas\DocumentSectionForm;
use App\Filament\Resources\DocumentSections\Tables\DocumentSectionsTable;
use App\Models\DocumentSection;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentSectionResource extends Resource
{
    protected static ?string $model = DocumentSection::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static ?string $navigationLabel = 'Разделы документов';
    protected static ?string $modelLabel = 'Раздел документов';
    protected static ?string $pluralModelLabel = 'Разделы документов';
    protected static string|\UnitEnum|null $navigationGroup = 'Контент';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DocumentSectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentSectionsTable::configure($table);
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
            'index' => ListDocumentSections::route('/'),
            'create' => CreateDocumentSection::route('/create'),
            'edit' => EditDocumentSection::route('/{record}/edit'),
        ];
    }
}
