@extends('layouts.admin')

@section('breadcrumbs', 'Programas')

@section('title', 'Programas')

@section('content')
    {{-- Category Stats --}}
    @if(isset($categoryStats) && $categoryStats->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
            @foreach($categoryStats as $cat)
                <div class="bg-surface-container-lowest rounded-xl card-shadow border border-outline-variant p-4 text-center hover:border-primary transition-colors group">
                    <div class="text-2xl text-primary mb-1">
                        @if($cat->icon)<span class="material-symbols-outlined text-3xl">{{ $cat->icon }}</span>@else<span class="material-symbols-outlined text-3xl">tag</span>@endif
                    </div>
                    <div class="text-xl font-bold text-on-background">{{ $cat->programs_count }}</div>
                    <div class="text-xs text-secondary truncate">{{ $cat->name }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl card-shadow border border-outline-variant">
        <div class="px-6 py-5 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-on-background">Programas</h2>
            <div class="flex items-center gap-2">
                <div class="flex items-center border border-outline-variant rounded-lg overflow-hidden">
                    <a href="{{ route('admin.programs.index', array_merge(request()->query(), ['view' => 'table'])) }}" class="px-3 py-2 text-sm {{ $viewMode === 'table' ? 'bg-primary/10 text-primary' : 'text-secondary hover:bg-surface' }}">
                        <span class="material-symbols-outlined text-lg">list</span>
                    </a>
                    <a href="{{ route('admin.programs.index', array_merge(request()->query(), ['view' => 'grid'])) }}" class="px-3 py-2 text-sm {{ $viewMode === 'grid' ? 'bg-primary/10 text-primary' : 'text-secondary hover:bg-surface' }}">
                        <span class="material-symbols-outlined text-lg">grid_view</span>
                    </a>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-secondary-container text-on-secondary-container text-sm font-medium rounded-lg hover:opacity-80 transition-colors">
                    <span class="material-symbols-outlined text-lg">category</span>
                    Categorías
                </a>
                <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Nuevo Programa
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
            <form method="GET" class="flex flex-wrap gap-3">
                <input type="hidden" name="view" value="{{ $viewMode }}">
                <select name="category" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                    <option value="">Todas las categorías</option>
                    @foreach($categoryStats as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                    <option value="">Todos los estados</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
                <input type="text" name="search" placeholder="Buscar programa..." value="{{ request('search') }}" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                <button type="submit" class="px-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm font-medium text-secondary hover:bg-surface transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">filter_alt</span>
                    Filtrar
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            @if (isset($programs) && $programs->count() > 0)
                @if($viewMode === 'table')
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-left">
                            <th class="px-6 py-3 font-medium">Nombre</th>
                            <th class="px-6 py-3 font-medium">Categoría</th>
                            <th class="px-6 py-3 font-medium">Duración</th>
                            <th class="px-6 py-3 font-medium">Rango Edad</th>
                            <th class="px-6 py-3 font-medium">Estado</th>
                            <th class="px-6 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($programs as $program)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($program->image)
                                            <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->name }}" class="w-10 h-10 rounded-lg object-cover">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-layer-group"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="font-medium text-gray-900">{{ $program->name }}</span>
                                            <span class="block text-xs text-gray-400">{{ $program->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 text-gray-600">
                                        <i class="fas fa-tag text-gray-400"></i>
                                        {{ $program->category->name ?? 'Sin categoría' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <i class="far fa-calendar-alt text-gray-400 mr-1"></i>
                                    {{ $program->duration_days }} días
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $program->age_range ?? '--' }}</td>
                                <td class="px-6 py-4">
                                    @include('admin.partials._badge', ['type' => $program->is_active ? 'success' : 'gray', 'slot' => $program->is_active ? 'Activo' : 'Inactivo'])
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.programs.edit', $program->id) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.programs.duplicate', $program->id) }}" class="p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Duplicar" data-confirm="¿Duplicar este programa?">
                                            <i class="fas fa-copy"></i>
                                        </a>
                                        <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar" data-confirm="¿Eliminar este programa? Esta acción no se puede deshacer.">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($programs as $program)
                        <div class="border border-gray-200 rounded-xl overflow-hidden hover:shadow-md transition-shadow">
                            <div class="h-32 bg-gradient-to-br from-blue-50 to-purple-50 flex items-center justify-center">
                                @if ($program->image)
                                    <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-layer-group text-4xl text-blue-300"></i>
                                @endif
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="font-semibold text-gray-900">{{ $program->name }}</h3>
                                    @include('admin.partials._badge', ['type' => $program->is_active ? 'success' : 'gray', 'slot' => $program->is_active ? 'Activo' : 'Inactivo'])
                                </div>
                                <p class="text-xs text-gray-500 mb-2">{{ $program->category->name ?? 'Sin categoría' }}</p>
                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $program->description ?? 'Sin descripción' }}</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $program->duration_days }} días</span>
                                    <span><i class="fas fa-user mr-1"></i> {{ $program->age_range ?? '--' }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
                                    <a href="{{ route('admin.programs.edit', $program->id) }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Editar <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                    <form action="{{ route('admin.programs.destroy', $program->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium" data-confirm="¿Eliminar este programa?">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $programs->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-gray-300 text-5xl mb-4">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">No hay programas registrados</h3>
                    <p class="text-sm text-gray-400 mb-4">Crea tu primer programa para comenzar.</p>
                    <a href="{{ route('admin.programs.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus"></i>
                        Crear Programa
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
