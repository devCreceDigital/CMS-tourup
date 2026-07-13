<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Traveler extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'last_name', 'dni', 'birth_date', 'sex',
        'email', 'phone', 'address', 'notes',
    ];

    public function bookings()
    {
        return $this->hasMany(TripBooking::class);
    }

    public function documents()
    {
        return $this->hasMany(TravelerDocument::class);
    }
}
