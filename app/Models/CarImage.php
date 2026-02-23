<?php
// app/Models/CarImage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class CarImage extends Model
{
    protected $fillable = [
        'car_id',
        'image_path',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function getUrlAttribute()
    {
        if (empty($this->image_path)) {
            return asset('images/no-image.jpg');
        }
        
        // بررسی وجود فایل
        if (!Storage::disk('public')->exists($this->image_path)) {
            return asset('images/no-image.jpg');
        }
        
        return asset('storage/' . $this->image_path);
    }
// app/Models/CarImage.php

public function getThumbnailUrlAttribute()
{
    if (empty($this->image_path)) {
        return asset('images/no-image-thumb.jpg');
    }
    
    $thumbnailPath = str_replace('cars/', 'cars/thumbnails/', $this->image_path);
    
    // اگه thumbnail وجود نداشت، خود تصویر اصلی رو برگردون
    if (!Storage::disk('public')->exists($thumbnailPath)) {
        return asset('storage/' . $this->image_path);
    }
    
    return asset('storage/' . $thumbnailPath);
}
}