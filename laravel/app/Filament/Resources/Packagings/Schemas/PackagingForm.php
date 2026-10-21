<?php

namespace App\Filament\Resources\Packagings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PackagingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
