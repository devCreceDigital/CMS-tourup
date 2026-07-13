@extends('layouts.admin')

@section('breadcrumbs', 'Apariencia / Imágenes')

@section('title', 'Gestión de Imágenes')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-lg font-semibold text-gray-900">Imágenes del Sitio</h2>
        <p class="text-sm text-gray-500 mt-1">Sube imágenes personalizadas para reemplazar las de stock del tema activo.</p>
    </div>
</div>

@if (session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
        <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
    </div>
@endif

{{-- Upload Form --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
    <div class="px-6 py-5 border-b border-gray-200">
        <h3 class="text-sm font-semibold text-gray-900">Subir Nueva Imagen</h3>
    </div>
    <form action="{{ route('admin.images.upload') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Clave única</label>
                <input type="text" name="key" placeholder="ej: hero_bg, about_team" value="{{ old('key') }}"
                    class="w-full rounded-lg border-gray-300 text-sm @error('key') border-red-500 @enderror">
                @error('key') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sección</label>
                <select name="section" class="w-full rounded-lg border-gray-300 text-sm @error('section') border-red-500 @enderror">
                    <option value="hero">Hero y Portadas</option>
                    <option value="about">Sobre Nosotros</option>
                    <option value="blog">Blog</option>
                    <option value="services">Servicios</option>
                    <option value="contact">Contacto</option>
                    <option value="general">General</option>
                </select>
                @error('section') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Archivo</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-500 @enderror">
                @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Texto alternativo</label>
                <input type="text" name="alt_text" placeholder="Descripción de la imagen" value="{{ old('alt_text') }}"
                    class="w-full rounded-lg border-gray-300 text-sm">
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-upload mr-1"></i> Subir Imagen
            </button>
        </div>
    </form>
</div>

{{-- Images by Section --}}
@if ($images->count() > 0)
    @foreach ($sections as $sectionKey => $sectionName)
        @if (isset($images[$sectionKey]))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">{{ $sectionName }}</h3>
                    <span class="text-xs text-gray-500">{{ $images[$sectionKey]->count() }} imagen(es)</span>
                </div>
                <div class="p-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($images[$sectionKey] as $image)
                        <div class="group relative border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <div class="aspect-w-16 aspect-h-9 bg-gray-100">
                                <img src="{{ asset('storage/' . $image->file_path) }}" alt="{{ $image->alt_text ?: $image->key }}" class="w-full h-32 object-cover">
                            </div>
                            <div class="p-2">
                                <p class="text-xs font-medium text-gray-700 truncate">{{ $image->key }}</p>
                                @if ($image->alt_text)
                                    <p class="text-xs text-gray-400 truncate">{{ $image->alt_text }}</p>
                                @endif
                            </div>
                            <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center text-xs hover:bg-red-600" onclick="return confirm('¿Eliminar esta imagen?')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@else
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="text-center py-16">
            <div class="text-gray-300 text-5xl mb-4">
                <i class="fas fa-images"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-500 mb-2">No hay imágenes subidas</h3>
            <p class="text-sm text-gray-400 mb-4">Las imágenes de stock del tema activo se usarán hasta que subas tus propias imágenes.</p>
        </div>
    </div>
@endif
@endsection
