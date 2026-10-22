<?php
namespace App\Support;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
class FloraBrand
{
    public const CATEGORY_SLUG = 'floora';
    public static function productsQuery(): Builder
    {
        return Product::query()
            ->with(['brand', 'categories', 'packagings', 'documents', 'images'])
            ->where('is_active', true)
            ->whereHas('categories', function ($q) {
                $q->where('categories.slug', self::CATEGORY_SLUG)
                    ->where('product_category.is_primary', true);
            });
    }
}