<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'type',
        'name',
        'bank_name',
        'account_number',
        'card_number',
        'sheba_number',
        'amount',
        'value',
        'description',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'value' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * تراکنش‌هایی که این دارایی به عنوان مبدأ (پرداخت از) است
     */
    public function outgoingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_asset_id');
    }

    /**
     * تراکنش‌هایی که این دارایی به عنوان مقصد (دریافت به) است
     */
    public function incomingTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_asset_id');
    }

    /**
     * همه تراکنش‌های مرتبط با این دارایی
     */
    public function allTransactions()
    {
        return Transaction::where('from_asset_id', $this->id)
            ->orWhere('to_asset_id', $this->id);
    }

    /**
     * محاسبه موجودی واقعی حساب با احتساب تراکنش‌ها
     * این متد فقط برای نمایش هست و amount رو تغییر نمی‌ده
     */
    public function getCurrentBalanceAttribute(): float
    {
        if ($this->type !== 'bank') {
            return $this->amount;
        }
        
        // 🚨 اینجا دیگه amount رو اضافه نکن!
        // چون amount خودش توی store آپدیت شده
        
        $totalIncome = $this->incomingTransactions()
            ->where('status', 'completed')
            ->sum('amount');
            
        $totalExpense = $this->outgoingTransactions()
            ->where('status', 'completed')
            ->sum('amount');
        
        // فقط تراکنش‌ها رو جمع بزن
        return $this->amount;
    }

    /**
     * بررسی کفایت موجودی برای پرداخت
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return $this->current_balance >= $amount;
    }

    /**
     * دریافت نوع دارایی به فارسی
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'bank' => 'حساب بانکی',
            'dollar' => 'دلار',
            'gold' => 'طلا',
            default => $this->type,
        };
    }

    /**
     * دریافت وضعیت به فارسی
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'فعال' : 'غیرفعال';
    }

    /**
     * دریافت موجودی فرمت شده
     */
    public function getFormattedAmountAttribute(): string
    {
        $unit = match($this->type) {
            'bank' => 'ریال',
            'dollar' => 'دلار',
            'gold' => 'گرم',
            default => '',
        };
        
        return number_format($this->amount) . ' ' . $unit;
    }

    /**
     * دریافت ارزش فرمت شده
     */
    public function getFormattedValueAttribute(): string
    {
        if ($this->type === 'bank') {
            return $this->formatted_amount;
        }
        
        return $this->value ? number_format($this->value) . ' ریال' : '—';
    }

    /**
     * بازگردانی موجودی به حالت واقعی بر اساس تراکنش‌ها
     * (برای مواقعی که نیاز به اصلاح دیتا داریم)
     */
    public function fixBalance(): void
    {
        $totalIncome = $this->incomingTransactions()
            ->where('status', 'completed')
            ->sum('amount');
            
        $totalExpense = $this->outgoingTransactions()
            ->where('status', 'completed')
            ->sum('amount');
        
        // موجودی اولیه رو از دیتابیس می‌گیریم نه از $this->amount
        $originalAmount = $this->getOriginal('amount');
        
        // مقدار درست = موجودی اولیه + کل دریافتی - کل پرداختی
        $correctAmount = $originalAmount + $totalIncome - $totalExpense;
        
        // اگه فرق داره، اصلاحش کن
        if ($this->amount != $correctAmount) {
            $this->amount = $correctAmount;
            $this->saveQuietly();
        }
    }

    /**
     * دریافت اطلاعات کامل بانکی
     */
    public function getBankDetailsAttribute(): array
    {
        return [
            'bank_name' => $this->bank_name,
            'account_number' => $this->account_number,
            'card_number' => $this->card_number,
            'sheba_number' => $this->sheba_number,
        ];
    }
}