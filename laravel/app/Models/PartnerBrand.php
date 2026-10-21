<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PartnerBrand extends Model {
    protected $fillable = ['name','logo','url','sort','is_active'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
}
