<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id', 'traveler_id', 'bus_id', 'bus_seat_id',
        'reference', 'spots',
        'payment_status', 'document_status', 'booking_status',
        'amount_paid', 'notes', 'pricing_group_snapshot', 'extras_snapshot',
    ];

    protected $casts = [
        'pricing_group_snapshot' => 'array',
        'extras_snapshot' => 'array',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function traveler()
    {
        return $this->belongsTo(Traveler::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function seat()
    {
        return $this->belongsTo(BusSeat::class, 'bus_seat_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'trip_booking_id');
    }
}
