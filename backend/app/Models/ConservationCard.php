<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConservationCard extends Model
{
    protected $fillable = ['title', 'image_url', 'photographer_credit', 'sort_order'];
}
