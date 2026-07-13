@extends('layouts.admin')

@section('breadcrumbs', 'Viajes / ' . $trip->name . ' / Viajeros')

@section('title', 'Viajeros - ' . $trip->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.trips.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Viajes
        </a>
    </div>

    @include('admin.trips._tabs')

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <span class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</span>
            <p class="text-xs text-gray-500 mt-1">Total Viajeros</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <span class="text-2xl font-bold text-green-600">{{ $stats['confirmed'] }}</span>
            <p class="text-xs text-gray-500 mt-1">Confirmados</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <span class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</span>
            <p class="text-xs text-gray-500 mt-1">Pendientes</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 text-center">
            <span class="text-2xl font-bold text-gray-400">{{ $stats['waitlist'] }}</span>
            <p class="text-xs text-gray-500 mt-1">Lista Espera</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        @if($bookings->count())
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="text-left py-3 px-4 font-medium text-gray-600">Viajero</th>
                        <th class="text-left py-3 px-4 font-medium text-gray-600">DNI</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-600">Estado Pago</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-600">Estado Reserva</th>
                        <th class="text-center py-3 px-4 font-medium text-gray-600">Documentación</th>
                        <th class="text-right py-3 px-4 font-medium text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-4">
                            <span class="font-medium text-gray-900">{{ $booking->traveler->first_name }} {{ $booking->traveler->last_name }}</span>
                            @if($booking->traveler->email)
                                <br><span class="text-xs text-gray-400">{{ $booking->traveler->email }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-gray-600">{{ $booking->traveler->dni }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $booking->payment_status == 'paid' ? 'bg-green-100 text-green-800' : ($booking->payment_status == 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800') }}">
                                {{ $booking->payment_status == 'paid' ? 'Pagado' : ($booking->payment_status == 'pending' ? 'Pendiente' : 'Vencido') }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $booking->booking_status == 'confirmed' ? 'bg-green-100 text-green-800' : ($booking->booking_status == 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-600') }}">
                                {{ $booking->booking_status == 'confirmed' ? 'Confirmado' : ($booking->booking_status == 'pending' ? 'Pendiente' : 'Lista Espera') }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                {{ $booking->document_status == 'complete' ? 'bg-green-100 text-green-800' : ($booking->document_status == 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ $booking->document_status == 'complete' ? 'Completa' : ($booking->document_status == 'pending' ? 'Pendiente' : 'En Revisión') }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('admin.travelers.edit', $booking->traveler) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors inline-block" title="Ver viajero">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
        @else
        <div class="text-center py-16">
            <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-users"></i></div>
            <h3 class="text-lg font-medium text-gray-500 mb-2">No hay viajeros inscritos</h3>
            <p class="text-sm text-gray-400">Los viajeros aparecerán aquí cuando se inscriban a través del sistema de reservas.</p>
        </div>
        @endif
    </div>
@endsection
