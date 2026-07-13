@extends('layouts.admin')

@section('breadcrumbs', 'Viajes')

@section('title', 'Viajes')

@section('content')
    <div class="bg-surface-container-lowest rounded-xl card-shadow border border-outline-variant">
        <div class="px-6 py-5 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-on-background">Viajes</h2>
            <a href="{{ route('admin.trips.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-lg">add</span>
                Nuevo Viaje
            </a>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
            <form method="GET" action="{{ route('admin.trips.index') }}" class="flex flex-col sm:flex-row gap-3">
                <select name="year" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                    <option value="">Todos los años</option>
                    @foreach (range(date('Y'), date('Y') + 2) as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>

                <select name="status" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="on_sale" {{ request('status') == 'on_sale' ? 'selected' : '' }}>En Venta</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>

                <input type="text" name="search" placeholder="Buscar por referencia..." value="{{ request('search') }}" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">

                <button type="submit" class="px-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm font-medium text-secondary hover:bg-surface transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">filter_alt</span>
                    Filtrar
                </button>

                @if (request()->anyFilled(['year', 'status', 'search']))
                    <a href="{{ route('admin.trips.index') }}" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">close</span>
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            @if (isset($trips) && $trips->count() > 0)
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container text-on-surface font-medium text-xs uppercase tracking-wider">
                            <th class="px-6 py-4">Referencia</th>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Destino</th>
                            <th class="px-6 py-4">Fechas</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Plazas</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach ($trips as $trip)
                            <tr class="hover:bg-surface-container-low transition-colors group">
                                <td class="px-6 py-4 font-mono text-xs text-outline">{{ $trip->reference }}</td>
                                <td class="px-6 py-4 font-medium text-on-background">{{ $trip->name }}</td>
                                <td class="px-6 py-4 text-on-surface-variant">{{ $trip->destination ?? '--' }}</td>
                                <td class="px-6 py-4 text-on-surface-variant text-xs">
                                    {{ \Carbon\Carbon::parse($trip->start_date)->format('d/m/Y') }}<br>
                                    {{ \Carbon\Carbon::parse($trip->end_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter
                                        @if($trip->status == 'active') bg-green-100 text-green-700
                                        @elseif($trip->status == 'on_sale') bg-blue-100 text-blue-700
                                        @elseif($trip->status == 'completed') bg-surface-container text-secondary
                                        @elseif($trip->status == 'cancelled') bg-red-100 text-red-700
                                        @else bg-surface-container text-secondary @endif">
                                        @switch($trip->status)
                                            @case('active') Activo @break
                                            @case('on_sale') En Venta @break
                                            @case('completed') Completado @break
                                            @case('cancelled') Cancelado @break
                                            @default {{ ucfirst($trip->status) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant">{{ $trip->occupied_spots ?? 0 }}/{{ $trip->total_spots }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1 row-actions">
                                        <a href="{{ route('admin.trips.transport.index', $trip) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Transporte">
                                            <span class="material-symbols-outlined text-lg">directions_bus</span>
                                        </a>
                                        <a href="{{ route('admin.trips.accommodations.index', $trip) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Alojamiento">
                                            <span class="material-symbols-outlined text-lg">bed</span>
                                        </a>
                                        <a href="{{ route('admin.trips.show', $trip->id) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Ver">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.trips.edit', $trip->id) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Editar">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <a href="{{ route('admin.trips.duplicate', $trip->id) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Duplicar" data-confirm="¿Duplicar este viaje?">
                                            <span class="material-symbols-outlined text-lg">content_copy</span>
                                        </a>
                                        <form action="{{ route('admin.trips.destroy', $trip->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-secondary hover:text-error hover:bg-error-container/50 rounded-lg transition-colors" title="Eliminar" data-confirm="¿Eliminar este viaje? Esta acción no se puede deshacer.">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-16">
                    <div class="text-outline text-5xl mb-4"><span class="material-symbols-outlined text-6xl">explore</span></div>
                    <h3 class="text-lg font-medium text-secondary mb-2">No hay viajes registrados</h3>
                    <p class="text-sm text-outline mb-4">Crea tu primer viaje para comenzar.</p>
                    <a href="{{ route('admin.trips.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-sm active:scale-95">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Crear Viaje
                    </a>
                </div>
            @endif
        </div>

        @if (isset($trips) && $trips->hasPages())
            <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low">
                {{ $trips->links() }}
            </div>
        @endif
    </div>
@endsection
