<?php
namespace App\Filament\Resources\Leads\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class LeadsTable
{
    public static function typeLabel(?string $type): string
    {
        return match ($type) {
            'price_request' => 'Прайс',
            'dealer_request' => 'Дилер',
            default => $type ?: '-',
        };
    }
    public static function statusLabel(?string $status): string
    {
        return match ($status) {
            'new' => 'Новая',
            'in_progress' => 'В работе',
            'done' => 'Закрыта',
            'closed' => 'Закрыта',
            default => $status ?: '-',
        };
    }
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn (?string $state): string => self::typeLabel($state))
                    ->badge()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Организация')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('inn')
                    ->label('ИНН')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('city')
                    ->label('Город')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('contact_preference')
                    ->label('Формат работы')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn (?string $state): string => self::statusLabel($state))
                    ->badge()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Обновлена')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label('Просмотр'),
                EditAction::make()->label('Изменить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Удалить выбранные'),
                ]),
            ]);
    }
}
