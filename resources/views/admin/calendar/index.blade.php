@extends('layouts.admin')

@section('breadcrumbs', 'Viajes / Calendario')

@section('title', 'Calendario de Salidas')

@push('styles')
<style>
    .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #e5e7eb; border-radius: 0.5rem; overflow: hidden; }
    .calendar-header { background: #f9fafb; padding: 0.75rem; text-align: center; font-size: 0.8rem; font-weight: 600; color: #374151; }
    .calendar-day { background: #fff; min-height: 100px; padding: 0.5rem; position: relative; }
    .calendar-day.other-month { background: #f9fafb; }
    .calendar-day.today { background: #eff6ff; }
    .calendar-day .day-number { font-size: 0.8rem; font-weight: 600; color: #6b7280; margin-bottom: 0.25rem; }
    .calendar-day.today .day-number { color: #2563eb; }
    .calendar-event { padding: 0.2rem 0.4rem; margin-bottom: 0.2rem; border-radius: 0.25rem; font-size: 0.7rem; color: #fff; cursor: pointer; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; transition: transform 0.1s; }
    .calendar-event:hover { transform: scale(1.05); opacity: 0.9; }
    .calendar-event.overlap { outline: 2px solid #ef4444; outline-offset: 1px; }
    .calendar-event .event-tooltip { display: none; position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%); background: #1f2937; color: #fff; padding: 0.5rem 0.75rem; border-radius: 0.375rem; font-size: 0.75rem; white-space: nowrap; z-index: 50; pointer-events: none; }
    .calendar-event:hover .event-tooltip { display: block; }
    .overlap-badge { display: inline-flex; align-items: center; gap: 0.25rem; padding: 0.25rem 0.5rem; background: #fef2f2; color: #ef4444; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 500; }
</style>
@endpush

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Calendario de Salidas</h2>
            <span id="overlapBadge" class="overlap-badge hidden"><i class="fas fa-exclamation-triangle"></i> <span id="overlapCount">0</span> solapamiento(s)</span>
        </div>
        <div class="flex items-center gap-3">
            <button id="prevMonth" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-chevron-left"></i>
            </button>
            <select id="monthSelect" class="text-sm border-gray-300 rounded-lg"></select>
            <select id="yearSelect" class="text-sm border-gray-300 rounded-lg"></select>
            <button id="nextMonth" class="px-3 py-1.5 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>

    <div class="p-6">
        <div id="calendarContainer">
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-spinner fa-spin text-2xl"></i>
                <p class="mt-2 text-sm">Cargando calendario...</p>
            </div>
        </div>
    </div>
</div>

{{-- Legend --}}
<div class="mt-4 flex items-center gap-6 text-sm text-gray-600">
    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded" style="background:#3b82f6"></span> En Venta</span>
    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded" style="background:#10b981"></span> Activo</span>
    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded" style="background:#6b7280"></span> Completado</span>
    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded" style="background:#ef4444"></span> Cancelado</span>
    <span class="flex items-center gap-2"><span class="w-3 h-3 rounded" style="background:#d1d5db"></span> Inactivo</span>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarContainer = document.getElementById('calendarContainer');
    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');
    const overlapBadge = document.getElementById('overlapBadge');
    const overlapCount = document.getElementById('overlapCount');

    const months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
    const dayNames = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'];

    let currentDate = new Date();

    months.forEach((m, i) => {
        const opt = document.createElement('option');
        opt.value = i + 1;
        opt.textContent = m;
        monthSelect.appendChild(opt);
    });

    for (let y = currentDate.getFullYear() - 5; y <= currentDate.getFullYear() + 5; y++) {
        const opt = document.createElement('option');
        opt.value = y;
        opt.textContent = y;
        yearSelect.appendChild(opt);
    }

    monthSelect.value = currentDate.getMonth() + 1;
    yearSelect.value = currentDate.getFullYear();

    function loadCalendar() {
        const month = monthSelect.value;
        const year = yearSelect.value;

        calendarContainer.innerHTML = '<div class="text-center py-12 text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2 text-sm">Cargando calendario...</p></div>';

        fetch(`{{ route('admin.calendar.data') }}?month=${month}&year=${year}`)
            .then(r => r.json())
            .then(data => {
                renderCalendar(data);
            });
    }

    function renderCalendar(data) {
        const month = data.month;
        const year = data.year;
        const events = data.events;
        const overlapIds = data.overlap_ids || [];

        // Show overlap badge
        if (overlapIds.length > 0) {
            overlapBadge.classList.remove('hidden');
            overlapCount.textContent = overlapIds.length;
        } else {
            overlapBadge.classList.add('hidden');
        }

        const firstDay = new Date(year, month - 1, 1);
        const lastDay = new Date(year, month, 0);
        const startDay = firstDay.getDay();
        const daysInMonth = lastDay.getDate();
        const daysInPrev = new Date(year, month - 1, 0).getDate();

        const today = new Date();
        const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

        let html = '<div class="calendar-grid">';

        // Day names header
        dayNames.forEach(d => {
            html += `<div class="calendar-header">${d}</div>`;
        });

        // Previous month days
        for (let i = startDay - 1; i >= 0; i--) {
            html += `<div class="calendar-day other-month"><div class="day-number">${daysInPrev - i}</div></div>`;
        }

        // Current month days
        for (let d = 1; d <= daysInMonth; d++) {
            const dateStr = `${year}-${String(month).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
            const isToday = dateStr === todayStr;
            const dayEvents = events.filter(e => {
                const eStart = e.start;
                const eEnd = e.end;
                return dateStr >= eStart && dateStr < eEnd;
            });

            html += `<div class="calendar-day ${isToday ? 'today' : ''}">`;
            html += `<div class="day-number">${d}</div>`;

            dayEvents.forEach(e => {
                const isOverlap = overlapIds.includes(e.id);
                html += `<div class="calendar-event ${isOverlap ? 'overlap' : ''}" style="background:${e.color}" onclick="window.location.href='${e.url}'">
                    <div class="event-tooltip">
                        <strong>${e.title}</strong><br>
                        Ref: ${e.reference}<br>
                        Plazas: ${e.spots}<br>
                        Estado: ${e.status}
                    </div>
                    ${e.reference}
                </div>`;
            });

            html += '</div>';
        }

        // Next month days
        const remainingCells = 42 - (startDay + daysInMonth);
        for (let i = 1; i <= remainingCells; i++) {
            html += `<div class="calendar-day other-month"><div class="day-number">${i}</div></div>`;
        }

        html += '</div>';
        calendarContainer.innerHTML = html;
    }

    monthSelect.addEventListener('change', loadCalendar);
    yearSelect.addEventListener('change', loadCalendar);
    prevBtn.addEventListener('click', function () {
        let m = parseInt(monthSelect.value) - 1;
        let y = parseInt(yearSelect.value);
        if (m < 1) { m = 12; y--; }
        monthSelect.value = m;
        yearSelect.value = y;
        loadCalendar();
    });
    nextBtn.addEventListener('click', function () {
        let m = parseInt(monthSelect.value) + 1;
        let y = parseInt(yearSelect.value);
        if (m > 12) { m = 1; y++; }
        monthSelect.value = m;
        yearSelect.value = y;
        loadCalendar();
    });

    loadCalendar();
});
</script>
@endpush
