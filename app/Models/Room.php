<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['accommodation_id', 'room_number', 'type', 'capacity'];

    public function accommodation()
    {
        return $this->belongsTo(Accommodation::class);
    }

    public function travelers()
    {
        return $this->belongsToMany(Traveler::class, 'room_traveler');
    }
}
