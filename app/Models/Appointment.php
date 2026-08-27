<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'scheduled_by',
        'cancelled_date',
        'cancellation_reason',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'cancelled_date' => 'datetime',
    ];

    /**
     * Get the patient this appointment belongs to
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user who scheduled this appointment
     */
    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }
}
