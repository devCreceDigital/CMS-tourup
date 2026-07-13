@extends('layouts.admin')

@section('title', 'Bandeja de Conversación - ' . $customer->name)
@section('page-title', 'Bandeja de Conversación')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-900">Interacciones con {{ $customer->name }}</h3>
                <p class="text-sm text-gray-500">{{ $customer->email ?? 'Sin email' }} · {{ $customer->phone ?? 'Sin teléfono' }}</p>
            </div>
            <a href="{{ url('/panel-agencia/crm/cliente/' . $customer->id) }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                <i class="fas fa-arrow-left mr-1"></i>Volver a ficha
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($interactions as $interaction)
            <div class="p-4 hover:bg-gray-50">
                <div class="flex items-start justify-between mb-1">
                    <div class="flex items-center space-x-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $interaction->type === 'entrante' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst($interaction->type) }}
                        </span>
                        <span class="text-xs text-gray-400">{{ ucfirst($interaction->channel) }}</span>
                    </div>
                    <span class="text-xs text-gray-400">{{ $interaction->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">{{ $interaction->content }}</p>
            </div>
            @empty
            <div class="p-6 text-center text-gray-400 text-sm">Sin interacciones registradas</div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">
        {{ $interactions->links() }}
    </div>
</div>
@endsection
