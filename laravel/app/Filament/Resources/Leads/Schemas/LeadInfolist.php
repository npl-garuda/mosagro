<?php
namespace App\Filament\Resources\Leads\Schemas;
use App\Filament\Resources\Leads\Tables\LeadsTable;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
class LeadInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->label('Тип заявки')
                    ->formatStateUsing(fn (?string $state): string => LeadsTable::typeLabel($state)),
                TextEntry::make('name')
                    ->label('Организация'),
                TextEntry::make('phone')
                    ->label('Телефон'),
                TextEntry::make('email')
                    ->label('Email')
                    ->placeholder('-'),
                TextEntry::make('inn')
                    ->label('ИНН')
                    ->placeholder('-'),
                TextEntry::make('city')
                    ->label('Город')
                    ->placeholder('-'),
                TextEntry::make('comment')
                    ->label('Комментарий')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('contact_preference')
                    ->label('Формат работы')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn (?string $state): string => LeadsTable::statusLabel($state)),
                TextEntry::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Обновлена')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('-'),
            ]);
    }
}
