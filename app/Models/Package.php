<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','slug','short_description','description','price','discount_price','included_tests','image','valid_from','valid_until','is_featured','is_active','sort_order'];
    protected function casts(): array { return ['price'=>'decimal:2','discount_price'=>'decimal:2','included_tests'=>'array','valid_from'=>'date','valid_until'=>'date','is_featured'=>'boolean','is_active'=>'boolean']; }
}
