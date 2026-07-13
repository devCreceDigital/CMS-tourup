@extends('layouts.admin')

@section('breadcrumbs', 'Dashboard')

@section('title', 'Dashboard')

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @include('admin.partials._stat-card', ['icon' => 'map', 'label' => 'Viajes Activos', 'value' => $activeTrips ?? 0, 'iconBg' => 'bg-green-100', 'iconColor' => 'text-green-600'])
        @include('admin.partials._stat-card', ['icon' => 'flight_takeoff', 'label' => 'Próximas Salidas', 'value' => $upcomingTrips ?? 0, 'iconBg' => 'bg-blue-100', 'iconColor' => 'text-blue-600'])
        @include('admin.partials._stat-card', ['icon' => 'groups', 'label' => 'Total Viajeros', 'value' => $totalTravelers ?? 0, 'iconBg' => 'bg-purple-100', 'iconColor' => 'text-purple-600'])
        @include('admin.partials._stat-card', ['icon' => 'payments', 'label' => 'Recaudación Total', 'value' => '$' . number_format($totalRevenue ?? 0, 2), 'subtext' => 'Pendiente: $' . number_format($pendingPayments ?? 0, 2), 'subtextColor' => 'text-amber-600', 'iconBg' => 'bg-amber-100', 'iconColor' => 'text-amber-600'])
    </div>

    {{-- Trips Section --}}
    <div class="bg-surface-container-lowest rounded-xl card-shadow border border-outline-variant">
        <div class="px-6 py-5 border-b border-outline-variant flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-bold text-on-background">Listado de Viajes</h2>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.dashboard.export') }}?{{ request()->getQueryString() }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant rounded-lg text-sm font-medium text-secondary hover:bg-surface transition-colors">
                    <span class="material-symbols-outlined text-lg">download</span>
                    Exportar CSV
                </a>
                <a href="{{ route('admin.trips.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Nuevo Viaje
                </a>
            </div>
        </div>

        {{-- Filters Bar --}}
        <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex flex-col sm:flex-row gap-3">
                <select name="year" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                    <option value="">Todos los años</option>
                    @foreach (range(date('Y'), date('Y') + 2) as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>

                <select name="status" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">
                    <option value="">Todos los estados</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                    <option value="on_sale" {{ request('status') == 'on_sale' ? 'selected' : '' }}>En Venta</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completado</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                </select>

                <input type="text" name="search" placeholder="Buscar viaje..." value="{{ request('search') }}" class="text-sm border-outline-variant rounded-lg focus:border-primary focus:ring-primary">

                <button type="submit" class="px-4 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm font-medium text-secondary hover:bg-surface transition-colors">
                    <span class="material-symbols-outlined text-lg">filter_alt</span>
                    Filtrar
                </button>
            </form>
        </div>

        {{-- Trip Cards --}}
        <div class="p-6">
            @if (isset($trips) && $trips->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($trips as $trip)
                        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden hover:shadow-xl transition-all duration-300 group">
                            <div class="h-40 bg-surface-container relative overflow-hidden">
                                @if ($trip->image)
                                    <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-outline">
                                        <span class="material-symbols-outlined text-5xl">image</span>
                                    </div>
                                @endif
                                <span class="absolute top-3 left-3 px-3 py-1 bg-surface-container-lowest/90 backdrop-blur-sm rounded-full text-[10px] font-bold uppercase tracking-widest text-primary shadow-sm">
                                    {{ $trip->reference }}
                                </span>
                                <span class="absolute top-3 right-3 px-2 py-1 text-[10px] font-bold rounded-full uppercase
                                    @if($trip->status == 'active') bg-green-100 text-green-700
                                    @elseif($trip->status == 'completed') bg-gray-100 text-gray-600
                                    @elseif($trip->status == 'on_sale') bg-blue-100 text-blue-700
                                    @elseif($trip->status == 'cancelled') bg-rose-100 text-rose-700
                                    @else bg-gray-100 text-gray-500 @endif">
                                    @switch($trip->status)
                                        @case('active') Activo @break
                                        @case('on_sale') En Venta @break
                                        @case('completed') Completado @break
                                        @case('cancelled') Cancelado @break
                                        @default {{ ucfirst($trip->status) }}
                                    @endswitch
                                </span>
                            </div>

                            <div class="p-5">
                                <h3 class="font-bold text-on-background mb-1">{{ $trip->name }}</h3>
                                <div class="flex items-center gap-3 text-sm text-secondary mb-4">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">calendar_month</span>
                                        {{ \Carbon\Carbon::parse($trip->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($trip->end_date)->format('d/m/Y') }}
                                    </span>
                                </div>

                                <div class="space-y-3 mb-4">
                                    <div>
                                        <div class="flex justify-between text-xs mb-1">
                                            <span class="font-semibold text-secondary">Plazas</span>
                                            <span class="font-bold text-on-background">{{ $trip->occupied_spots ?? 0 }}/{{ $trip->total_spots }}</span>
                                        </div>
                                        <div class="h-1.5 w-full bg-surface-container rounded-full overflow-hidden">
                                            <div class="h-full bg-primary" style="width: {{ $trip->total_spots > 0 ? min(($trip->occupied_spots ?? 0) / $trip->total_spots * 100, 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-outline-variant">
                                    @php $firstGroup = $trip->pricingGroups->first(); @endphp
                                    <span class="text-xl font-bold text-on-background">
                                        @if($firstGroup && $firstGroup->installments->isNotEmpty())
                                            ${{ number_format($firstGroup->installments->first()->amount, 2) }}
                                        @else
                                            <span class="text-base font-normal text-outline">--</span>
                                        @endif
                                    </span>
                                    <a href="{{ route('admin.trips.edit', $trip->id) }}" class="px-4 py-2 bg-on-background text-surface text-sm font-bold rounded-lg hover:opacity-90 transition-opacity">
                                        Configurar
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-outline text-5xl mb-4">
                        <span class="material-symbols-outlined text-6xl">explore</span>
                    </div>
                    <h3 class="text-lg font-medium text-secondary mb-2">No hay viajes registrados</h3>
                    <p class="text-sm text-outline mb-4">Comienza creando tu primer viaje para que aparezca aquí.</p>
                    <a href="{{ route('admin.trips.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-container text-white text-sm font-medium rounded-lg hover:opacity-90 transition-all shadow-sm active:scale-95">
                        <span class="material-symbols-outlined text-lg">add</span>
                        Crear Viaje
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
