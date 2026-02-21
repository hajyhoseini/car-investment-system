<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    protected $fillable = [
        'full_name',
        'national_code',
        'phone',
        'email',
        'address',
        'total_invested'
    ];

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function updateTotalInvested(): void
    {
        $this->total_invested = $this->investments()->sum('amount');
        $this->save();
    }
    

public function user()
{
    return $this->belongsTo(User::class);
}


}