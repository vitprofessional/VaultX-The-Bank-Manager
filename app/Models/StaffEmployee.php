<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffEmployee extends Model
{
    protected $guarded = [];

    protected $casts = [
        'can_login' => 'boolean',
        'attendance_access' => 'boolean',
        'device_bound_at' => 'datetime',
    ];

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
