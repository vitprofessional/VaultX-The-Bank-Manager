<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffAttendance extends Model
{
    protected $guarded = [];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(StaffEmployee::class, 'staff_employee_id');
    }
}
