@extends('layouts.admin')

@section('breadcrumbs', 'Contenido / Servicios / Editar')

@section('title', 'Editar Servicio')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.services.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Servicios
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('admin.services.update', $service) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="name" id="name" value="{{ old('name', $service->name) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-1">Icono (Font Awesome)</label>
                <input type="text" name="icon" id="icon" value="{{ old('icon', $service->icon) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                @if($service->image)
                <div class="mb-2"><img src="{{ asset('storage/' . $service->image) }}" class="h-16 w-auto rounded-lg object-cover"></div>
                @endif
                <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div>
                <label class="inline-flex items-center gap-2 mt-6">
                    <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm text-gray-700">Activo</span>
                </label>
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="description" id="description" rows="6" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('description', $service->description) }}</textarea>
            @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.services.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-1"></i> Actualizar Servicio
            </button>
        </div>
    </form>
</div>
@endsection
