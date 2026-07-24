<?php
namespace App\Filament\Resources\Documents\Schemas;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
class DocumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Название')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('file_path')
                    ->label('Файл (PDF)')
                    ->disk('public')
                    ->directory('documents')
                    ->visibility('public')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(20480)
                    ->required()
                    ->downloadable()
                    ->openable()
                    ->helperText('Публичный PDF для скачивания на сайте. Только PDF, до 20 МБ.'),
                Select::make('document_section_id')
                    ->label('Раздел')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('product_id')
                    ->label('Товар')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('sort')
                    ->label('Сортировка')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
