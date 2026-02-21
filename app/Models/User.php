<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Accessor برای عکس پروفایل
public function getAvatarUrlAttribute()
{
    if ($this->avatar) {
        // چک کردن وجود فایل در مسیر public/uploads/avatars
        if (file_exists(public_path('uploads/avatars/' . $this->avatar))) {
            return asset('uploads/avatars/' . $this->avatar);
        }
    }
    return $this->getGravatarAttribute();
}

    // Gravatar به عنوان عکس پیش‌فرض
    public function getGravatarAttribute()
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?s=200&d=mp";
    }
    

public function investor()
{
    return $this->hasOne(Investor::class);
}

public function investments()
{
    return $this->hasManyThrough(Investment::class, Investor::class);
}

public function cars()
{
    return $this->hasManyThrough(Car::class, Investment::class, 'investor_id', 'id', 'id', 'car_id');
}
// اضافه کردن این متد به کلاس User
public function isInvestor(): bool
{
    return $this->investor !== null;
}

public function getInvestorProfile()
{
    if (!$this->isInvestor()) {
        // اگر سرمایه‌گذار نبود، می‌تونیم به صورت خودکار بسازیمش
        return $this->createInvestorProfile();
    }
    return $this->investor;
}

public function createInvestorProfile()
{
    return Investor::firstOrCreate(
        ['email' => $this->email],
        [
            'user_id' => $this->id,
            'full_name' => $this->name,
            'national_code' => '0000000000',
            'phone' => '09120000000',
            'address' => 'تهران',
            'total_invested' => 0,
        ]
    );
}
}
