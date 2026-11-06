<?php
namespace App\Filament\Resources\Documents\Tables;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class DocumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->label('Товар')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('title')
                    ->label('Название документа')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.brand.name')
                    ->label('Бренд')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Дата загрузки')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
                TextColumn::make('sort')
                    ->label('Сортировка')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('file_path')
                    ->label('Файл')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('section.name')
                    ->label('Раздел')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
