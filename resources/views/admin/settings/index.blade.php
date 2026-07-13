@extends('layouts.admin')

@section('breadcrumbs', 'Sistema / Configuración')

@section('title', 'Configuración General')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.index') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">General</a>
    <a href="{{ route('admin.settings.phrases') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.phrases*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Frases</a>
    <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.social*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Redes Sociales</a>
    <a href="{{ route('admin.settings.email') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.email*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Email</a>
    <a href="{{ route('admin.settings.help') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.help') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Ayuda</a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Configuración General</h2>
        <p class="text-sm text-gray-500 mt-1">Panel de control de la configuración del sitio.</p>
    </div>

    @if (session('success'))
        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="border-b border-gray-200 pb-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Visibilidad de Secciones</h3>
            <p class="text-xs text-gray-500 mb-4">Activa o desactiva secciones completas de la web pública.</p>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="hidden" name="blog_enabled" value="0">
                    <input type="checkbox" name="blog_enabled" value="1" {{ ($settings['blog_enabled'] ?? 'true') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700"><i class="fas fa-newspaper text-gray-400 mr-1"></i> Blog</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="hidden" name="faq_enabled" value="0">
                    <input type="checkbox" name="faq_enabled" value="1" {{ ($settings['faq_enabled'] ?? 'true') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700"><i class="fas fa-question-circle text-gray-400 mr-1"></i> FAQ</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="hidden" name="booking_enabled" value="0">
                    <input type="checkbox" name="booking_enabled" value="1" {{ ($settings['booking_enabled'] ?? 'true') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700"><i class="fas fa-ticket-alt text-gray-400 mr-1"></i> Reservas</span>
                </label>
                <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="hidden" name="services_enabled" value="0">
                    <input type="checkbox" name="services_enabled" value="1" {{ ($settings['services_enabled'] ?? 'true') === 'true' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    <span class="text-sm text-gray-700"><i class="fas fa-concierge-bell text-gray-400 mr-1"></i> Servicios</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.settings.phrases') }}" class="p-5 border border-gray-200 rounded-xl hover:border-blue-300 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 mb-3">
                    <i class="fas fa-font"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Frases Públicas</h3>
                <p class="text-xs text-gray-500">Personaliza los textos del sitio web</p>
            </a>
            <a href="{{ route('admin.settings.social') }}" class="p-5 border border-gray-200 rounded-xl hover:border-blue-300 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center text-pink-600 mb-3">
                    <i class="fas fa-share-alt"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Redes Sociales</h3>
                <p class="text-xs text-gray-500">Enlaces a tus perfiles sociales</p>
            </a>
            <a href="{{ route('admin.settings.email') }}" class="p-5 border border-gray-200 rounded-xl hover:border-blue-300 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600 mb-3">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Email SMTP</h3>
                <p class="text-xs text-gray-500">Configuración de correo saliente</p>
            </a>
            <a href="{{ route('admin.settings.help') }}" class="p-5 border border-gray-200 rounded-xl hover:border-blue-300 hover:shadow-sm transition-all">
                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600 mb-3">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h3 class="text-sm font-semibold text-gray-900 mb-1">Ayuda</h3>
                <p class="text-xs text-gray-500">Guía de uso del sistema</p>
            </a>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-1"></i> Guardar Visibilidad
            </button>
        </div>
    </form>
</div>
@endsection
