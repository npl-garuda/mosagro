<?php
namespace App\Filament\Resources\Leads\Schemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')
                    ->label('Тип заявки')
                    ->options([
                        'price_request' => 'Запрос прайса',
                        'dealer_request' => 'Заявка на дилерство',
                    ])
                    ->required()
                    ->native(false),
                TextInput::make('name')
                    ->label('Организация')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel()
                    ->required()
                    ->maxLength(50),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                TextInput::make('inn')
                    ->label('ИНН')
                    ->maxLength(20),
                TextInput::make('city')
                    ->label('Город')
                    ->maxLength(255),
                Textarea::make('comment')
                    ->label('Комментарий')
                    ->columnSpanFull(),
                TextInput::make('contact_preference')
                    ->label('Формат работы')
                    ->maxLength(255),
                Select::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новая',
                        'in_progress' => 'В работе',
                        'done' => 'Закрыта',
                    ])
                    ->required()
                    ->native(false),
            ]);
    }
}
