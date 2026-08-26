<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConservationCard extends Model
{
    protected $fillable = ['title', 'image_url', 'photographer_credit', 'sort_order'];

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
}
