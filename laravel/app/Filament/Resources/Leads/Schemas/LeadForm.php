<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required()
                    ->default('price_request'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('inn'),
                TextInput::make('city'),
                Textarea::make('comment')
                    ->columnSpanFull(),
                TextInput::make('contact_preference'),
                TextInput::make('status')
                    ->required()
                    ->default('new'),
            ]);
    }
}
