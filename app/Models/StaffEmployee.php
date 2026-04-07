<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffEmployee extends Model
{
    protected $guarded = [];

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }
}
