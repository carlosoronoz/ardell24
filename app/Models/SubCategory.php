<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'category_id',        
        'image',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
