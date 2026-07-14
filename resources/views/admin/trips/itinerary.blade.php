@extends('layouts.admin')

@section('breadcrumbs', 'Viajes / ' . $trip->name . ' / Itinerario')

@section('title', 'Itinerario - ' . $trip->name)

@push('styles')
<style>
.timeline-connector::before {
    content: '';
    position: absolute;
    left: 19px;
    top: 40px;
    bottom: 0;
    width: 2px;
    background-color: #c3c6d7;
}
.timeline-connector-last::before {
    display: none;
}
.active-day-indicator {
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background-color: #004ac6;
    border-radius: 0 4px 4px 0;
}
.hide-scrollbar::-webkit-scrollbar { display: none; }
</style>
@endpush

@section('content')
    @include('admin.trips._tabs')

    <div class="flex items-end justify-between mb-8">
        <div>
            <h3 class="text-xl font-bold text-on-background">Creador de Itinerario</h3>
            <p class="text-on-surface-variant text-sm">Organiza el cronograma detallado de actividades para este viaje.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        {{-- Left Column: Day Navigation --}}
        <div class="lg:col-span-3 lg:sticky lg:top-32">
            <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
                <div class="p-4 border-b border-outline-variant bg-surface-container-low flex items-center justify-between">
                    <span class="text-xs font-semibold text-on-surface uppercase tracking-wider">Días del viaje</span>
                    <span class="text-xs bg-primary/10 text-primary px-2 py-0.5 rounded-full font-bold">{{ $trip->itineraryDays->count() }} Días</span>
                </div>
                <div class="p-2 space-y-1 max-h-[420px] overflow-y-auto custom-scrollbar">
                    @forelse($trip->itineraryDays->sortBy('day_number') as $day)
                        <a href="{{ request()->url() }}?day={{ $day->id }}"
                           class="group block w-full text-left p-3 rounded-lg flex items-center gap-3 relative transition-all
                           {{ isset($selectedDay) && $selectedDay->id == $day->id ? 'bg-primary-container/10 text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low' }}">
                            @if(isset($selectedDay) && $selectedDay->id == $day->id)
                                <div class="active-day-indicator"></div>
                            @endif
                            <span class="material-symbols-outlined text-sm">calendar_today</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm truncate">Día {{ $day->day_number }}</p>
                                <p class="text-[10px] opacity-60 truncate">{{ $day->title ?: ($day->date ? \Carbon\Carbon::parse($day->date)->format('d/m/Y') : '') }}</p>
                            </div>
                            <div class="hidden group-hover:flex items-center gap-0.5 shrink-0">
                                <form action="{{ route('admin.trips.itinerary.days.toggle-publish', $day) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-1 text-outline hover:text-primary transition-colors" title="{{ $day->is_published ? 'Publicado' : 'Borrador' }}">
                                        <span class="material-symbols-outlined text-sm">{{ $day->is_published ? 'visibility' : 'visibility_off' }}</span>
                                    </button>
                                </form>
                                <button type="button"
                                    data-day-id="{{ $day->id }}"
                                    data-day-number="{{ $day->day_number }}"
                                    data-day-date="{{ $day->date }}"
                                    data-day-title="{{ $day->title }}"
                                    onclick="editDayFromData(this)"
                                    class="p-1 text-outline hover:text-primary transition-colors" title="Editar">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                                <form action="{{ route('admin.trips.itinerary.days.destroy', $day) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 text-outline hover:text-error transition-colors" data-confirm="¿Eliminar este día?" title="Eliminar">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </a>
                    @empty
                        <p class="text-sm text-on-surface-variant/50 text-center py-8">No hay días registrados</p>
                    @endforelse
                </div>
                <div class="p-2">
                    <button onclick="openDayModal()" class="w-full py-3 flex items-center justify-center gap-2 border-2 border-dashed border-outline-variant rounded-lg text-secondary hover:border-primary hover:text-primary transition-all">
                        <span class="material-symbols-outlined">add</span>
                        <span class="text-sm font-semibold">Añadir día</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Right Column: Timeline Detail --}}
        <div class="lg:col-span-9 space-y-6 pb-24">
            @if(isset($selectedDay))
                {{-- Day Summary Header --}}
                <div class="bg-white border border-outline-variant rounded-xl p-6 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 bg-primary-container text-white rounded-2xl flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-2xl font-bold">{{ $selectedDay->date ? \Carbon\Carbon::parse($selectedDay->date)->format('d') : $selectedDay->day_number }}</span>
                            <span class="text-[10px] uppercase tracking-wider font-bold">{{ $selectedDay->date ? ucfirst(\Carbon\Carbon::parse($selectedDay->date)->locale('es')->isoFormat('MMMM')) : 'DÍA' }}</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-on-background">Día {{ $selectedDay->day_number }}: {{ $selectedDay->title }}</h4>
                            @if($selectedDay->date)
                                <p class="text-on-surface-variant text-sm">{{ \Carbon\Carbon::parse($selectedDay->date)->format('d/m/Y') }}</p>
                            @endif
                        </div>
                    </div>
                    <button type="button"
                        data-day-id="{{ $selectedDay->id }}"
                        data-day-number="{{ $selectedDay->day_number }}"
                        data-day-date="{{ $selectedDay->date }}"
                        data-day-title="{{ $selectedDay->title }}"
                        onclick="editDayFromData(this)"
                        class="p-2 text-outline hover:text-primary transition-colors rounded-lg hover:bg-surface-container">
                        <span class="material-symbols-outlined">edit</span>
                    </button>
                </div>

                {{-- Activities List --}}
                @if($selectedDay->activities->isEmpty())
                    <div class="bg-white border border-outline-variant rounded-xl p-12 shadow-sm">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-surface-container rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl text-outline">calendar_month</span>
                            </div>
                            <p class="text-on-surface-variant font-medium mb-1">No hay actividades para este día</p>
                            <p class="text-on-surface-variant/50 text-sm mb-4">Añade la primera actividad para comenzar.</p>
                            <button onclick="openActivityModal()" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-container transition-all inline-flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm">add</span>
                                Añadir actividad
                            </button>
                        </div>
                    </div>
                @else
                    <div class="space-y-6 relative ml-4">
                        @foreach($selectedDay->activities->sortBy('order') as $activity)
                            <div class="relative pl-12 {{ !$loop->last ? 'timeline-connector' : 'timeline-connector-last' }}">
                                {{-- Activity Type Icon Circle --}}
                                <div class="absolute left-0 top-0 w-10 h-10 bg-white border-2 border-primary rounded-full flex items-center justify-center z-10 shadow-sm text-primary">
                                    <span class="material-symbols-outlined text-lg">
                                        @switch($activity->type)
                                            @case('transport') directions_bus @break
                                            @case('food') restaurant @break
                                            @case('activity') museum @break
                                            @case('accommodation') hotel @break
                                            @default schedule
                                        @endswitch
                                    </span>
                                </div>
                                {{-- Activity Content --}}
                                <div class="pb-8 group">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                @if($activity->time)
                                                    <span class="font-bold text-on-background">{{ $activity->time }} h - {{ $activity->title }}</span>
                                                @else
                                                    <span class="font-bold text-on-background">{{ $activity->title }}</span>
                                                @endif
                                            </div>
                                            @if($activity->description)
                                                <p class="text-on-surface-variant text-sm leading-relaxed mt-1">{{ $activity->description }}</p>
                                            @endif
                                            @if($activity->important_notes)
                                                <div class="mt-4 bg-surface-container-low p-4 rounded-lg border border-outline-variant/20">
                                                    <p class="text-sm text-on-surface-variant">
                                                        <strong class="text-primary">Nota importante:</strong>
                                                        {{ $activity->important_notes }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                        {{-- Activity Actions --}}
                                        <div class="hidden group-hover:flex items-center gap-1 shrink-0">
                                            <button type="button"
                                                data-activity-id="{{ $activity->id }}"
                                                data-activity-time="{{ $activity->time }}"
                                                data-activity-title="{{ $activity->title }}"
                                                data-activity-description="{{ $activity->description }}"
                                                data-activity-type="{{ $activity->type }}"
                                                data-activity-notes="{{ $activity->important_notes }}"
                                                onclick="editActivityFromData(this)"
                                                class="p-1.5 text-outline hover:text-primary transition-colors rounded-lg hover:bg-surface-container">
                                                <span class="material-symbols-outlined text-sm">edit</span>
                                            </button>
                                            <form action="{{ route('admin.trips.itinerary.activities.destroy', $activity) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-outline hover:text-error transition-colors rounded-lg hover:bg-surface-container" data-confirm="¿Eliminar esta actividad?">
                                                    <span class="material-symbols-outlined text-sm">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Add Activity Button --}}
                    <div class="relative ml-4 pl-12">
                        <button onclick="openActivityModal()" class="w-full py-6 flex flex-col items-center justify-center gap-2 border-2 border-dashed border-outline-variant rounded-xl text-secondary hover:border-primary hover:text-primary transition-all hover:bg-white bg-surface-container-low/30">
                            <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center">
                                <span class="material-symbols-outlined">add</span>
                            </div>
                            <span class="font-bold text-sm">Añadir actividad al Día {{ $selectedDay->day_number }}</span>
                            <p class="text-xs opacity-60">Escoge entre Transporte, Comida, Actividad o Alojamiento</p>
                        </button>
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="bg-white border border-outline-variant rounded-xl p-16 shadow-sm">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto mb-5 bg-surface-container rounded-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-outline">route</span>
                        </div>
                        <h3 class="text-lg font-bold text-on-background mb-2">Selecciona un día</h3>
                        <p class="text-on-surface-variant/60 text-sm">Elige un día del panel lateral para ver o añadir actividades.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Day Modal --}}
    <div id="dayModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md relative z-10">
                <div class="flex items-center justify-between p-6 border-b border-outline-variant">
                    <h3 class="text-lg font-bold text-on-background" id="dayModalTitle">Nuevo Día</h3>
                    <button onclick="closeDayModal()" class="p-1 text-outline hover:text-on-background transition-colors rounded-lg hover:bg-surface-container">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="dayForm" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <input type="hidden" name="_method" id="dayFormMethod" value="POST">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Número de Día</label>
                            <input type="number" name="day_number" id="dayNumber" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20" required min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Fecha</label>
                            <input type="date" name="date" id="dayDate" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Título</label>
                            <input type="text" name="title" id="dayTitle" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20" required maxlength="255">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 p-6 border-t border-outline-variant bg-surface-container-low rounded-b-xl">
                        <button type="button" onclick="closeDayModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:text-on-background transition-colors">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-container transition-all" onclick="this.disabled=true; this.classList.add('opacity-50','cursor-not-allowed'); this.form.submit();">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Activity Modal --}}
    <div id="activityModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl relative z-10">
                <div class="flex items-center justify-between p-6 border-b border-outline-variant">
                    <h3 class="text-lg font-bold text-on-background" id="activityModalTitle">Nueva Actividad</h3>
                    <button onclick="closeActivityModal()" class="p-1 text-outline hover:text-on-background transition-colors rounded-lg hover:bg-surface-container">
                        <span class="material-symbols-outlined">close</span>
                    </button>
                </div>
                <form id="activityForm" method="POST">
                    @csrf
                    <div class="p-6 space-y-4">
                        <input type="hidden" name="_method" id="activityFormMethod" value="POST">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-on-surface mb-1">Hora</label>
                                <input type="text" name="time" id="activityTime" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20" placeholder="09:00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-on-surface mb-1">Tipo</label>
                                <select name="type" id="activityType" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20">
                                    <option value="">General</option>
                                    <option value="transport">Transporte</option>
                                    <option value="food">Comida</option>
                                    <option value="activity">Actividad</option>
                                    <option value="accommodation">Alojamiento</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Título</label>
                            <input type="text" name="title" id="activityTitle" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20" required maxlength="255">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Descripción</label>
                            <textarea name="description" id="activityDescription" rows="3" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1">Notas importantes</label>
                            <textarea name="important_notes" id="activityImportantNotes" rows="2" class="w-full border-outline-variant rounded-lg text-sm focus:border-primary focus:ring-primary/20" placeholder="Información crítica para los viajeros..."></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 p-6 border-t border-outline-variant bg-surface-container-low rounded-b-xl">
                        <button type="button" onclick="closeActivityModal()" class="px-4 py-2 text-sm font-medium text-secondary hover:text-on-background transition-colors">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-container transition-all" onclick="this.disabled=true; this.classList.add('opacity-50','cursor-not-allowed'); this.form.submit();">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- FAB --}}
    <button onclick="openActivityModal()" class="fixed bottom-8 right-8 w-14 h-14 bg-primary text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all z-50">
        <span class="material-symbols-outlined text-3xl">add</span>
    </button>
