<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','slug','designation','degrees','specialization','experience','phone','email','short_bio','biography','image','is_featured','is_active','sort_order'];
    protected function casts(): array { return ['is_featured'=>'boolean','is_active'=>'boolean']; }
    public function hospitals(): BelongsToMany { return $this->belongsToMany(Hospital::class)->withPivot(['chamber_room','appointment_phone','is_primary'])->withTimestamps(); }
    public function departments(): BelongsToMany { return $this->belongsToMany(Department::class)->withTimestamps(); }
    public function schedules(): HasMany { return $this->hasMany(DoctorSchedule::class); }
}
