@extends('layouts.admin')

@section('title', 'Alojamientos - ' . $trip->name)
@section('breadcrumbs', 'Viajes / ' . $trip->name . ' / Alojamientos')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.trips.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Viajes
        </a>
    </div>

    @include('admin.trips._tabs')

    @if(session('success'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-200 text-green-800 text-sm flex items-center gap-2">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 px-4 py-3 rounded-lg bg-red-100 border border-red-200 text-red-800 text-sm flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
    @endif

    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Nuevo Alojamiento</h2>
            <form action="{{ route('admin.trips.accommodations.store', $trip) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @csrf
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" required
                        class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estrellas</label>
                    <select name="stars" class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} {{ str_repeat('★', $i) }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                    <input type="text" name="location"
                        class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Web (enlace)</label>
                    <input type="url" name="link" placeholder="https://..."
                        class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div class="sm:col-span-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition text-sm">
                        <i class="fas fa-plus mr-2"></i>Añadir Alojamiento
                    </button>
                </div>
            </form>
        </div>

        @forelse($accommodations as $accommodation)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-start gap-4">
                    @if($accommodation->image)
                    <img src="{{ asset('storage/' . $accommodation->image) }}" alt="{{ $accommodation->name }}" class="w-20 h-20 rounded-lg object-cover">
                    @else
                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hotel text-gray-400 text-3xl"></i>
                    </div>
                    @endif
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ $accommodation->name }}</h3>
                        <p class="text-sm text-gray-500">
                            @for($i = 0; $i < $accommodation->stars; $i++)
                            <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                            @if($accommodation->location) · {{ $accommodation->location }} @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if($accommodation->link)
                    <a href="{{ $accommodation->link }}" target="_blank" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Ver web">
                        <i class="fas fa-external-link-alt"></i>
                    </a>
                    @endif
                    <form action="{{ route('admin.trips.accommodations.destroy', $accommodation) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar este alojamiento?" title="Eliminar"><i class="fas fa-trash"></i></button>
                    </form>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <h4 class="text-sm font-medium text-gray-700 mb-3">Habitaciones</h4>
                @if($accommodation->rooms->count() > 0)
                <div class="overflow-x-auto mb-4">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="text-left py-2 px-3 font-medium text-gray-600">Nº Habitación</th>
                                <th class="text-left py-2 px-3 font-medium text-gray-600">Tipo</th>
                                <th class="text-center py-2 px-3 font-medium text-gray-600">Capacidad</th>
                                <th class="text-center py-2 px-3 font-medium text-gray-600">Ocupadas</th>
                                <th class="text-right py-2 px-3 font-medium text-gray-600">Viajeros</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($accommodation->rooms as $room)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-3">{{ $room->room_number }}</td>
                                <td class="py-2 px-3 capitalize">{{ $room->type }}</td>
                                <td class="py-2 px-3 text-center">{{ $room->capacity }}</td>
                                <td class="py-2 px-3 text-center">{{ $room->travelers->count() }}</td>
                                <td class="py-2 px-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        @foreach($room->travelers as $rt)
                                        <span class="inline-flex items-center gap-1 text-xs bg-gray-100 px-2 py-1 rounded-full">
                                            {{ $rt->first_name }} {{ $rt->last_name }}
                                            <form action="{{ route('admin.trips.accommodations.rooms.remove', [$room, $rt]) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 ml-1" title="Remover">×</button>
                                            </form>
                                        </span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-500 mb-4">No hay habitaciones registradas.</p>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <form action="{{ route('admin.trips.accommodations.rooms.store', $accommodation) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Nº Habitación</label>
                            <input type="text" name="room_number" required
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                            <select name="type" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="individual">Individual</option>
                                <option value="doble" selected>Doble</option>
                                <option value="triple">Triple</option>
                                <option value="suite">Suite</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Capacidad</label>
                            <input type="number" min="1" max="20" value="2" name="capacity"
                                class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                            <i class="fas fa-plus mr-1"></i>Añadir
                        </button>
                    </form>

                    {{-- Assign traveler to room --}}
                    <form action="{{ route('admin.trips.accommodations.rooms.assign', ['room' => $accommodation->rooms->first()?->id ?? 0]) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                        @csrf
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Habitación</label>
                            <select name="room_id" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                @foreach($accommodation->rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->room_number }} ({{ $r->type }}, {{ $r->travelers->count() }}/{{ $r->capacity }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Viajero</label>
                            <select name="traveler_id" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                                <option value="">Seleccionar...</option>
                                @foreach($trip->bookings->load('traveler') as $booking)
                                <option value="{{ $booking->traveler_id }}">{{ $booking->traveler->first_name }} {{ $booking->traveler->last_name }} ({{ $booking->traveler->dni }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                            <i class="fas fa-user-plus mr-1"></i>Asignar
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-hotel"></i></div>
            <p class="text-gray-500 text-base">No hay alojamientos registrados.</p>
            <p class="text-gray-400 text-sm mt-1">Añade alojamientos para este viaje.</p>
        </div>
        @endforelse
    </div>
@endsection
