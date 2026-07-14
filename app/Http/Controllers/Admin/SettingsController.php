<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
        $request->validate([
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'about_intro' => 'nullable|string|max:1000',
            'services_intro' => 'nullable|string|max:1000',
            'footer_copyright' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:255',
            'cta_button' => 'nullable|string|max:255',
            'trust_badge_1' => 'nullable|string|max:255',
            'trust_badge_2' => 'nullable|string|max:255',
            'trust_badge_3' => 'nullable|string|max:255',
        ]);

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
        $request->validate([
            'social_facebook' => 'nullable|string|max:255',
            'social_instagram' => 'nullable|string|max:255',
            'social_twitter' => 'nullable|string|max:255',
            'social_youtube' => 'nullable|string|max:255',
            'social_linkedin' => 'nullable|string|max:255',
            'social_tiktok' => 'nullable|string|max:255',
            'social_whatsapp' => 'nullable|string|max:255',
        ]);

        $keys = ['social_facebook', 'social_instagram', 'social_twitter', 'social_youtube', 'social_linkedin', 'social_tiktok', 'social_whatsapp'];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        return redirect()->route('admin.settings.social')->with('success', 'Redes sociales actualizadas.');
    }

    public function email()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        $settings['mail_password'] = !empty($settings['mail_password']) ? 'set' : '';
        return view('admin.settings.email', compact('settings'));
    }

    public function emailUpdate(Request $request)
    {
        $request->validate([
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|string|in:tls,ssl,null',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        $keys = ['mail_host', 'mail_port', 'mail_username', 'mail_encryption', 'mail_from_address', 'mail_from_name'];

        foreach ($keys as $key) {
            Setting::set($key, $request->input($key, ''));
        }

        $password = $request->input('mail_password');
        if ($password && $password !== 'set') {
            Setting::set('mail_password', Crypt::encryptString($password));
        }

        return redirect()->route('admin.settings.email')->with('success', 'Configuración de email guardada.');
    }

    public function help()
    {
        return view('admin.settings.help');
    }
}
