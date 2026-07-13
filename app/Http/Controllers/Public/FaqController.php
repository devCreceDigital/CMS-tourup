<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Faq;
use App\Models\Setting;

class FaqController extends Controller
{
    public function index()
    {
        if (Setting::get('faq_enabled', 'true') !== 'true') {
            abort(404);
        }

        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        $faqs = Faq::where('is_active', true)->orderBy('order')->get();

        return view("themes.{$theme}.public.faq", compact('faqs', 'agency'));
    }
}
