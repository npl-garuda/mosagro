<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
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
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort')->orderBy('id');
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
    public function catalogUrl(): string
    {
        $category = $this->primaryCategory();
        return route('catalog.product', [
            'category' => $category?->slug ?? 'all',
            'product' => $this->slug,
        ]);
    }
    public function primaryImage(): ?ProductImage
    {
        if ($this->relationLoaded('images')) {
            return $this->images->firstWhere('is_primary', true)
                ?? $this->images->sortBy('sort')->first();
        }
        return $this->images()->where('is_primary', true)->first()
            ?? $this->images()->orderBy('sort')->first();
    }
    public function galleryImageUrls(): Collection
    {
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            return $this->images
                ->map(fn (ProductImage $image) => $image->publicUrl())
                ->filter()
                ->values();
        }
        $url = $this->publicImageUrl();
        return $url ? collect([$url]) : collect();
    }
    public function publicImageUrl(): ?string
    {
        $primary = $this->primaryImage();
        if ($primary) {
            return $primary->publicUrl();
        }
        return $this->urlForPath($this->image);
    }
    public function urlForPath(?string $path): ?string
    {
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
