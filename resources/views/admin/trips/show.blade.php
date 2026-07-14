@extends('layouts.admin')

@section('breadcrumbs', 'Viajes / ' . $trip->name)

@section('title', $trip->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.trips.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Viajes
        </a>
    </div>

    @include('admin.trips._tabs')

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-4">Detalles del Viaje</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Referencia</dt>
                        <dd class="text-sm font-mono text-gray-900 mt-1">{{ $trip->reference }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Nombre</dt>
                        <dd class="text-sm font-medium text-gray-900 mt-1">{{ $trip->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Slug</dt>
                        <dd class="text-sm text-gray-600 mt-1">{{ $trip->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Categoría</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $trip->category->name ?? 'Sin categoría' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Destino</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $trip->destination ?? '--' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Estado</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($trip->status == 'active') bg-green-100 text-green-800
                                @elseif($trip->status == 'on_sale') bg-blue-100 text-blue-800
                                @elseif($trip->status == 'completed') bg-gray-100 text-gray-600
                                @elseif($trip->status == 'cancelled') bg-red-100 text-red-600
                                @else bg-gray-100 text-gray-600 @endif">
                                @switch($trip->status)
                                    @case('active') Activo @break
                                    @case('on_sale') En Venta @break
                                    @case('completed') Completado @break
                                    @case('cancelled') Cancelado @break
                                    @default {{ ucfirst($trip->status) }}
                                @endswitch
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-4">Fechas y Capacidad</h3>
                <dl class="space-y-4">
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Fecha Inicio</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $trip->start_date ? \Carbon\Carbon::parse($trip->start_date)->format('d/m/Y') : '--' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Fecha Fin</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $trip->end_date ? \Carbon\Carbon::parse($trip->end_date)->format('d/m/Y') : '--' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Duración</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            @if($trip->start_date && $trip->end_date)
                                {{ \Carbon\Carbon::parse($trip->start_date)->diffInDays(\Carbon\Carbon::parse($trip->end_date)) + 1 }} días
                            @else
                                <span class="text-gray-400">No definido</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Precio</dt>
                        <dd class="text-lg font-bold text-gray-900 mt-1">
                            @php $firstGroup = $trip->pricingGroups->first(); @endphp
                            @if($firstGroup && $firstGroup->installments->isNotEmpty())
                                ${{ number_format($firstGroup->installments->first()->amount, 2) }}
                                <span class="text-xs font-normal text-gray-500">({{ $firstGroup->name }})</span>
                            @else
                                <span class="text-sm font-normal text-gray-400">Sin precio configurado</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-gray-400 uppercase tracking-wide">Plazas</dt>
                        <dd class="text-sm text-gray-900 mt-1">
                            {{ $trip->occupied_spots ?? 0 }} / {{ $trip->total_spots }} ocupadas
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2 max-w-xs">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $trip->total_spots > 0 ? min(($trip->occupied_spots ?? 0) / $trip->total_spots * 100, 100) : 0 }}%"></div>
                            </div>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        @if ($trip->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Descripción</h3>
                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $trip->description }}</p>
            </div>
        @endif

        @if ($trip->image)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-medium text-gray-500 mb-3">Imagen</h3>
                <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}" class="rounded-xl max-w-md border">
            </div>
        @endif

        <div class="mt-6 pt-6 border-t border-gray-200 flex items-center gap-3">
            <a href="{{ route('admin.trips.edit', $trip->id) }}" class="px-4 py-2 bg-amber-500 text-white text-sm font-medium rounded-lg hover:bg-amber-600 transition-colors">
                <i class="fas fa-edit mr-1"></i> Editar
            </a>
            <form action="{{ route('admin.trips.duplicate', $trip->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Duplicar este viaje?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-copy mr-1"></i> Duplicar
                </button>
            </form>
        </div>
    </div>
@endsection
