<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    use SoftDeletes;

    protected $fillable = ['name','slug','code','phone','email','address','city','short_description','description','facilities','map_embed_url','image','is_active','sort_order'];
    protected function casts(): array { return ['facilities'=>'array','is_active'=>'boolean']; }
    public function doctors(): BelongsToMany { return $this->belongsToMany(Doctor::class)->withPivot(['chamber_room','appointment_phone','is_primary'])->withTimestamps(); }
    public function schedules(): HasMany { return $this->hasMany(DoctorSchedule::class); }
}
