<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSale extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_amount',
        'discount',
    ];

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
