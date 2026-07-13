@extends('layouts.admin')

@section('breadcrumbs', 'Sistema / Configuración / Redes Sociales')

@section('title', 'Redes Sociales')

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
        <h2 class="text-lg font-semibold text-gray-900">Redes Sociales</h2>
        <p class="text-sm text-gray-500 mt-1">Configura los enlaces a tus redes sociales. Se mostrarán en el footer del sitio web.</p>
    </div>

    @if (session('success'))
        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.social.update') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-facebook text-blue-600 mr-1"></i> Facebook</label>
                <input type="url" name="social_facebook" value="{{ $settings['social_facebook'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://facebook.com/tuagencia">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-instagram text-pink-600 mr-1"></i> Instagram</label>
                <input type="url" name="social_instagram" value="{{ $settings['social_instagram'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://instagram.com/tuagencia">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-twitter text-blue-400 mr-1"></i> Twitter / X</label>
                <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://twitter.com/tuagencia">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-youtube text-red-600 mr-1"></i> YouTube</label>
                <input type="url" name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://youtube.com/@tuagencia">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-linkedin text-blue-700 mr-1"></i> LinkedIn</label>
                <input type="url" name="social_linkedin" value="{{ $settings['social_linkedin'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://linkedin.com/company/tuagencia">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-tiktok text-gray-900 mr-1"></i> TikTok</label>
                <input type="url" name="social_tiktok" value="{{ $settings['social_tiktok'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://tiktok.com/@tuagencia">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><i class="fab fa-whatsapp text-green-500 mr-1"></i> WhatsApp</label>
                <input type="url" name="social_whatsapp" value="{{ $settings['social_whatsapp'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="https://wa.me/51999000000">
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-1"></i> Guardar Redes Sociales
            </button>
        </div>
    </form>
</div>
@endsection
