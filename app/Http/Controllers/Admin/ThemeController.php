<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    public function index()
    {
        $agency = Agency::first();
        $themes = [
            'ethos_earth' => [
                'name' => 'Earth',
                'description' => 'Tonos verdes y naturales. Perfecto para agencias sostenibles.',
                'icon' => 'fa-leaf',
                'gradient' => 'from-green-800 via-emerald-700 to-lime-600',
            ],
            'ethos_ocean' => [
                'name' => 'Ocean',
                'description' => 'Azules oceánicos. Ideal para destinos costeros y aventura.',
                'icon' => 'fa-water',
                'gradient' => 'from-blue-800 via-cyan-700 to-teal-500',
            ],
            'ethos_peak' => [
                'name' => 'Peak',
                'description' => 'Tonos grises y neutros. Estilo elegante y profesional.',
                'icon' => 'fa-mountain',
                'gradient' => 'from-stone-800 via-gray-700 to-slate-600',
            ],
            'ethos_sunset' => [
                'name' => 'Sunset',
                'description' => 'Tonos cálidos y vibrantes. Para agencias con energía.',
                'icon' => 'fa-sun',
                'gradient' => 'from-orange-700 via-rose-600 to-purple-700',
            ],
        ];

        return view('admin.themes.index', compact('agency', 'themes'));
    }

    public function activate(Request $request)
    {
        $validated = $request->validate([
            'theme' => 'required|in:ethos_earth,ethos_ocean,ethos_peak,ethos_sunset',
        ]);

        Agency::first()->update(['active_theme' => $validated['theme']]);

        return redirect()->route('admin.themes.index')->with('success', 'Tema activado correctamente.');
    }

    public function preview($theme)
    {
        if (!in_array($theme, ['ethos_earth', 'ethos_ocean', 'ethos_peak', 'ethos_sunset'])) {
            abort(404);
        }

        session()->put('preview_theme', $theme);

        return redirect(url('/'));
    }
}
