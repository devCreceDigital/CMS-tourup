<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;

class AboutController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $agency = Agency::firstOrFail();

        $team = [
            ['name' => 'María García', 'position' => 'Directora', 'photo' => null],
            ['name' => 'Carlos López', 'position' => 'Coordinador de Expediciones', 'photo' => null],
            ['name' => 'Ana Martínez', 'position' => 'Responsable de Sostenibilidad', 'photo' => null],
            ['name' => 'David Rodríguez', 'position' => 'Atención al Cliente', 'photo' => null],
        ];

        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        return view("themes.{$theme}.public.about", compact('agency', 'team'));
    }
}