@endsection

@push('scripts')
<script>
var ROUTES = {
    daysStore: '{{ route("admin.trips.itinerary.days.store", $trip) }}',
    daysUpdate: '{{ route("admin.trips.itinerary.days.update", ["day" => "_ID_"]) }}'.replace('_ID_', ''),
    activitiesStore: '{{ isset($selectedDay) ? route("admin.trips.itinerary.activities.store", $selectedDay) : "" }}',
    activitiesUpdate: '{{ route("admin.trips.itinerary.activities.update", ["activity" => "_ID_"]) }}'.replace('_ID_', ''),
};

document.getElementById('dayForm').addEventListener('submit', function(e) {
    var btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
});
document.getElementById('activityForm').addEventListener('submit', function(e) {
    var btn = this.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.classList.add('opacity-50', 'cursor-not-allowed');
});
function openDayModal(title, method, action, data) {
    var btn = document.querySelector('#dayForm button[type="submit"]');
    btn.disabled = false;
    btn.classList.remove('opacity-50', 'cursor-not-allowed');
    document.getElementById('dayModalTitle').textContent = title || 'Nuevo Día';
    document.getElementById('dayFormMethod').value = method || 'POST';
    document.getElementById('dayForm').action = action || ROUTES.daysStore;
    document.getElementById('dayNumber').value = data?.day_number || '';
    document.getElementById('dayDate').value = data?.date || '';
    document.getElementById('dayTitle').value = data?.title || '';
    document.getElementById('dayModal').classList.remove('hidden');
}
function closeDayModal() {
    document.getElementById('dayModal').classList.add('hidden');
}
function editDayFromData(btn) {
    var id = btn.getAttribute('data-day-id');
    openDayModal('Editar Día', 'PUT', ROUTES.daysUpdate + id, {
        day_number: btn.getAttribute('data-day-number'),
        date: btn.getAttribute('data-day-date'),
        title: btn.getAttribute('data-day-title'),
    });
}
document.getElementById('dayModal').addEventListener('click', function(e) { if (e.target === this) closeDayModal(); });

