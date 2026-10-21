<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
class Document extends Model
{
    protected $fillable = ['title','file_path','external_url','document_section_id','product_id','sort'];
    public function section(): BelongsTo
    {
        return $this->belongsTo(DocumentSection::class, 'document_section_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function publicFileUrl(): ?string
    {
        $path = $this->file_path;
        if (! is_string($path) || $path === '') {
            return null;
        }
        if (str_contains($path, '..') || str_starts_with($path, '/') || str_contains($path, '\\')) {
            return null;
        }
        if (! str_starts_with($path, 'documents/')) {
            return null;
        }
        if (! Storage::disk('public')->exists($path)) {
            return null;
        }
        return Storage::disk('public')->url($path);
    }
}
