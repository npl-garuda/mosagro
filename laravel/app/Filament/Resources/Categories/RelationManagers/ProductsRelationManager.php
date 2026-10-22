<?php
namespace App\Filament\Resources\Categories\RelationManagers;
use Filament\Actions\AttachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';
    protected static ?string $title = 'Товары в категории';
    protected static ?string $modelLabel = 'товар';
    protected static ?string $pluralModelLabel = 'товары';
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('brand.name')
                    ->label('Бренд')
                    ->placeholder('-'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_primary')
                    ->label('Основная')
                    ->boolean()
                    ->getStateUsing(fn ($record): bool => (bool) ($record->pivot?->is_primary)),
                IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean(),
                TextColumn::make('product_sort')
                    ->label('Sort')
                    ->numeric()
                    ->getStateUsing(fn ($record): int|string|null => $record->sort)
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderBy('products.sort', $direction)),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Добавить товар')
                    ->modalHeading('Добавить товар в категорию')
                    ->modalSubmitActionLabel('Добавить')
                    ->color('primary')
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->multiple()
                    ->recordSelectSearchColumns(['name', 'slug'])
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Товар')
                            ->helperText('Товары, которых ещё нет в этой категории. Можно выбрать несколько.'),
                        Toggle::make('is_primary')
                            ->label('Сделать основной категорией')
                            ->default(false),
                        TextInput::make('sort')
                            ->label('Sort в категории')
                            ->numeric()
                            ->default(0),
                    ]),
            ])
            ->recordActions([
                DetachAction::make()
                    ->label('Удалить')
                    ->requiresConfirmation()
                    ->modalHeading('Убрать товар из категории?')
                    ->modalDescription('Товар останется в каталоге и в других категориях. Связь только с этой категорией будет удалена.')
                    ->modalSubmitActionLabel('Удалить'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()
                        ->label('Удалить выбранные')
                        ->requiresConfirmation()
                        ->modalHeading('Убрать выбранные товары из категории?')
                        ->modalDescription('Товары останутся в каталоге. Связь только с этой категорией будет удалена.')
                        ->modalSubmitActionLabel('Удалить'),
                ]),
            ])
            ->defaultSort('name')
            ->persistSortInSession(false)
            ->emptyStateHeading('В категории пока нет товаров')
            ->emptyStateDescription('Добавьте товар кнопкой выше или назначьте категорию в карточке товара.');
    }
}
