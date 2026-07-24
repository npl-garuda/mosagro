<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
class Product extends Model
{
    protected $fillable = ['brand_id','name','slug','short_description','composition','application','image','is_new','is_exclusive','is_active','sort'];
    protected function casts(): array
    {
        return ['is_new'=>'boolean','is_exclusive'=>'boolean','is_active'=>'boolean'];
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category')
            ->withPivot(['is_primary','sort']);
    }
    public function packagings(): HasMany
    {
        return $this->hasMany(Packaging::class)->orderBy('sort');
    }
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class)->orderBy('sort');
    }
    public function primaryCategory(): ?Category
    {
        if ($this->relationLoaded('categories')) {
            return $this->categories->firstWhere('pivot.is_primary', true)
                ?? $this->categories->first();
        }
        return $this->categories()->wherePivot('is_primary', true)->first()
            ?? $this->categories()->first();
    }
    public function catalogUrl(): ?string
    {
        $category = $this->primaryCategory();
        if (! $category) {
            return null;
        }
        return route('catalog.product', [
            'category' => $category->slug,
            'product' => $this->slug,
        ]);
    }
    public function publicImageUrl(): ?string
    {
        $path = $this->image;
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
