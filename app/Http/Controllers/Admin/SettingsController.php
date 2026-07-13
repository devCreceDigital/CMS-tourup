<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $keys = [
            'blog_enabled', 'faq_enabled', 'booking_enabled', 'services_enabled',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, 'false'));
        }

        return redirect()->route('admin.settings.index')->with('success', 'Configuración guardada correctamente.');
    }

    public function phrases()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.phrases', compact('settings'));
    }

    public function phrasesUpdate(Request $request)
    {
        $keys = [
            'hero_title', 'hero_subtitle', 'about_intro', 'services_intro',
            'footer_copyright', 'cta_text', 'cta_button',
            'trust_badge_1', 'trust_badge_2', 'trust_badge_3',
        ];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->route('admin.settings.phrases')->with('success', 'Frases actualizadas correctamente.');
    }

    public function social()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.social', compact('settings'));
    }

    public function socialUpdate(Request $request)
    {
        $keys = ['social_facebook', 'social_instagram', 'social_twitter', 'social_youtube', 'social_linkedin', 'social_tiktok', 'social_whatsapp'];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->route('admin.settings.social')->with('success', 'Redes sociales actualizadas.');
    }

    public function email()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.email', compact('settings'));
    }

    public function emailUpdate(Request $request)
    {
        $keys = ['mail_host', 'mail_port', 'mail_username', 'mail_password', 'mail_encryption', 'mail_from_address', 'mail_from_name'];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->route('admin.settings.email')->with('success', 'Configuración de email guardada.');
    }

    public function help()
    {
        return view('admin.settings.help');
    }
}
