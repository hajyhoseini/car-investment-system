<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Investor extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'national_code',
        'phone',
        'email',
        'address',
        'total_invested'
    ];

    protected $casts = [
        'total_invested' => 'decimal:2'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function updateTotalInvested(): void
    {
        $this->total_invested = $this->investments()->sum('amount');
        $this->save();
    }

    /**
     * ایجاد سرمایه‌گذار برای کاربر (اگه نداره)
     */
    public static function findOrCreateForUser(User $user): self
    {
        $investor = self::where('user_id', $user->id)->first();
        
        if (!$investor) {
            $investor = self::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'national_code' => '0000000000' . $user->id,
                'phone' => '09120000000',
                'email' => $user->email,
                'address' => 'تهران',
                'total_invested' => 0,
            ]);
        }
        
        return $investor;
    }
}