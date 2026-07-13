<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Trip;
use App\Models\TripCategory;

class TripController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $query = Trip::where('status', 'active')->with('category');

        if ($slug = request('categoria')) {
            $category = TripCategory::where('slug', $slug)->where('is_active', true)->first();
            if ($category) {
                $query->where('trip_category_id', $category->id);
            }
        }

        $trips = $query->orderBy('start_date')->paginate(12);

        $categories = TripCategory::where('is_active', true)->get();
        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        return view("themes.{$theme}.public.trips", compact('trips', 'categories', 'agency'));
    }
}
