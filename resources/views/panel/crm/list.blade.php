@extends('layouts.admin')

@section('title', 'Listado de Clientes')
@section('page-title', 'Clientes')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
        <div class="relative">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Buscar clientes..." class="pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-200 text-sm w-64">
        </div>
        <span class="text-sm text-gray-500">{{ $customers->total() }} clientes</span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="py-3 px-4 font-semibold text-gray-600">Nombre</th>
                    <th class="py-3 px-4 font-semibold text-gray-600">Email</th>
                    <th class="py-3 px-4 font-semibold text-gray-600">Teléfono</th>
                    <th class="py-3 px-4 font-semibold text-gray-600">Etapa</th>
                    <th class="py-3 px-4 font-semibold text-gray-600">Último Contacto</th>
                    <th class="py-3 px-4 font-semibold text-gray-600">Viajes</th>
                    <th class="py-3 px-4 font-semibold text-gray-600"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium text-gray-900">{{ $customer->name }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $customer->email ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-600">{{ $customer->phone ?? '—' }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">
                            {{ App\Models\Customer::STAGES[$customer->stage] ?? $customer->stage }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-gray-500">{{ $customer->last_contact_at?->diffForHumans() ?? '—' }}</td>
                    <td class="py-3 px-4 text-gray-500">{{ $customer->total_trips_count }}</td>
                    <td class="py-3 px-4">
                        <a href="{{ url('/panel-agencia/crm/cliente/' . $customer->id) }}" class="text-green-600 hover:text-green-700 font-medium text-sm">
                            Ver <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 text-center text-gray-400">No hay clientes registrados</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($customers->hasPages())
    <div class="p-4 border-t border-gray-100">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
