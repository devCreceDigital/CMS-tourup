@extends('layouts.admin')

@section('title', 'Tarifas - ' . $trip->name)
@section('breadcrumbs', 'Viajes / ' . $trip->name . ' / Tarifas')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.trips.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Viajes
        </a>
    </div>

    @include('admin.trips._tabs')

    <div class="space-y-6">
        @if(session('success'))
        <div class="px-4 py-3 rounded-lg bg-green-100 border border-green-200 text-green-800 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-900 mb-4">Nuevo Grupo de Tarifa</h2>
            <form action="{{ route('admin.trips.pricing.groups.store', $trip) }}" method="POST" class="flex items-end gap-4">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del grupo</label>
                    <input type="text" name="name" required placeholder="Ej: General, Infantil, Mayores"
                        class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-2.5 rounded-lg transition text-sm">
                    <i class="fas fa-plus mr-2"></i>Añadir Grupo
                </button>
            </form>
        </div>

        @forelse($groups as $group)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <h3 class="text-base font-semibold text-gray-900">{{ $group->name }}</h3>
                    @if($group->is_active)
                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">Activo</span>
                    @else
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full font-medium">Inactivo</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.trips.pricing.groups.update', $group) }}" method="POST" class="inline">
                        @csrf @method('PUT')
                        <input type="hidden" name="is_active" value="{{ $group->is_active ? 0 : 1 }}">
                        <button type="submit" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="{{ $group->is_active ? 'Desactivar' : 'Activar' }}">
                            <i class="fas {{ $group->is_active ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.trips.pricing.groups.destroy', $group) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar este grupo de tarifa?" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-medium text-gray-700 mb-3">Plazos de Pago</h4>
                @if($group->installments->count() > 0)
                <div class="overflow-x-auto mb-4">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b">
                                <th class="text-left py-2 px-3 font-medium text-gray-600">Concepto</th>
                                <th class="text-left py-2 px-3 font-medium text-gray-600">Fecha</th>
                                <th class="text-right py-2 px-3 font-medium text-gray-600">Importe</th>
                                <th class="text-right py-2 px-3 font-medium text-gray-600">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($group->installments as $installment)
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-3">{{ $installment->name }}</td>
                                <td class="py-2 px-3">{{ $installment->due_date->format('d/m/Y') }}</td>
                                <td class="py-2 px-3 text-right font-medium">{{ number_format($installment->amount, 2) }} €</td>
                                <td class="py-2 px-3 text-right">
                                    <form action="{{ route('admin.trips.pricing.installments.destroy', $installment) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" data-confirm="¿Eliminar este plazo?"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-sm text-gray-500 mb-4">No hay plazos de pago definidos.</p>
                @endif

                <form action="{{ route('admin.trips.pricing.installments.store', $group) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Concepto</label>
                        <input type="text" name="name" required placeholder="Ej: Reserva"
                            class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Fecha</label>
                        <input type="date" name="due_date" required
                            class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Importe (€)</label>
                        <input type="number" step="0.01" min="0" name="amount" required placeholder="0.00"
                            class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg transition text-sm">
                        <i class="fas fa-plus mr-1"></i>Añadir
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
            <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-tag"></i></div>
            <p class="text-gray-500 text-base">No hay grupos de tarifa creados.</p>
            <p class="text-gray-400 text-sm mt-1">Crea tu primer grupo de tarifa para definir los plazos de pago.</p>
        </div>
        @endforelse
    </div>
@endsection
