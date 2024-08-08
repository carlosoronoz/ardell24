<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'business_name',
        'email',
        'phone',
        'address',
        'logo',
        'instagram',
        'notes',
        'credential',
        'integrator_id',
        'access_token_whatsapp',
        'mobile_id',
        'business_id',
        'wa_business_id',
        'catalog_id',
        'graph_version',
        'production_mode'
    ];

    protected $casts = [
        'production_mode' => 'boolean',
    ];
}
