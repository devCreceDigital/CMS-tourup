@extends('layouts.admin')

@section('breadcrumbs', 'Viajes / ' . $trip->name . ' / Transporte')

@section('title', 'Transporte - ' . $trip->name)

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.trips.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Volver a Viajes
        </a>
    </div>

    @include('admin.trips._tabs')

    <div class="space-y-6">
        {{-- Add Bus Button --}}
        <div class="flex justify-end">
            <button onclick="openBusModal()" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i> Añadir Bus
            </button>
        </div>

        @if($trip->buses->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 text-center py-16">
                <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-bus"></i></div>
                <h3 class="text-lg font-medium text-gray-500 mb-2">No hay buses asignados</h3>
                <p class="text-sm text-gray-400 mb-4">Añade autobuses para gestionar los asientos y la asignación de viajeros.</p>
                <button onclick="openBusModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus"></i> Añadir Bus
                </button>
            </div>
        @else
            @foreach($trip->buses as $bus)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200">
                        <div>
                            <h3 class="text-base font-semibold text-gray-900">{{ $bus->name }}</h3>
                            <p class="text-xs text-gray-500">{{ $bus->rows }} filas × {{ $bus->columns }} columnas — {{ $bus->total_seats }} asientos totales</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button onclick="editBus({{ $bus->id }}, '{{ addslashes($bus->name) }}', {{ $bus->rows }}, {{ $bus->columns }})" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.trips.transport.destroy', $bus) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar este bus? Se eliminarán todos sus asientos.">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="p-6">
                        @php
                            $seatsByRow = $bus->seats->groupBy('row')->sortKeys();
                            $occupied = $bus->seats->where('is_occupied', true)->count();
                            $free = $bus->total_seats - $occupied;
                        @endphp

                        {{-- Stats --}}
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 rounded-lg p-3 text-center">
                                <span class="text-2xl font-bold text-blue-600">{{ $bus->total_seats }}</span>
                                <p class="text-xs text-gray-500">Plazas Totales</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-3 text-center">
                                <span class="text-2xl font-bold text-green-600">{{ $free }}</span>
                                <p class="text-xs text-gray-500">Libres</p>
                            </div>
                            <div class="bg-red-50 rounded-lg p-3 text-center">
                                <span class="text-2xl font-bold text-red-600">{{ $occupied }}</span>
                                <p class="text-xs text-gray-500">Ocupadas</p>
                            </div>
                        </div>

                        {{-- Seat Map --}}
                        <div class="overflow-x-auto">
                            <table class="mx-auto">
                                @foreach($seatsByRow as $rowNum => $rowSeats)
                                    <tr>
                                        @foreach($rowSeats as $seat)
                                            <td class="p-1">
                                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-xs font-medium
                                                    {{ $seat->is_occupied ? 'bg-red-500 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200' }}">
                                                    {{ $seat->seat_number }}
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Bus Modal --}}
    <div id="busModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative z-10">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900" id="busModalTitle">Nuevo Bus</h3>
                    <button onclick="closeBusModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>
                <form id="busForm" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <input type="hidden" name="_method" id="busFormMethod" value="POST">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input type="text" name="name" id="busName" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Filas</label>
                                <input type="number" name="rows" id="busRows" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required min="1" max="50">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Columnas</label>
                                <input type="number" name="columns" id="busColumns" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required min="1" max="10">
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                        <button type="button" onclick="closeBusModal()" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function openBusModal(title, method, action, data) {
    document.getElementById('busModalTitle').textContent = title || 'Nuevo Bus';
    document.getElementById('busFormMethod').value = method || 'POST';
    document.getElementById('busForm').action = action || '{{ route("admin.trips.transport.store", $trip) }}';
    document.getElementById('busName').value = data?.name || '';
    document.getElementById('busRows').value = data?.rows || '';
    document.getElementById('busColumns').value = data?.columns || '';
    document.getElementById('busModal').classList.remove('hidden');
}
function closeBusModal() {
    document.getElementById('busModal').classList.add('hidden');
}
function editBus(id, name, rows, columns) {
    openBusModal('Editar Bus', 'PUT', '/panel-agencia/transport/' + id, { name, rows, columns });
}
document.querySelectorAll('[onclick="openBusModal()"]').forEach(el => {
    if (!el.hasClickListener) {
        el.addEventListener('click', () => openBusModal());
        el.hasClickListener = true;
    }
});
document.getElementById('busModal')?.addEventListener('click', function(e) { if (e.target === this) closeBusModal(); });
</script>
@endpush
