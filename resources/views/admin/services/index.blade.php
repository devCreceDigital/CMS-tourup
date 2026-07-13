@extends('layouts.admin')

@section('breadcrumbs', 'Contenido / Servicios')

@section('title', 'Servicios')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Servicios</h1>
    <a href="{{ route('admin.services.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Nuevo Servicio
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($services as $service)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="w-12 h-12 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xl">
                <i class="fas {{ $service->icon ?: 'fa-concierge-bell' }}"></i>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                {{ $service->is_active ? 'Activo' : 'Inactivo' }}
            </span>
        </div>
        <h3 class="text-base font-semibold text-gray-900 mb-2">{{ $service->name }}</h3>
        @if($service->description)
        <p class="text-sm text-gray-500 mb-4">{{ Str::limit($service->description, 120) }}</p>
        @endif
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <span class="text-xs text-gray-400">Orden: {{ $service->order }}</span>
            <div class="flex items-center gap-1">
                <a href="{{ route('admin.services.edit', $service) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar este servicio?">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full text-center py-16">
        <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-concierge-bell"></i></div>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No hay servicios registrados</h3>
        <p class="text-sm text-gray-400 mb-4">Añade los servicios que ofrece tu agencia.</p>
        <a href="{{ route('admin.services.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus"></i> Crear Servicio
        </a>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $services->links() }}
</div>
@endsection
