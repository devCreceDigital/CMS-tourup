@php
    $themePalettes = [
        'ethos_earth'  => ['primary' => '#1B3022', 'accent' => '#C28E60', 'surface' => '#FCF9F5', 'font' => 'Playfair Display + Inter'],
        'ethos_ocean'  => ['primary' => '#0A4A7A', 'accent' => '#2EC4B6', 'surface' => '#F0F7FA', 'font' => 'Playfair Display + Inter'],
        'ethos_peak'   => ['primary' => '#2D3748', 'accent' => '#E53E3E', 'surface' => '#F7F6F3', 'font' => 'Merriweather + Inter'],
        'ethos_sunset' => ['primary' => '#C05621', 'accent' => '#6B46C1', 'surface' => '#FFFAF7', 'font' => 'Lora + Inter'],
    ];
@endphp
@extends('layouts.admin')

@section('breadcrumbs', 'Apariencia / Temas')

@section('title', 'Gestión de Temas Visuales')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Temas Visuales</h1>
    <p class="text-gray-500 mt-1">Selecciona el diseño visual de tu sitio web público. Cada tema es completamente independiente: tiene su propio CSS, paleta, tipografía y componentes.</p>
</div>

<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 flex items-start gap-3">
    <i class="fas fa-info-circle text-blue-600 text-lg mt-0.5"></i>
    <div class="text-sm text-blue-800">
        <p class="font-semibold mb-1">Tema activo: <span class="font-mono">{{ optional($agency)->active_theme ?? 'ethos_earth' }}</span></p>
        <p>Al cambiar de tema, toda la parte pública (header, footer, homepage, ficha de viaje, blog, reservas) cambia de piel sin afectar rutas, controladores ni lógica.</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @foreach($themes as $key => $theme)
    @php $palette = $themePalettes[$key] ?? []; @endphp
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden {{ $agency->active_theme === $key ? 'ring-2 ring-green-500' : '' }}">
        <div class="h-40 relative overflow-hidden" style="background: linear-gradient(135deg, {{ $palette['primary'] ?? '#1B3022' }} 0%, {{ $palette['accent'] ?? '#C28E60' }} 100%);">
            <div class="absolute inset-0 flex items-center justify-center">
                <i class="fas {{ $theme['icon'] }} text-white text-6xl opacity-30"></i>
            </div>
            <div class="absolute top-3 left-3 flex gap-1.5">
                <span class="w-6 h-6 rounded-full border-2 border-white/60" style="background: {{ $palette['primary'] ?? '#1B3022' }};" title="Color primario"></span>
                <span class="w-6 h-6 rounded-full border-2 border-white/60" style="background: {{ $palette['accent'] ?? '#C28E60' }};" title="Color acento"></span>
                <span class="w-6 h-6 rounded-full border-2 border-white/60" style="background: {{ $palette['surface'] ?? '#FCF9F5' }};" title="Color fondo"></span>
            </div>
            @if($agency->active_theme === $key)
            <span class="absolute top-3 right-3 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                <i class="fas fa-check mr-1"></i>Activo
            </span>
            @endif
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-lg font-bold text-gray-900">{{ $theme['name'] }}</h3>
                <span class="text-xs font-mono text-gray-400">{{ $key }}</span>
            </div>
            <p class="text-sm text-gray-500 mb-3">{{ $theme['description'] }}</p>
            <div class="text-xs text-gray-500 mb-4 flex items-center gap-2">
                <i class="fas fa-font"></i>
                <span>{{ $palette['font'] ?? 'Inter' }}</span>
            </div>
            <div class="flex items-center gap-3">
                @if($agency->active_theme !== $key)
                <form action="{{ route('admin.themes.activate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="theme" value="{{ $key }}">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-check-circle mr-1.5"></i>Activar
                    </button>
                </form>
                @else
                <span class="px-4 py-2 bg-green-100 text-green-700 text-sm font-medium rounded-lg">
                    <i class="fas fa-check mr-1.5"></i>Tema en uso
                </span>
                @endif
                <a href="{{ route('admin.themes.preview', $key) }}" target="_blank" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-eye mr-1.5"></i>Vista Previa
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 text-xl">
            <i class="fas fa-paint-brush"></i>
        </div>
        <div class="flex-1">
            <h3 class="text-lg font-bold text-gray-900">¿Quieres un diseño personalizado?</h3>
            <p class="text-sm text-gray-500">Contáctanos para crear un tema exclusivo para tu agencia con colores, tipografía y estilo propio.</p>
        </div>
        <a href="{{ url('/contacto') }}" target="_blank" class="shrink-0 px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
            Solicitar Diseño
        </a>
    </div>
</div>

<div class="mt-6 bg-gray-50 rounded-lg p-5">
    <h4 class="text-sm font-semibold text-gray-700 mb-3"><i class="fas fa-code-branch mr-2"></i>Arquitectura de temas</h4>
    <ul class="text-xs text-gray-600 space-y-1.5">
        <li><i class="fas fa-check text-green-500 mr-2"></i>CSS nativo autónomo por tema en <code class="bg-gray-200 px-1.5 py-0.5 rounded">public/css/themes/{slug}/style.css</code></li>
        <li><i class="fas fa-check text-green-500 mr-2"></i>Fuente CSS editable en <code class="bg-gray-200 px-1.5 py-0.5 rounded">tema-visual-base/{slug}.css</code></li>
        <li><i class="fas fa-check text-green-500 mr-2"></i>Vistas Blade por tema en <code class="bg-gray-200 px-1.5 py-0.5 rounded">resources/views/themes/{slug}/</code></li>
        <li><i class="fas fa-check text-green-500 mr-2"></i>Header y footer independientes por tema (partials)</li>
        <li><i class="fas fa-check text-green-500 mr-2"></i>Sin herencia de CSS entre temas — cada uno tiene su propio sistema de design tokens</li>
        <li><i class="fas fa-check text-green-500 mr-2"></i>Sin dependencia de Tailwind CDN en la parte pública</li>
    </ul>
</div>
@endsection
