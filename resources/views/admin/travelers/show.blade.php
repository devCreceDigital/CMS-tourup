@extends('layouts.admin')

@section('breadcrumbs', 'Pasajeros / ' . $traveler->first_name . ' ' . $traveler->last_name)

@section('title', $traveler->first_name . ' ' . $traveler->last_name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.travelers.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Pasajeros
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Profile Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex flex-col items-center text-center mb-4">
                    <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-bold mb-3">
                        {{ substr($traveler->first_name, 0, 1) }}{{ substr($traveler->last_name, 0, 1) }}
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $traveler->first_name }} {{ $traveler->last_name }}</h2>
                    <p class="text-sm text-gray-500">{{ $traveler->dni ?? 'Sin DNI' }}</p>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-envelope text-gray-400 w-4"></i>
                        <span>{{ $traveler->email ?? '—' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-phone text-gray-400 w-4"></i>
                        <span>{{ $traveler->phone ?? '—' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-calendar text-gray-400 w-4"></i>
                        <span>{{ $traveler->birth_date ? \Carbon\Carbon::parse($traveler->birth_date)->format('d/m/Y') : '—' }}</span>
                    </div>
                    <div class="flex items-center gap-3 text-gray-600">
                        <i class="fas fa-venus-mars text-gray-400 w-4"></i>
                        <span>{{ $traveler->sex ?? '—' }}</span>
                    </div>
                    <div class="flex items-start gap-3 text-gray-600">
                        <i class="fas fa-map-marker-alt text-gray-400 w-4 mt-0.5"></i>
                        <span>{{ $traveler->address ?? '—' }}</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('admin.travelers.edit', $traveler->id) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-edit"></i> Editar Viajero
                    </a>
                </div>

                @if($traveler->notes)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-900 mb-2">Notas</h4>
                        <p class="text-sm text-gray-600">{{ $traveler->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Bookings & Documents --}}
        <div class="lg:col-span-2 space-y-6">
            {{--- Bookings ---}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">
                        <i class="fas fa-suitcase mr-2 text-blue-500"></i>Reservas ({{ $traveler->bookings->count() }})
                    </h3>
                </div>
                @if($traveler->bookings->isEmpty())
                    <div class="text-center py-8 text-sm text-gray-400">Sin reservas registradas.</div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($traveler->bookings as $booking)
                            <div class="px-6 py-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ $booking->trip->name ?? 'Viaje' }}</span>
                                        <span class="text-xs text-gray-400 ml-2">#{{ $booking->reference ?? $booking->id }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @include('admin.partials._badge', ['type' => $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'partial' ? 'warning' : 'danger'), 'slot' => $booking->payment_status === 'paid' ? 'Pagado' : ($booking->payment_status === 'partial' ? 'Parcial' : 'Pendiente')])
                                        @include('admin.partials._badge', ['type' => $booking->booking_status === 'confirmed' ? 'success' : ($booking->booking_status === 'cancelled' ? 'danger' : 'warning'), 'slot' => $booking->booking_status === 'confirmed' ? 'Confirmado' : ucfirst($booking->booking_status)])
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 text-xs text-gray-500">
                                    <span><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::parse($booking->trip->start_date ?? '')->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-dollar-sign mr-1"></i> ${{ number_format($booking->amount_paid ?? 0, 2) }}</span>
                                    <span><i class="fas fa-file-alt mr-1"></i> {{ $booking->document_status === 'complete' ? 'Completo' : 'Pendiente' }}</span>
                                </div>
                                @if($booking->payments->isNotEmpty())
                                    <div class="mt-2 pt-2 border-t border-gray-50">
                                        <span class="text-xs text-gray-400 font-medium">Pagos:</span>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            @foreach($booking->payments as $payment)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $payment->status === 'confirmed' ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                                    ${{ number_format($payment->amount ?? 0, 2) }}
                                                    @if($payment->payment_date)
                                                        - {{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{--- Documents ---}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">
                        <i class="fas fa-file-alt mr-2 text-amber-500"></i>Documentos ({{ $traveler->documents->count() }})
                    </h3>
                </div>
                @if($traveler->documents->isEmpty())
                    <div class="text-center py-8 text-sm text-gray-400">Sin documentos registrados.</div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach($traveler->documents as $doc)
                            <div class="px-6 py-3 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file text-gray-400"></i>
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $doc->label ?? $doc->type }}</span>
                                        <span class="text-xs text-gray-400 ml-2">{{ ucfirst($doc->type) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    @include('admin.partials._badge', ['type' => $doc->status === 'complete' ? 'success' : ($doc->status === 'in_review' ? 'warning' : 'gray'), 'slot' => $doc->status === 'complete' ? 'Completo' : ($doc->status === 'in_review' ? 'En revisión' : 'Pendiente')])
                                    @if($doc->file_path)
                                        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection