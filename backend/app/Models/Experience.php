<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = ['number_code', 'title', 'description', 'image_url', 'aspect_ratio', 'sort_order'];
}
