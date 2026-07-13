<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Setting;
use App\Models\Trip;

class TripDetailController extends Controller
{
    public function show(string $slug): \Illuminate\View\View
    {
        $trip = Trip::where('slug', $slug)
            ->with([
                'category',
                'itineraryDays' => function ($q) {
                    $q->with('activities')->where('is_published', true);
                },
                'buses.seats',
                'pricingGroups' => function ($q) {
                    $q->with('installments')->where('is_active', true);
                },
                'accommodations.rooms',
                'bookings',
            ])
            ->firstOrFail();

        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        $bookingEnabled = Setting::get('booking_enabled', 'true') === 'true';

        $availableSpots = $trip->total_spots - $trip->occupied_spots;

        $phrases = [];
        $phraseKeys = ['hero_title', 'hero_subtitle', 'why_travel_title', 'why_travel_1', 'why_travel_2', 'why_travel_3', 'why_travel_4'];
        foreach ($phraseKeys as $key) {
            $phrases[$key] = Setting::get('phrase_' . $key, '');
        }

        $cheapestGroup = $trip->pricingGroups->sortBy(function ($group) {
            return $group->installments->sum('amount');
        })->first();

        $priceFrom = $cheapestGroup ? $cheapestGroup->installments->sum('amount') : 0;

        $social = [];
        foreach (['facebook', 'instagram', 'twitter', 'youtube', 'linkedin', 'whatsapp'] as $net) {
            $social[$net] = Setting::get($net, '');
        }

        return view("themes.{$theme}.public.trip-detail", compact(
            'trip', 'agency', 'bookingEnabled', 'availableSpots', 'phrases', 'priceFrom', 'social'
        ));
    }
}
