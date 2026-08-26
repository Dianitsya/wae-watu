<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Villa extends Model
{
    protected $fillable = [
        'name',
        'slug',
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

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }}
