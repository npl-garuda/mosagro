<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class DocumentSection extends Model {
    protected $fillable = ['name','slug','description','brand_id','sort','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function brand(): BelongsTo { return $this->belongsTo(Brand::class); }
    public function documents(): HasMany { return $this->hasMany(Document::class); }
}
