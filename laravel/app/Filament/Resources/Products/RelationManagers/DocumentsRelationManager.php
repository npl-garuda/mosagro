<?php
namespace App\Filament\Resources\Products\RelationManagers;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';
    protected static ?string $title = 'Документы';
    protected static ?string $modelLabel = 'документ';
    protected static ?string $pluralModelLabel = 'документы';
    public function form(Schema $schema): Schema
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
                    ->helperText('PDF или изображение (JPEG, PNG, WebP, GIF, BMP, TIFF, SVG, HEIC). Можно несколько документов к одному товару.'),
                Select::make('document_section_id')
                    ->label('Раздел документов')
                    ->relationship('section', 'name')
                    ->searchable()
                    ->preload(),
                TextInput::make('sort')
                    ->label('Сортировка')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->label('Название')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file_path')
                    ->label('Файл')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('section.name')
                    ->label('Раздел')
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('sort')
                    ->label('Сорт.')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Добавить документ'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort');
    }
}
