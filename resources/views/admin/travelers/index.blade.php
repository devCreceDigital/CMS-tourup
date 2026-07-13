@extends('layouts.admin')

@section('breadcrumbs', 'Pasajeros')

@section('title', 'Pasajeros')

@section('content')
    {{-- Stats Bento Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-surface-container-lowest p-5 rounded-xl card-shadow border border-outline-variant flex items-center gap-4 hover:border-primary transition-colors group">
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined">check_circle</span>
            </div>
            <div>
                <p class="text-secondary text-sm font-medium mb-0.5">Confirmados</p>
                <p class="text-2xl font-bold text-on-background">{{ $stats['confirmed'] ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-xl card-shadow border border-outline-variant flex items-center gap-4 hover:border-primary transition-colors group">
            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined">hourglass_empty</span>
            </div>
            <div>
                <p class="text-secondary text-sm font-medium mb-0.5">Pendientes</p>
                <p class="text-2xl font-bold text-on-background">{{ $stats['pending'] ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-5 rounded-xl card-shadow border border-outline-variant flex items-center gap-4 hover:border-primary transition-colors group">
            <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined">list_alt</span>
            </div>
            <div>
                <p class="text-secondary text-sm font-medium mb-0.5">Lista Espera</p>
                <p class="text-2xl font-bold text-on-background">{{ $stats['waitlist'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Main Table --}}
    <div class="bg-surface-container-lowest rounded-xl card-shadow border border-outline-variant">
        <div class="px-6 py-5 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-on-background">Pasajeros</h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.travelers.export') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-sm font-medium text-secondary hover:bg-surface transition-colors">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Exportar
                </a>
                <a href="{{ route('admin.travelers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Nuevo Pasajero
                </a>
            </div>
        </div>

        {{-- Filters --}}
        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
            <form method="GET" action="{{ route('admin.travelers.index') }}" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input type="text" name="search" placeholder="Buscar por nombre, DNI o email..." value="{{ request('search') }}" class="w-full pl-10 text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                </div>

                <select name="status" class="w-full sm:w-44 text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                    <option value="">Todos los estados</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmado</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="waitlist" {{ request('status') == 'waitlist' ? 'selected' : '' }}>Lista Espera</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm font-medium text-secondary hover:bg-surface transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-lg">filter_alt</span>
                    Filtrar
                </button>

                @if (request()->anyFilled(['search', 'status']))
                    <a href="{{ route('admin.travelers.index') }}" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-lg">close</span>
                        Limpiar
                    </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            @if (isset($travelers) && $travelers->count() > 0)
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container text-on-surface font-medium text-xs uppercase tracking-wider">
                            <th class="px-6 py-4">Nombre Completo</th>
                            <th class="px-6 py-4">DNI</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Teléfono</th>
                            <th class="px-6 py-4">Viaje</th>
                            <th class="px-6 py-4">Bus/Asiento</th>
                            <th class="px-6 py-4">Estado Pago</th>
                            <th class="px-6 py-4">Documentación</th>
                            <th class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @foreach ($travelers as $traveler)
                            @php
                                $booking = $traveler->bookings->first();
                            @endphp
                            <tr class="hover:bg-surface-container-low transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-on-background">{{ $traveler->first_name }} {{ $traveler->last_name }}</span>
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant font-mono text-xs">{{ $traveler->dni ?? '--' }}</td>
                                <td class="px-6 py-4 text-on-surface-variant">{{ $traveler->email ?? '--' }}</td>
                                <td class="px-6 py-4 text-on-surface-variant">{{ $traveler->phone ?? '--' }}</td>
                                <td class="px-6 py-4 text-on-surface-variant">
                                    @if ($booking && $booking->trip)
                                        {{ $booking->trip->reference }}
                                    @else
                                        <span class="text-outline">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant text-xs">
                                    @if ($booking && $booking->bus && $booking->seat)
                                        {{ $booking->bus->name ?? 'Bus' }} / #{{ $booking->seat->seat_number ?? '' }}
                                    @else
                                        <span class="text-outline">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($booking)
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter
                                            @if($booking->payment_status == 'paid') bg-green-100 text-green-700
                                            @elseif($booking->payment_status == 'pending') bg-amber-100 text-amber-700
                                            @elseif($booking->payment_status == 'partial') bg-blue-100 text-blue-700
                                            @else bg-surface-container text-secondary @endif">
                                            @switch($booking->payment_status)
                                                @case('paid') Pagado @break
                                                @case('pending') Pendiente @break
                                                @case('partial') Parcial @break
                                                @default {{ ucfirst($booking->payment_status) }}
                                            @endswitch
                                        </span>
                                    @else
                                        <span class="text-outline">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($booking)
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tighter
                                            @if($booking->document_status == 'complete') bg-green-100 text-green-700
                                            @elseif($booking->document_status == 'pending') bg-amber-100 text-amber-700
                                            @else bg-surface-container text-secondary @endif">
                                            @switch($booking->document_status)
                                                @case('complete') Completa @break
                                                @case('pending') Pendiente @break
                                                @default {{ ucfirst($booking->document_status) }}
                                            @endswitch
                                        </span>
                                    @else
                                        <span class="text-outline">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-1 row-actions">
                                        <a href="{{ route('admin.travelers.show', $traveler->id) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Ver ficha">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.travelers.edit', $traveler->id) }}" class="p-2 text-secondary hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Editar">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('admin.travelers.destroy', $traveler->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-secondary hover:text-error hover:bg-error-container/50 rounded-lg transition-colors" title="Eliminar" data-confirm="¿Eliminar este pasajero?">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t border-outline-variant bg-surface-container-low">
                    {{ $travelers->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-outline text-5xl mb-4">
                        <span class="material-symbols-outlined text-6xl">group</span>
                    </div>
                    <h3 class="text-lg font-medium text-secondary mb-2">No hay pasajeros registrados</h3>
                    <p class="text-sm text-outline mb-4">Los pasajeros aparecerán cuando se registren en un viaje.</p>
                    <a href="{{ route('admin.travelers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-sm active:scale-95">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Nuevo Pasajero
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
