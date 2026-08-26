<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiningItem extends Model
{
    protected $fillable = ['title', 'description', 'image_url', 'sort_order'];
}
