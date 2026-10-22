<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Category extends Model {
    protected $fillable = ['name','slug','sort','is_active','seo_title','seo_description','seo_h1'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function seoTitle(): string
    {
        return $this->seo_title ?: ($this->name.' оптом | МосАгро');
    }
    public function seoDescription(): string
    {
        return $this->seo_description
            ?: ('Препараты категории «'.$this->name.'» оптом от МосАгро. Фасовки и документы в карточках, цены - в прайсе.');
    }
    public function seoH1(): string
    {
        return $this->seo_h1 ?: $this->name;
    }
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'product_category')
            ->withPivot(['is_primary','sort']);
    }
    public function scopeActiveOrdered(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort')->orderBy('name');
    }
}
