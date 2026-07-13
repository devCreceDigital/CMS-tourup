@extends('layouts.admin')

@section('breadcrumbs', 'Sistema / Configuración / Frases')

@section('title', 'Frases Públicas')

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
        <h2 class="text-lg font-semibold text-gray-900">Frases Públicas</h2>
        <p class="text-sm text-gray-500 mt-1">Personaliza los textos que aparecen en las páginas públicas de tu sitio web.</p>
    </div>

    @if (session('success'))
        <div class="mx-6 mt-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.phrases.update') }}" method="POST" class="p-6 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título del Hero</label>
                <input type="text" name="hero_title" value="{{ $settings['hero_title'] ?? 'Descubre el Mundo' }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Subtítulo del Hero</label>
                <input type="text" name="hero_subtitle" value="{{ $settings['hero_subtitle'] ?? '' }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto Intro Sobre Nosotros</label>
                <textarea name="about_intro" rows="3" class="w-full rounded-lg border-gray-300 text-sm">{{ $settings['about_intro'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto Intro Servicios</label>
                <textarea name="services_intro" rows="3" class="w-full rounded-lg border-gray-300 text-sm">{{ $settings['services_intro'] ?? '' }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Copyright Footer</label>
                <input type="text" name="footer_copyright" value="{{ $settings['footer_copyright'] ?? '© ' . date('Y') . ' Todos los derechos reservados.' }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto CTA</label>
                <input type="text" name="cta_text" value="{{ $settings['cta_text'] ?? '¿Listo para tu próxima aventura?' }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Botón CTA</label>
                <input type="text" name="cta_button" value="{{ $settings['cta_button'] ?? 'Explorar Viajes' }}" class="w-full rounded-lg border-gray-300 text-sm">
            </div>
        </div>

        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Barra de Confianza (3 frases)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text" name="trust_badge_1" value="{{ $settings['trust_badge_1'] ?? 'Gestión 100% online' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ej: Gestión 100% online">
                <input type="text" name="trust_badge_2" value="{{ $settings['trust_badge_2'] ?? 'Pago fraccionado' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ej: Pago fraccionado">
                <input type="text" name="trust_badge_3" value="{{ $settings['trust_badge_3'] ?? 'Soporte permanente' }}" class="w-full rounded-lg border-gray-300 text-sm" placeholder="Ej: Soporte permanente">
            </div>
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-1"></i> Guardar Frases
            </button>
        </div>
    </form>
</div>
@endsection
