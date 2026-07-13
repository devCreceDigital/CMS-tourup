@extends('layouts.admin')

@section('breadcrumbs', 'Buscar')

@section('title', 'Resultados de Búsqueda')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200">
        <h2 class="text-lg font-semibold text-gray-900">Buscador Global</h2>
        <p class="text-sm text-gray-500 mt-1">Resultados para tu búsqueda en viajes, viajeros, blog, FAQ y programas.</p>
    </div>

    <div class="p-6">
        <form method="GET" action="{{ route('admin.search') }}" class="mb-6">
            <div class="flex gap-3">
                <input type="text" name="q" value="{{ $query }}" placeholder="Buscar viajes, viajeros, blog, FAQ..."
                    class="flex-1 rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                    autofocus>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-search mr-1"></i> Buscar
                </button>
            </div>
        </form>

        @if (strlen(trim($query)) < 2)
            <div class="text-center py-12">
                <div class="text-gray-300 text-5xl mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Introduce al menos 2 caracteres</h3>
                <p class="text-sm text-gray-400">Escribe en el campo de búsqueda para encontrar lo que necesitas.</p>
            </div>
        @elseif ($total === 0)
            <div class="text-center py-12">
                <div class="text-gray-300 text-5xl mb-4">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">Sin resultados</h3>
                <p class="text-sm text-gray-400">No se encontraron coincidencias para "{{ $query }}". Prueba con otros términos.</p>
            </div>
        @else
            <p class="text-sm text-gray-500 mb-6">{{ $total }} resultado(s) para "{{ $query }}"</p>

            <div class="space-y-6">
                @foreach ($results as $group)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <i class="fas {{ $group['icon'] }} text-blue-600"></i>
                            {{ $group['section'] }}
                            <span class="text-xs text-gray-400 font-normal">({{ $group['items']->count() }})</span>
                        </h3>
                        <div class="space-y-1">
                            @foreach ($group['items'] as $item)
                                <a href="{{ $item['url'] }}" class="block px-4 py-2.5 bg-gray-50 hover:bg-blue-50 rounded-lg transition-colors text-sm text-gray-700 hover:text-blue-700">
                                    {{ $item['title'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
