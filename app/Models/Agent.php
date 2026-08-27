<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'type',
        'email',
        'phone',
        'organization',
        'commission_rate',
        'status',
        'notes',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
    ];
}
