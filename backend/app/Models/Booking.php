<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'user_id',
        'villa_id',
        'check_in',
        'check_out',
        'guests',
        'total_price',
        'guest_name',
        'guest_email',
        'guest_phone',
        'special_notes',
        'status',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
    ];

    public function villa()
    {
        return $this->belongsTo(Villa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
