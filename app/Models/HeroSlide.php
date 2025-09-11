<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model {
    protected $table = 'hero_slides';
    protected $fillable = [
        'title',
        'description',
        'image',
        'order'
    ];
    protected $casts = [
        'order' => 'integer',
    ];
}
