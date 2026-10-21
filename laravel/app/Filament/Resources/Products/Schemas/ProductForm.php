<?php
namespace App\Filament\Resources\Products\Schemas;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Textarea::make('short_description')
                    ->columnSpanFull(),
                Textarea::make('composition')
                    ->columnSpanFull(),
                Textarea::make('application')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('Фото')
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->image()
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->helperText('Публичное фото карточки. JPEG/PNG/WebP, до 5 МБ.'),
                Toggle::make('is_new')
                    ->required(),
                Toggle::make('is_exclusive')
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
