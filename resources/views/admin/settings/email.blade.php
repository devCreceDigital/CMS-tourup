@extends('layouts.admin')

@section('breadcrumbs', 'Sistema / Configuración / Email')

@section('title', 'Configuración de Email')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.index') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">General</a>
    <a href="{{ route('admin.settings.phrases') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.phrases*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Frases</a>
    <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.social*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Redes Sociales</a>
    <a href="{{ route('admin.settings.email') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.email*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Email</a>
    <a href="{{ route('admin.settings.help') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.help') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Ayuda</a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Configuración SMTP</h2>
            <p class="text-sm text-gray-500 mt-1">Configura el servidor de correo saliente para enviar emails desde el sistema.</p>
        </div>

        @if (session('success'))
            <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
                <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.settings.email.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Host</label>
                    <input type="text" name="mail_host" value="{{ $settings['mail_host'] ?? 'smtp.gmail.com' }}" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Puerto</label>
                    <input type="text" name="mail_port" value="{{ $settings['mail_port'] ?? '587' }}" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
                    <input type="text" name="mail_username" value="{{ $settings['mail_username'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="tu@email.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="mail_password" value="{{ $settings['mail_password'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="App Password">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Encriptación</label>
                    <select name="mail_encryption" class="w-full rounded-lg border-gray-300 text-sm">
                        <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS</option>
                        <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        <option value="" {{ ($settings['mail_encryption'] ?? '') === '' ? 'selected' : '' }}>Ninguna</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email desde</label>
                    <input type="email" name="mail_from_address" value="{{ $settings['mail_from_address'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="noreply@tuagencia.com">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre desde</label>
                    <input type="text" name="mail_from_name" value="{{ $settings['mail_from_name'] ?? config('app.name') }}" class="w-full rounded-lg border-gray-300 text-sm">
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-1"></i> Guardar Configuración
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Mini Tutorial: Gmail App Password</h2>
            <p class="text-sm text-gray-500 mt-1">Sigue estos pasos para generar una contraseña de aplicación en Gmail.</p>
        </div>
        <div class="p-6 space-y-4 text-sm text-gray-700">
            <div class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">1</span>
                <p>Ve a tu <a href="https://myaccount.google.com/security" target="_blank" class="text-blue-600 underline">Cuenta de Google</a> → Seguridad.</p>
            </div>
            <div class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">2</span>
                <p>Activa la <strong>Verificación en dos pasos</strong> si aún no lo has hecho.</p>
            </div>
            <div class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">3</span>
                <p>Una vez activada, ve a "Contraseñas de aplicaciones" (busca en el buscador de la cuenta).</p>
            </div>
            <div class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">4</span>
                <p>Selecciona "Correo" y "Windows/Ordenador", luego haz clic en "Generar".</p>
            </div>
            <div class="flex gap-3">
                <span class="w-7 h-7 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold shrink-0">5</span>
                <p>Copia la contraseña de 16 caracteres que aparece y pégala en el campo "Contraseña" de este formulario.</p>
            </div>
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-yellow-800">
                <i class="fas fa-lightbulb mr-1"></i> <strong>Consejo:</strong> Usa <code>smtp.gmail.com</code> como Host, puerto <code>587</code> con TLS, y tu correo completo como Usuario.
            </div>
        </div>
    </div>
</div>
@endsection
