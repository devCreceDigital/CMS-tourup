<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripTravelerController extends Controller
{
    public function index(Trip $trip)
    {
        $trip->load('bookings');

        $bookings = $trip->bookings()->with('traveler')->paginate(20);

        $stats = [
            'total' => $trip->bookings->count(),
            'confirmed' => $trip->bookings->where('booking_status', 'confirmed')->count(),
            'pending' => $trip->bookings->where('booking_status', 'pending')->count(),
            'waitlist' => $trip->bookings->where('booking_status', 'waitlist')->count(),
        ];

        return view('admin.trips.travelers', compact('trip', 'bookings', 'stats'));
    }
}
