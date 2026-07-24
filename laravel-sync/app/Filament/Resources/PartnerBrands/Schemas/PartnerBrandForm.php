<?php
namespace App\Filament\Resources\PartnerBrands\Schemas;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
class PartnerBrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->label('Логотип')
                    ->disk('public')
                    ->directory('partners')
                    ->visibility('public')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048)
                    ->helperText('Публичный логотип на витрине. JPEG/PNG/WebP, до 2 МБ.'),
                TextInput::make('url')
                    ->label('Сайт бренда')
                    ->url()
                    ->maxLength(2048)
                    ->helperText('Только http/https.'),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
