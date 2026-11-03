<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorSchedule extends Model
{
    protected $fillable = ['doctor_id','hospital_id','day_of_week','start_time','end_time','is_available','note'];
    protected function casts(): array { return ['day_of_week'=>'integer','is_available'=>'boolean']; }
    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class); }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class); }
}