function openActivityModal(title, method, action, data) {
    var btn = document.querySelector('#activityForm button[type="submit"]');
    btn.disabled = false;
    btn.classList.remove('opacity-50', 'cursor-not-allowed');
    document.getElementById('activityModalTitle').textContent = title || 'Nueva Actividad';
    document.getElementById('activityFormMethod').value = method || 'POST';
    document.getElementById('activityForm').action = action || ROUTES.activitiesStore;
    document.getElementById('activityTime').value = data?.time || '';
    document.getElementById('activityType').value = data?.type || '';
    document.getElementById('activityTitle').value = data?.title || '';
    document.getElementById('activityDescription').value = data?.description || '';
    document.getElementById('activityImportantNotes').value = data?.important_notes || '';
    document.getElementById('activityModal').classList.remove('hidden');
}
function closeActivityModal() {
    document.getElementById('activityModal').classList.add('hidden');
}
function editActivityFromData(btn) {
    var id = btn.getAttribute('data-activity-id');
    openActivityModal('Editar Actividad', 'PUT', ROUTES.activitiesUpdate + id, {
        time: btn.getAttribute('data-activity-time'),
        title: btn.getAttribute('data-activity-title'),
        description: btn.getAttribute('data-activity-description'),
        type: btn.getAttribute('data-activity-type'),
        important_notes: btn.getAttribute('data-activity-notes'),
    });
}
document.getElementById('activityModal').addEventListener('click', function(e) { if (e.target === this) closeActivityModal(); });
</script>
@endpush
