<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'brand_id',        
        'image',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function Brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
}
