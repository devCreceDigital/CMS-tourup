<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $agency = Agency::first();
        $theme = request('preview_theme') ?: session('preview_theme') ?: ($agency->active_theme ?? 'ethos_earth');

        return view("themes.{$theme}.public.contact", compact('agency'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Contact::create($validated);

        return redirect()->route('contacto')->with('success', 'Mensaje enviado correctamente. Nos pondremos en contacto contigo pronto.');
    }
}
