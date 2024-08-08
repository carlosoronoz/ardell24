<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'department_id',
    ];

    public function Department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
