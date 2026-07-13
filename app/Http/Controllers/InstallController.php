<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class InstallController extends Controller
{
    protected $availableThemes = [
        'ethos_earth',
        'ethos_ocean',
        'ethos_peak',
        'ethos_sunset',
    ];

    public function __construct()
    {
        try {
            if (Agency::where('is_installed', true)->exists()) {
                redirect('/panel-agencia')->send();
            }
        } catch (\Exception $e) {
        }
    }

    public function index()
    {
        return redirect()->route('install.step1');
    }

    public function step1()
    {
        return view('installation.step1');
    }

    public function postStep1(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|string',
            'db_name' => 'required|string',
            'db_user' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        try {
            config([
                'database.connections.mysql.host' => $request->db_host,
                'database.connections.mysql.port' => $request->db_port,
                'database.connections.mysql.database' => $request->db_name,
                'database.connections.mysql.username' => $request->db_user,
                'database.connections.mysql.password' => $request->db_password ?? '',
            ]);

            DB::purge('mysql');
            DB::connection('mysql')->getPdo();

            Artisan::call('migrate', ['--force' => true]);

            session([
                'install.db_host' => $request->db_host,
                'install.db_port' => $request->db_port,
                'install.db_name' => $request->db_name,
                'install.db_user' => $request->db_user,
                'install.db_password' => $request->db_password,
            ]);

            return redirect()->route('install.step2');
        } catch (\Exception $e) {
            return back()->withErrors(['db_connection' => 'Database connection failed: ' . $e->getMessage()]);
        }
    }

    public function step2()
    {
        return view('installation.step2');
    }

    public function postStep2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        session([
            'install.admin_name' => $request->name,
            'install.admin_email' => $request->email,
            'install.admin_phone' => $request->phone,
            'install.admin_password' => $request->password,
        ]);

        return redirect()->route('install.step3');
    }

    public function step3()
    {
        return view('installation.step3');
    }

    public function postStep3(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'welcome_phrase' => 'nullable|string|max:500',
            'about' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        session([
            'install.agency_name' => $request->name,
            'install.welcome_phrase' => $request->welcome_phrase,
            'install.about' => $request->about,
            'install.logo' => $logoPath,
        ]);

        return redirect()->route('install.step4');
    }

    public function step4()
    {
        return view('installation.step4', [
            'themes' => $this->availableThemes,
        ]);
    }

    public function postStep4(Request $request)
    {
        $request->validate([
            'theme' => 'required|string|in:' . implode(',', $this->availableThemes),
        ]);

        $this->applyDatabaseConfig();

        Agency::create([
            'name' => session('install.agency_name'),
            'admin_name' => session('install.admin_name'),
            'email' => session('install.admin_email'),
            'phone' => session('install.admin_phone'),
            'welcome_phrase' => session('install.welcome_phrase'),
            'about' => session('install.about'),
            'logo' => session('install.logo'),
            'active_theme' => $request->theme,
            'is_installed' => true,
        ]);

        User::create([
            'name' => session('install.admin_name'),
            'email' => session('install.admin_email'),
            'phone' => session('install.admin_phone'),
            'password' => Hash::make(session('install.admin_password')),
            'is_admin' => true,
        ]);

        session()->forget([
            'install.db_host',
            'install.db_port',
            'install.db_name',
            'install.db_user',
            'install.db_password',
            'install.admin_name',
            'install.admin_email',
            'install.admin_phone',
            'install.admin_password',
            'install.agency_name',
            'install.welcome_phrase',
            'install.about',
            'install.logo',
        ]);

        return redirect('/panel-agencia');
    }

    protected function applyDatabaseConfig()
    {
        if (session()->has('install.db_host')) {
            config([
                'database.connections.mysql.host' => session('install.db_host'),
                'database.connections.mysql.port' => session('install.db_port'),
                'database.connections.mysql.database' => session('install.db_name'),
                'database.connections.mysql.username' => session('install.db_user'),
                'database.connections.mysql.password' => session('install.db_password') ?? '',
            ]);

            DB::purge('mysql');
        }
    }
}
