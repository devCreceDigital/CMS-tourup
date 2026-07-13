@extends('layouts.admin')

@section('title', 'CRM - Pipeline de Ventas')
@section('page-title', 'Pipeline de Ventas')

@section('content')
<div class="space-y-6">
    {{-- Metrics cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Total Clientes</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $metrics['total_customers'] }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Tasa Conversión</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $metrics['conversion_rate'] }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Clientes Recurrentes</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $metrics['recurring_rate'] }}%</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
            <p class="text-sm text-gray-500 font-medium">Tiempo Respuesta</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $metrics['avg_response_time'] }}</p>
        </div>
    </div>

    {{-- Kanban Board --}}
    <div class="flex gap-4 overflow-x-auto pb-4" style="min-height: 400px;">
        @foreach($stages as $key => $label)
        <div class="flex-shrink-0 w-72 bg-gray-50 rounded-xl p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-gray-700 text-sm">{{ $label }}</h3>
                <span class="bg-gray-200 text-gray-600 text-xs font-bold px-2 py-0.5 rounded-full">
                    {{ count($kanbanData[$key]['customers'] ?? []) }}
                </span>
            </div>

            <div class="space-y-3" data-stage="{{ $key }}">
                @forelse(($kanbanData[$key]['customers'] ?? []) as $customer)
                    @include('panel.crm.customer-card', ['customer' => $customer])
                @empty
                    <p class="text-gray-400 text-sm text-center py-8">Sin clientes</p>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('[draggable="true"]');
    const columns = document.querySelectorAll('[data-stage]');
    let draggedCard = null;

    cards.forEach(card => {
        card.addEventListener('dragstart', function(e) {
            draggedCard = this;
            this.classList.add('opacity-50');
            e.dataTransfer.effectAllowed = 'move';
        });

        card.addEventListener('dragend', function(e) {
            this.classList.remove('opacity-50');
            draggedCard = null;
        });
    });

    columns.forEach(col => {
        col.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('bg-green-50');
        });

        col.addEventListener('dragleave', function(e) {
            this.classList.remove('bg-green-50');
        });

        col.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('bg-green-50');

            if (!draggedCard) return;

            const stage = this.dataset.stage;
            const customerId = draggedCard.dataset.customerId;

            const emptyMsg = this.querySelector('.text-gray-400');
            if (emptyMsg) emptyMsg.remove();

            this.appendChild(draggedCard);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ url("/panel-agencia/crm/cliente") }}/' + customerId + '/etapa-ajax';
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            const stageInput = document.createElement('input');
            stageInput.type = 'hidden';
            stageInput.name = 'stage';
            stageInput.value = stage;
            form.appendChild(csrf);
            form.appendChild(stageInput);
            document.body.appendChild(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: new URLSearchParams(new FormData(form))
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    updateCounts();
                }
            }).finally(() => form.remove());
        });
    });

    function updateCounts() {
        document.querySelectorAll('[data-stage]').forEach(col => {
            const count = col.querySelectorAll('[draggable="true"]').length;
            const badge = col.closest('.flex-shrink-0').querySelector('.bg-gray-200');
            if (badge) badge.textContent = count;
        });
    }
});
</script>
@endpush
