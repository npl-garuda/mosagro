<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LeadRecipient extends Model {
    protected $fillable = ['email','is_active','sort'];
    protected function casts(): array { return ['is_active'=>'boolean']; }
}
