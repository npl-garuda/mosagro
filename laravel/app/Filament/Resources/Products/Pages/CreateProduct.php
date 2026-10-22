<?php
namespace App\Filament\Resources\Products\Pages;
use App\Filament\Resources\Products\Concerns\SyncsProductCategories;
use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;
class CreateProduct extends CreateRecord
{
    use SyncsProductCategories;
    protected static string $resource = ProductResource::class;
}
