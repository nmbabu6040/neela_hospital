<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use SoftDeletes;
    protected $table = 'news';
    protected $fillable = ['title','slug','category','excerpt','body','featured_image','author','published_at','is_published','meta_title','meta_description'];
    protected function casts(): array { return ['published_at'=>'datetime','is_published'=>'boolean']; }
}
