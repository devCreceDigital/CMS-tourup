<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Service;
use App\Models\Setting;

class ServiceController extends Controller
{
    public function index()
    {
        if (Setting::get('services_enabled', 'true') !== 'true') {
            abort(404);
        }

        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        $services = Service::where('is_active', true)->orderBy('order')->get();

        return view("themes.{$theme}.public.services", compact('services', 'agency'));
    }
}
