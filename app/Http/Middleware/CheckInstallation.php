<?php

namespace App\Http\Middleware;

use App\Models\Agency;
use Closure;
use Illuminate\Http\Request;

class CheckInstallation
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('install*')) {
            return $next($request);
        }

        try {
            $installed = Agency::where('is_installed', true)->exists();
        } catch (\Exception $e) {
            return redirect('/install');
        }

        if (!$installed) {
            return redirect('/install');
        }

        return $next($request);
    }
}
