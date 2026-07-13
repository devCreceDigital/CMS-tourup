@extends('layouts.admin')

@section('title', 'Ficha de Cliente - ' . $customer->name)
@section('page-title', 'Ficha de Cliente')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Customer Info --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center text-green-600 font-bold text-xl">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $customer->name }}</h2>
                    <p class="text-sm text-gray-500">{{ $stages[$customer->stage] ?? $customer->stage }}</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center space-x-3 text-sm">
                    <i class="fas fa-envelope text-gray-400 w-4"></i>
                    <span>{{ $customer->email ?? '—' }}</span>
                </div>
                <div class="flex items-center space-x-3 text-sm">
                    <i class="fas fa-phone text-gray-400 w-4"></i>
                    <span>{{ $customer->phone ?? '—' }}</span>
                </div>
                <div class="flex items-center space-x-3 text-sm">
                    <i class="fas fa-id-card text-gray-400 w-4"></i>
                    <span>{{ $customer->dni ?? '—' }}</span>
                </div>
                <div class="flex items-center space-x-3 text-sm">
                    <i class="fas fa-map-marker-alt text-gray-400 w-4"></i>
                    <span>{{ $customer->address ?? '—' }}</span>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cambiar Etapa</label>
                <form action="{{ url('/panel-agencia/crm/cliente/' . $customer->id . '/etapa') }}" method="POST" class="flex space-x-2">
                    @csrf
                    <select name="stage" class="flex-1 text-sm rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-200">
                        @foreach($stages as $key => $label)
                        <option value="{{ $key }}" {{ $customer->stage === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-green-700 transition">
                        <i class="fas fa-check"></i>
                    </button>
                </form>
            </div>
        </div>

        {{-- Linked Travelers --}}
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Viajeros Asociados</h3>
            @if($customer->travelers->count() > 0)
            <div class="space-y-2">
                @foreach($customer->travelers as $traveler)
                <div class="flex items-center justify-between text-sm">
                    <span>{{ $traveler->name }}</span>
                    <span class="text-xs text-gray-400">{{ $traveler->dni }}</span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-400">Sin viajeros asociados</p>
            @endif
        </div>
    </div>

    {{-- Interactions --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h3 class="font-semibold text-gray-900 mb-4">Registrar Interacción</h3>
            <form action="{{ url('/panel-agencia/crm/cliente/' . $customer->id . '/interaccion') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <select name="channel" class="rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-200 text-sm" required>
                        <option value="">Canal</option>
                        <option value="email">Email</option>
                        <option value="whatsapp">WhatsApp</option>
                        <option value="llamada">Llamada</option>
                        <option value="nota">Nota interna</option>
                    </select>
                    <select name="type" class="rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-200 text-sm" required>
                        <option value="">Tipo</option>
                        <option value="saliente">Saliente</option>
                        <option value="entrante">Entrante</option>
                    </select>
                </div>
                <textarea name="content" rows="3" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-200 text-sm" placeholder="Contenido de la interacción..." required></textarea>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg text-sm transition">
                    <i class="fas fa-plus mr-2"></i>Registrar
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Historial de Interacciones</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($customer->interactions as $interaction)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-start justify-between mb-1">
                        <div class="flex items-center space-x-2">
                            @if($interaction->channel === 'email')
                                <i class="fas fa-envelope text-blue-500"></i>
                            @elseif($interaction->channel === 'whatsapp')
                                <i class="fab fa-whatsapp text-green-500"></i>
                            @elseif($interaction->channel === 'llamada')
                                <i class="fas fa-phone text-purple-500"></i>
                            @else
                                <i class="fas fa-sticky-note text-gray-500"></i>
                            @endif
                            <span class="font-medium text-sm text-gray-900">{{ ucfirst($interaction->type) }}</span>
                            <span class="text-xs text-gray-400">{{ $interaction->channel }}</span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $interaction->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <p class="text-sm text-gray-600 ml-7">{{ $interaction->content }}</p>
                    @if($interaction->agent)
                    <p class="text-xs text-gray-400 ml-7 mt-1">por {{ $interaction->agent->name }}</p>
                    @endif
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 text-sm">Sin interacciones registradas</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
