<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'name',
        'sector',
        'contact_person',
        'email',
        'phone',
        'administrator_email',
        'administrator_password',
        'city',
        'state',
        'status',
        'notes',
    ];

    protected $casts = [
        'administrator_password' => 'encrypted',
    ];

    public function licenses(): HasMany
    {
        return $this->hasMany(ClientLicense::class);
    }
}
