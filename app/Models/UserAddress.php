<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'department',
        'department_id',
        'location',
        'location_id',
        'address',
        'observation'
    ];

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
