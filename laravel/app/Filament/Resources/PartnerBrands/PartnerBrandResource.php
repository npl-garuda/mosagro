<?php

namespace App\Filament\Resources\PartnerBrands;

use App\Filament\Resources\PartnerBrands\Pages\CreatePartnerBrand;
use App\Filament\Resources\PartnerBrands\Pages\EditPartnerBrand;
use App\Filament\Resources\PartnerBrands\Pages\ListPartnerBrands;
use App\Filament\Resources\PartnerBrands\Schemas\PartnerBrandForm;
use App\Filament\Resources\PartnerBrands\Tables\PartnerBrandsTable;
use App\Models\PartnerBrand;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartnerBrandResource extends Resource
{
    protected static ?string $model = PartnerBrand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static ?string $navigationLabel = 'Партнёры';
    protected static ?string $modelLabel = 'Партнёр';
    protected static ?string $pluralModelLabel = 'Партнёры';
    protected static string|\UnitEnum|null $navigationGroup = 'Контент';
    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return PartnerBrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartnerBrandsTable::configure($table);
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
            'index' => ListPartnerBrands::route('/'),
            'create' => CreatePartnerBrand::route('/create'),
            'edit' => EditPartnerBrand::route('/{record}/edit'),
        ];
    }
}
