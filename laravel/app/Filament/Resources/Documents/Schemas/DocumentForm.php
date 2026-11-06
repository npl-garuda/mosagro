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
                    ->label('Файл')
                    ->disk('public')
                    ->directory('documents')
                    ->visibility('public')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                        'image/webp',
                        'image/gif',
                        'image/bmp',
                        'image/tiff',
                        'image/svg+xml',
                        'image/heic',
                        'image/heif',
                    ])
                    ->maxSize(20480)
                    ->required()
                    ->downloadable()
                    ->openable()
                    ->helperText('PDF или изображение (JPEG, PNG, WebP, GIF, BMP, TIFF, SVG, HEIC). До 20 МБ.'),
                Select::make('document_section_id')
                    ->label('Раздел на сайте')
                    ->helperText('Категория в /documents (МосАгро, HB-101…).')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),
                Select::make('product_id')
                    ->label('Препарат (товар)')
                    ->helperText('На странице раздела документы группируются по препарату. Укажите товар, чтобы файл попал в блок «Травостоп био», «Абига-пик» и т.п.')
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
