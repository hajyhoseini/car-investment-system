<?php
// app/Models/Car.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Car extends BaseModel
{
    protected $jalaliDates = [
        'purchase_date',
        'inquiry_date'  // تاریخ استعلام
    ];
    
 protected $fillable = [
    // صفحه 1
    'title',
    'brand',
    'model',
    'year',
    'kilometers',
    'fuel_type',        // ⭐ این رو اضافه کن
    'transmission',     // ⭐ این رو اضافه کن
    'color',
    'inquiry_date',
    'showroom_name',
    
    // صفحه 2
    'phone_number',
    'document_status',
    'storage_location',
    'holding_price',
    'purchase_priority',
    
    // صفحه 3 (اختیاری)
    'purchase_price',
    'purchase_date',
    'body_condition',
    'technical_condition',
    'owner_type',
    'market_price',
    'min_price',
    'customer_type',
    'description',
    
    // صفحه 4 (اختیاری)
    'listing_url',
    'status',
];

    protected $casts = [
        'purchase_date' => 'datetime',
        'inquiry_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'market_price' => 'decimal:2',
        'holding_price' => 'decimal:2',
        'min_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    // ============ ارتباطات ============
    
    public function investments(): HasMany
    {
        return $this->hasMany(Investment::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(CarSale::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CarImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(CarImage::class)->where('is_primary', true);
    }

    // ============ اکسسورهای تصاویر ============
    
    public function getPrimaryImageUrlAttribute()
    {
        $primary = $this->primaryImage;
        if ($primary) {
            return $primary->url;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->url;
        }
        
        return asset('images/no-image.jpg');
    }

    public function getThumbnailUrlAttribute()
    {
        $primary = $this->primaryImage;
        if ($primary) {
            return $primary->thumbnail_url;
        }
        
        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->thumbnail_url;
        }
        
        return asset('images/no-image-thumb.jpg');
    }

    // ============ متدهای سرمایه‌گذاری ============
    
    public function getTotalInvestedAttribute(): float
    {
        return $this->investments()->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return $this->purchase_price - $this->total_invested;
    }
    
    public function isFullyFunded(): bool
    {
        return $this->total_invested >= $this->purchase_price;
    }

    public function getRemainingForInvestmentAttribute(): float
    {
        $remaining = $this->purchase_price - $this->total_invested;
        return max($remaining, 0);
    }

    public function getFundedPercentageAttribute(): float
    {
        if ($this->purchase_price <= 0) return 0;
        return ($this->total_invested / $this->purchase_price) * 100;
    }

    // ============ اکسسورهای قیمتی ============
    
    /**
     * سود احتمالی (تفاوت قیمت بازار با قیمت خرید)
     */
    public function getPotentialProfitAttribute(): float
    {
        return $this->market_price - $this->purchase_price;
    }
    
    /**
     * درصد سود احتمالی
     */
    public function getPotentialProfitPercentageAttribute(): float
    {
        if ($this->purchase_price <= 0) return 0;
        return ($this->potential_profit / $this->purchase_price) * 100;
    }
    
    /**
     * اختلاف قیمت هلدینگ با قیمت بازار
     */
    public function getHoldingVsMarketDiffAttribute(): float
    {
        return $this->holding_price - $this->market_price;
    }

    // ============ اکسسورهای وضعیت ============
    
    /**
     * دریافت وضعیت بدنه به صورت فارسی
     */
    public function getBodyConditionPersianAttribute(): string
    {
        $conditions = [
            'colorless' => 'بی‌رنگ',
            'two_tone' => 'دورنگ',
            'fender_painted' => 'گلگیررنگ',
            'full_painted' => 'سرتاپا رنگ',
            'minor_scratch' => 'خط و خش جزئی',
            'replaced' => 'تعویضی',
        ];
        
        return $conditions[$this->body_condition] ?? $this->body_condition ?? 'نامشخص';
    }
    
    /**
     * دریافت وضعیت فنی به صورت فارسی
     */
    public function getTechnicalConditionPersianAttribute(): string
    {
        $conditions = [
            'healthy' => 'سالم',
            'needs_service' => 'نیاز به سرویس',
            'has_noise' => 'صدا دارد',
            'major_repair' => 'تعمیر اساسی شده',
        ];
        
        return $conditions[$this->technical_condition] ?? $this->technical_condition ?? 'نامشخص';
    }
    
    /**
     * دریافت وضعیت سند به صورت فارسی
     */
    public function getDocumentStatusPersianAttribute(): string
    {
        $statuses = [
            'seller_name' => 'به نام فروشنده',
            'other_person' => 'به نام شخص دیگر',
            'power_of_attorney' => 'وکالتی',
            'in_mortgage' => 'در رهن',
        ];
        
        return $statuses[$this->document_status] ?? $this->document_status ?? 'نامشخص';
    }
    
    /**
     * دریافت محل نگهداری خودرو به صورت فارسی
     */
    public function getStorageLocationPersianAttribute(): string
    {
        $locations = [
            'exhibition' => 'نمایشگاه',
            'parking' => 'پارکینگ',
            'seller_home' => 'منزل فروشنده',
            'city' => 'شهرستان',
        ];
        
        return $locations[$this->storage_location] ?? $this->storage_location ?? 'نامشخص';
    }
    
    /**
     * دریافت نوع مالک به صورت فارسی
     */
    public function getOwnerTypePersianAttribute(): string
    {
        $types = [
            'personal' => 'شخصی',
            'exhibition' => 'نمایشگاه',
            'consignment' => 'امانی',
        ];
        
        return $types[$this->owner_type] ?? $this->owner_type ?? 'نامشخص';
    }
    
    /**
     * دریافت نوع مشتری به صورت فارسی
     */
    public function getCustomerTypePersianAttribute(): string
    {
        $types = [
            'consumer' => 'مصرف‌کننده',
            'business' => 'کاسب',
            'both' => 'هردو',
        ];
        
        return $types[$this->customer_type] ?? $this->customer_type ?? 'نامشخص';
    }
    
    /**
     * دریافت اولویت خرید به صورت فارسی
     */
    public function getPurchasePriorityPersianAttribute(): string
    {
        $priorities = [
            'high' => 'زیاد',
            'medium' => 'متوسط',
            'low' => 'کم',
        ];
        
        return $priorities[$this->purchase_priority] ?? $this->purchase_priority ?? 'متوسط';
    }
    
    /**
     * دریافت اولویت خرید با رنگ Bootstrap
     */
    public function getPurchasePriorityBadgeAttribute(): string
    {
        $badges = [
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
        ];
        
        $color = $badges[$this->purchase_priority] ?? 'secondary';
        $text = $this->purchase_priority_persian;
        
        return "<span class='badge bg-{$color}'>{$text}</span>";
    }

    // ============ اکسسورهای تاریخ شمسی ============
    
    public function getCreatedAtJalaliAttribute()
    {
        return jalali_datetime($this->created_at);
    }

    public function getUpdatedAtJalaliAttribute()
    {
        return jalali_datetime($this->updated_at);
    }
    
    public function getInquiryDateJalaliAttribute()
    {
        return jalali_date($this->inquiry_date);
    }
    
    public function getPurchaseDateJalaliAttribute()
    {
        return jalali_date($this->purchase_date);
    }

    // ============ اسکوپ‌ها ============
    
    /**
     * اسکوپ برای خودروهایی که نیاز به سرمایه‌گذاری دارند
     */
    public function scopeNeedsInvestment($query)
    {
        return $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM investments WHERE car_id = cars.id) < purchase_price');
    }
    
    /**
     * اسکوپ بر اساس اولویت خرید
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('purchase_priority', $priority);
    }
    
    /**
     * اسکوپ بر اساس وضعیت بدنه
     */
    public function scopeBodyCondition($query, $condition)
    {
        return $query->where('body_condition', $condition);
    }
    
    /**
     * اسکوپ بر اساس وضعیت فنی
     */
    public function scopeTechnicalCondition($query, $condition)
    {
        return $query->where('technical_condition', $condition);
    }
    
    /**
     * اسکوپ بر اساس وضعیت سند
     */
    public function scopeDocumentStatus($query, $status)
    {
        return $query->where('document_status', $status);
    }
    
    /**
     * اسکوپ بر اساس محل نگهداری
     */
    public function scopeStorageLocation($query, $location)
    {
        return $query->where('storage_location', $location);
    }
    
    /**
     * اسکوپ بر اساس نوع مالک
     */
    public function scopeOwnerType($query, $type)
    {
        return $query->where('owner_type', $type);
    }
    
    /**
     * اسکوپ بر اساس نوع مشتری
     */
    public function scopeCustomerType($query, $type)
    {
        return $query->where('customer_type', $type);
    }
    
    /**
     * اسکوپ بر اساس محدوده قیمت بازار
     */
    public function scopeMarketPriceBetween($query, $min, $max)
    {
        return $query->whereBetween('market_price', [$min, $max]);
    }
}