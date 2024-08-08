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
        'category_id',
        'sub_category_id',
    ];

    protected $casts = [
        'tags' => 'array',
        'status' => 'boolean',
        'status_wa' => 'boolean',
        'images' => 'array'
    ];

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function SubCategory()
    {
        return $this->belongsTo(SubCategory::class,  'sub_category_id');
    }

    public function Rating()
    {
        return $this->hasMany(Rating::class, 'product_id', 'id');
    }

}
