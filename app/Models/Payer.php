<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payer extends Model
{
    protected $fillable = [
        'type_passport',
        'passport',
        'name',
        'surname',
        'email',
        'phone',
        'department',
        'location',
        'address',
        'sale_id',
        'user_id'
    ];

    public function Sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
