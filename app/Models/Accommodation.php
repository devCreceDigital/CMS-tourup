<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'name', 'stars', 'location', 'image', 'link'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
