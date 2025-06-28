<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

      protected $fillable = ['title', 'featured_image','gallery_images', 'description'];


       protected $casts = [
        'gallery_images' => 'array',  // cast JSON to array
    ];
}
