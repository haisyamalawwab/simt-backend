<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolYear extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'name', 'start_date', 'end_date', 'is_active'];



    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'school_year_id');
    }
}
