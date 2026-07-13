<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference', 'name', 'slug', 'trip_category_id', 'destination',
        'start_date', 'end_date', 'description', 'image', 'status',
        'total_spots', 'occupied_spots',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(TripCategory::class, 'trip_category_id');
    }

    public function itineraryDays()
    {
        return $this->hasMany(ItineraryDay::class);
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function bookings()
    {
        return $this->hasMany(TripBooking::class);
    }

    public function pricingGroups()
    {
        return $this->hasMany(PricingGroup::class);
    }

    public function accommodations()
    {
        return $this->hasMany(Accommodation::class);
    }
}
