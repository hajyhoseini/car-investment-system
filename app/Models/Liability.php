<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Liability extends Model
{
    protected $fillable = [
        'type',
        'creditor_name',
        'amount',
        'remaining_amount',
        'due_date',
        'status',
        'description'
    ];

    protected $casts = [
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2'
    ];

    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->status !== 'paid';
    }
}