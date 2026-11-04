<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'doctor_id','department_id','hospital_id','patient_name','patient_phone',
        'patient_email','patient_age','patient_gender','patient_message',
        'appointment_date','appointment_time','status','admin_note'
    ];

    protected function casts(): array { return ['appointment_date' => 'date']; }

    public function doctor(): BelongsTo { return $this->belongsTo(Doctor::class)->withTrashed(); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class)->withTrashed(); }
    public function hospital(): BelongsTo { return $this->belongsTo(Hospital::class)->withTrashed(); }
}
