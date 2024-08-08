<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $fillable = [
        'total_shipping',
        'type_shipping',
        'url_shipping',
        'courier',
        'tracker',
        'status_shipping',
        'sale_id'
    ];

    protected $casts = [
        'status_shipping' => 'boolean',
    ];

    public function Sale()
    {
        return $this->hasOne(Sale::class,'id', 'sale_id');
    }
}
