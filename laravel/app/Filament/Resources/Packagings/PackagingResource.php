<?php

namespace App\Filament\Resources\Packagings;

use App\Filament\Resources\Packagings\Pages\CreatePackaging;
use App\Filament\Resources\Packagings\Pages\EditPackaging;
use App\Filament\Resources\Packagings\Pages\ListPackagings;
use App\Filament\Resources\Packagings\Schemas\PackagingForm;
use App\Filament\Resources\Packagings\Tables\PackagingsTable;
use App\Models\Packaging;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PackagingResource extends Resource
{
    protected static ?string $model = Packaging::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Фасовки';
    protected static ?string $modelLabel = 'Фасовка';
    protected static ?string $pluralModelLabel = 'Фасовки';
    protected static string|\UnitEnum|null $navigationGroup = 'Каталог';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return PackagingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PackagingsTable::configure($table);
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
            'index' => ListPackagings::route('/'),
            'create' => CreatePackaging::route('/create'),
            'edit' => EditPackaging::route('/{record}/edit'),
        ];
    }
}
