<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Category extends Model {
    protected $fillable = ['name','slug','sort','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function products(): BelongsToMany {
        return $this->belongsToMany(Product::class, 'product_category')
            ->withPivot(['is_primary','sort']);
    }
}
