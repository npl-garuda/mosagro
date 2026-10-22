<?php
namespace App\Filament\Resources\Products\Schemas;
use App\Models\Category;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)->schema([
                    Select::make('brand_id')
                        ->label('Бренд')
                        ->relationship('brand', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),
                    TextInput::make('name')
                        ->label('Наименование товара')
                        ->required()
                        ->maxLength(255),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('sort')
                        ->label('Sort')
                        ->required()
                        ->numeric()
                        ->default(0),
                ]),
                Textarea::make('short_description')
                    ->label('Короткое описание')
                    ->columnSpanFull(),
                Textarea::make('composition')
                    ->label('Текст на вкладке "Описание" (карточка товара)')
                    ->columnSpanFull(),
                Textarea::make('application')
                    ->label('Текст на вкладке "Применение" (карточка товара)')
                    ->columnSpanFull(),
                Select::make('categories')
                    ->label('Категории')
                    ->multiple()
                    ->relationship(
                        name: 'categories',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->where('is_active', true)
                            ->orderBy('sort')
                            ->orderBy('name'),
                    )
                    ->preload()
                    ->searchable()
                    ->required()
                    ->live()
                    ->helperText('Товар попадёт в выбранные категории каталога.')
                    ->columnSpanFull(),
                Select::make('primary_category_id')
                    ->label('Основная категория')
                    ->options(function (Get $get): array {
                        $ids = collect($get('categories') ?? [])
                            ->map(fn ($id) => (int) $id)
                            ->filter()
                            ->values()
                            ->all();
                        if ($ids === []) {
                            return [];
                        }
                        return Category::query()
                            ->whereIn('id', $ids)
                            ->orderBy('sort')
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->required()
                    ->helperText('Для URL карточки и хлебных крошек. Должна быть среди выбранных категорий.')
                    ->dehydrated(false)
                    ->columnSpanFull(),
                Grid::make(3)->schema([
                    Toggle::make('is_active')
                        ->label('Активен')
                        ->required(),
                    Toggle::make('is_new')
                        ->label('Новинка')
                        ->required()
                        ->helperText('Показывает бейдж «Новинка» на сайте.'),
                    Toggle::make('is_exclusive')
                        ->label('Эксклюзив')
                        ->required()
                        ->helperText('Показывает бейдж «Эксклюзив» на сайте.'),
                ]),
                FileUpload::make('image_paths')
                    ->label('Изображения')
                    ->multiple()
                    ->reorderable()
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->image()
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(5120)
                    ->maxFiles(12)
                    ->dehydrated(false)
                    ->extraAttributes(['class' => 'mosagro-product-images'])
                    ->helperText('Можно несколько фото. Первое помечено «Основное» (каталог и крупный слайд) - перетащите нужное на первое место. JPEG/PNG/WebP, до 5 МБ.')
                    ->columnSpanFull(),
            ]);
    }
}
