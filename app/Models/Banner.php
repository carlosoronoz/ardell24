<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'type',
        'title',
        'subtitle',
        'url',
        'image',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
