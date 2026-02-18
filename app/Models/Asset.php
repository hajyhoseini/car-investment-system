<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'type',
        'name',
        'amount',
        'value',
        'description'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'value' => 'decimal:2'
    ];
}