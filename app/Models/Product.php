<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'name',
        'slug',
        'stock',
        'essential_amount',
        'professional_amount',
        'discount',
        'condition',
        'status',
        'status_wa',
        'detail',
        'indication',
        'tags',
        'sales',
        'images',
        'brand_id',
        'gender_id',
    ];

    protected $casts = [
        'tags' => 'array',
        'status' => 'boolean',
        'status_wa' => 'boolean',
        'images' => 'array'
    ];

    public function Gender()
    {
        return $this->belongsTo(Gender::class, 'gender_id');
    }

    public function Brand()
    {
        return $this->belongsTo(Brand::class,  'brand_id');
    }

    public function Rating()
    {
        return $this->hasMany(Rating::class, 'product_id', 'id');
    }

}
