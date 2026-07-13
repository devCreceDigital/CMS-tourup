<div class="mb-6 border-b border-outline-variant">
    <nav class="flex gap-6 overflow-x-auto hide-scrollbar" style="-ms-overflow-style:none;scrollbar-width:none">
        <a href="{{ route('admin.trips.show', $trip) }}"
           class="flex items-center gap-1.5 px-1 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
           {{ request()->routeIs('admin.trips.show') ? 'text-primary border-primary' : 'text-secondary border-transparent hover:text-primary hover:border-outline-variant' }}">
            <span class="material-symbols-outlined text-lg">info</span>
            Información
        </a>
        <a href="{{ route('admin.trips.itinerary.index', $trip) }}"
           class="flex items-center gap-1.5 px-1 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
           {{ request()->routeIs('admin.trips.itinerary*') ? 'text-primary border-primary font-semibold' : 'text-secondary border-transparent hover:text-primary hover:border-outline-variant' }}">
            <span class="material-symbols-outlined text-lg">route</span>
            Itinerario
        </a>
        <a href="{{ route('admin.trips.transport.index', $trip) }}"
           class="flex items-center gap-1.5 px-1 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
           {{ request()->routeIs('admin.trips.transport*') ? 'text-primary border-primary' : 'text-secondary border-transparent hover:text-primary hover:border-outline-variant' }}">
            <span class="material-symbols-outlined text-lg">directions_bus</span>
            Transporte
        </a>
        <a href="{{ route('admin.trips.pricing.index', $trip) }}"
           class="flex items-center gap-1.5 px-1 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
           {{ request()->routeIs('admin.trips.pricing*') ? 'text-primary border-primary' : 'text-secondary border-transparent hover:text-primary hover:border-outline-variant' }}">
            <span class="material-symbols-outlined text-lg">sell</span>
            Tarifas
        </a>
        <a href="{{ route('admin.trips.accommodations.index', $trip) }}"
           class="flex items-center gap-1.5 px-1 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
           {{ request()->routeIs('admin.trips.accommodations*') ? 'text-primary border-primary' : 'text-secondary border-transparent hover:text-primary hover:border-outline-variant' }}">
            <span class="material-symbols-outlined text-lg">hotel</span>
            Alojamiento
        </a>
        <a href="{{ route('admin.trips.travelers.index', $trip) }}"
           class="flex items-center gap-1.5 px-1 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
           {{ request()->routeIs('admin.trips.travelers*') ? 'text-primary border-primary' : 'text-secondary border-transparent hover:text-primary hover:border-outline-variant' }}">
            <span class="material-symbols-outlined text-lg">group</span>
            Viajeros
        </a>
        <a href="{{ route('admin.trips.documents.index', $trip) }}"
           class="flex items-center gap-1.5 px-1 py-3 text-sm font-medium whitespace-nowrap border-b-2 transition-colors
           {{ request()->routeIs('admin.trips.documents*') ? 'text-primary border-primary' : 'text-secondary border-transparent hover:text-primary hover:border-outline-variant' }}">
            <span class="material-symbols-outlined text-lg">description</span>
            Documentación
        </a>
    </nav>
</div>
