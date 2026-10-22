<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
class ProductImage extends Model
{
    protected $fillable = ['product_id', 'path', 'is_primary', 'sort'];
    protected function casts(): array
    {
        return ['is_primary' => 'boolean'];
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function publicUrl(): ?string
    {
        $path = $this->path;
        if (! is_string($path) || $path === '') {
            return null;
        }
        if (str_contains($path, '..') || str_starts_with($path, '/') || str_contains($path, '\\')) {
            return null;
        }
        if (! str_starts_with($path, 'products/')) {
            return null;
        }
        if (! Storage::disk('public')->exists($path)) {
            return null;
        }
        return Storage::disk('public')->url($path);
    }
}
