<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
        'capacity',
        'price_per_night',
        'description',
        'features',
        'image_url',
    ];

    protected $casts = [
        'features' => 'array',
        'price_per_night' => 'decimal:2',
    ];

    public function getImageUrlAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://') || str_starts_with($value, 'data:')) {
            return $value;
        }
        return url($value);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
