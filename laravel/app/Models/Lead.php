<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Lead extends Model {
    protected $fillable = ['type','name','phone','email','inn','city','comment','contact_preference','status'];
}
