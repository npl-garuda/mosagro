<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Support\CategoryIcons;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Название')
                    ->required(),
                TextInput::make('slug')
                    ->label('ЧПУ (slug)')
                    ->required(),
                TextInput::make('sort')
                    ->label('Сортировка')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Активна')
                    ->required(),
                Select::make('icon')
                    ->label('Иконка на главной')
                    ->options(CategoryIcons::selectOptions())
                    ->allowHtml()
                    ->native(false)
                    ->searchable()
                    ->required()
                    ->default(CategoryIcons::DEFAULT)
                    ->helperText('Отображается в блоке «Категории» на главной.'),
                TextInput::make('seo_title')
                    ->label('SEO title')
                    ->maxLength(255)
                    ->helperText('Пусто = «{название} оптом | МосАгро». Пример: «Стимуляторы роста оптом | МосАгро».'),
                Textarea::make('seo_description')
                    ->label('SEO description')
                    ->rows(2)
                    ->maxLength(512),
                TextInput::make('seo_h1')
                    ->label('SEO H1')
                    ->maxLength(255)
                    ->helperText('Пусто = название. Для Floora лучше «HB-101 (Floora)» и т.п.'),
            ]);
    }
}
