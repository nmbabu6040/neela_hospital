<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Department extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','slug','short_description','description','services','icon','image','is_featured','is_active','sort_order'];
    protected function casts(): array { return ['is_featured'=>'boolean','is_active'=>'boolean']; }
    public function doctors(): BelongsToMany { return $this->belongsToMany(Doctor::class)->withTimestamps(); }
}
