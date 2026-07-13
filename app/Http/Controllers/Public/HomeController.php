<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Trip;
use App\Models\TripCategory;

class HomeController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $trips = Trip::where('status', 'active')->get();
        $categories = TripCategory::where('is_active', true)->get();
        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        return view("themes.{$theme}.public.home", compact('trips', 'categories', 'agency'));
    }
}
