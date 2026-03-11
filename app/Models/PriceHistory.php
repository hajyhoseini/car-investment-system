<?php
// app/Models/PriceHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceHistory extends Model
{
    protected $fillable = [
        'asset_id',
        'old_value',
        'new_value',
        'change_percent',
        'source',
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'new_value' => 'decimal:2',
        'change_percent' => 'decimal:2',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function getChangePercentAttribute()
    {
        if ($this->old_value > 0) {
            return (($this->new_value - $this->old_value) / $this->old_value) * 100;
        }
        return 0;
    }
}