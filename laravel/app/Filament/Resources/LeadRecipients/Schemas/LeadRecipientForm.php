<?php

namespace App\Filament\Resources\LeadRecipients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LeadRecipientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
