@extends('layouts.admin')

@section('breadcrumbs', 'Viajes / ' . $trip->name . ' / Documentación')

@section('title', 'Documentación - ' . $trip->name)

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

    <div class="space-y-6">
        {{-- Quick add document form --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Registrar Documento</h3>
            <form action="{{ route('admin.trips.documents.store', $trip) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-5 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Viajero</label>
                    <select name="traveler_id" required class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Seleccionar...</option>
                        @foreach($trip->bookings->load('traveler') as $booking)
                        <option value="{{ $booking->traveler_id }}">{{ $booking->traveler->first_name }} {{ $booking->traveler->last_name }} ({{ $booking->traveler->dni }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Tipo</label>
                    <select name="type" required class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="authorization">Autorización padres</option>
                        <option value="insurance">Seguro</option>
                        <option value="medical">Ficha médica</option>
                        <option value="id_card">DNI/Pasaporte</option>
                        <option value="other">Otro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Etiqueta</label>
                    <input type="text" name="label" required value="Autorización padres" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Estado</label>
                    <select name="status" required class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="pending">Pendiente</option>
                        <option value="in_review">En Revisión</option>
                        <option value="complete">Completa</option>
                        <option value="rejected">Rechazado</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                    <i class="fas fa-plus mr-1"></i>Registrar
                </button>
            </form>
        </div>

        {{-- Per traveler documents --}}
        @forelse($trip->bookings->load('traveler') as $booking)
            @php $travelerDocs = $documents->get($booking->traveler_id) ?? collect(); @endphp
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">{{ $booking->traveler->first_name }} {{ $booking->traveler->last_name }}</h3>
                        <p class="text-xs text-gray-500">{{ $booking->traveler->dni }}</p>
                    </div>
                </div>

                @if($travelerDocs->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-4">Sin documentos registrados.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 border-b">
                                    <th class="text-left py-2 px-3 font-medium text-gray-600">Documento</th>
                                    <th class="text-center py-2 px-3 font-medium text-gray-600">Estado</th>
                                    <th class="text-center py-2 px-3 font-medium text-gray-600">Archivo</th>
                                    <th class="text-right py-2 px-3 font-medium text-gray-600">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($travelerDocs as $doc)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-2 px-3">
                                        <span class="font-medium text-gray-900">{{ $doc->label }}</span>
                                        @if($doc->notes)
                                        <br><span class="text-xs text-gray-400">{{ $doc->notes }}</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                            {{ $doc->status == 'complete' ? 'bg-green-100 text-green-800' : ($doc->status == 'in_review' ? 'bg-blue-100 text-blue-800' : ($doc->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800')) }}">
                                            {{ $doc->status == 'complete' ? 'Completa' : ($doc->status == 'in_review' ? 'En Revisión' : ($doc->status == 'rejected' ? 'Rechazado' : 'Pendiente')) }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 text-center">
                                        @if($doc->file_path)
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs" title="Ver archivo">
                                            <i class="fas fa-file-download"></i> Ver
                                        </a>
                                        @else
                                        <span class="text-xs text-gray-400">Sin archivo</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 text-right">
                                        <form action="{{ route('admin.trips.documents.update', [$trip, $doc]) }}" method="POST" class="inline-flex items-center gap-2">
                                            @csrf @method('PUT')
                                            <select name="status" class="text-xs border-gray-300 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200" onchange="this.form.submit()">
                                                <option value="pending" {{ $doc->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="in_review" {{ $doc->status == 'in_review' ? 'selected' : '' }}>En Revisión</option>
                                                <option value="complete" {{ $doc->status == 'complete' ? 'selected' : '' }}>Completa</option>
                                            </select>
                                        </form>
                                        <form action="{{ route('admin.trips.documents.destroy', [$trip, $doc]) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1 text-gray-400 hover:text-red-600" data-confirm="¿Eliminar este documento?" title="Eliminar">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16">
                <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-file-alt"></i></div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">No hay viajeros inscritos</h3>
                <p class="text-sm text-gray-400">La gestión de documentos estará disponible cuando haya viajeros inscritos en este viaje.</p>
            </div>
        @endforelse
    </div>
@endsection
