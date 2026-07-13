<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['trip_booking_id', 'pricing_installment_id', 'status', 'paid_at'];

    protected $casts = ['paid_at' => 'datetime'];

    public function tripBooking()
    {
        return $this->belongsTo(TripBooking::class);
    }

    public function pricingInstallment()
    {
        return $this->belongsTo(PricingInstallment::class);
    }
}
