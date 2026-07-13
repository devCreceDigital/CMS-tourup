<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaImage extends Model
{
    protected $fillable = ['key', 'file_path', 'alt_text', 'section'];

    protected $table = 'media_images';
}
