<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'type_document',
    'num_document',
    'notes',
    'type_operation',
    'num_transaction',
    'preference_id',
    'preference_url',
    'payment_id',
    'date_document',
    'total_amount',
    'state',
    'status',
    'notification'
  ];

  protected $casts = [
    'status' => 'boolean',
    'notification' => 'array'
  ];

  public function Payer()
  {
    return $this->hasOne(Payer::class, 'sale_id', 'id');
  }

  public function DetailSale()
  {
    return $this->hasMany(DetailSale::class, 'sale_id', 'id');
  }
  
}
