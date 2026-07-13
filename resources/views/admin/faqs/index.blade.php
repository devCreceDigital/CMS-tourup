@extends('layouts.admin')

@section('breadcrumbs', 'Contenido / FAQ')

@section('title', 'Preguntas Frecuentes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">FAQ</h1>
    <button onclick="openFaqModal()" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Nueva FAQ
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    @if($faqs->count())
    <div id="faq-list" class="divide-y divide-gray-100">
        @foreach($faqs as $faq)
        <div class="p-6 faq-item" data-id="{{ $faq->id }}">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400 font-mono">#{{ $faq->order }}</span>
                        <h3 class="text-sm font-medium text-gray-900">{{ $faq->question }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $faq->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $faq->is_active ? 'Activa' : 'Inactiva' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit(strip_tags($faq->answer), 150) }}</p>
                </div>
                <div class="flex items-center gap-1 shrink-0">
                    <button class="edit-faq-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                        data-id="{{ $faq->id }}"
                        data-question="{{ $faq->question }}"
                        data-answer="{{ $faq->answer }}"
                        data-active="{{ $faq->is_active ? '1' : '0' }}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar esta FAQ?">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    <button class="drag-handle p-2 text-gray-400 hover:text-gray-600 cursor-move">
                        <i class="fas fa-grip-vertical"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $faqs->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-question-circle"></i></div>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No hay preguntas frecuentes</h3>
        <p class="text-sm text-gray-400 mb-4">Añade preguntas frecuentes para ayudar a tus clientes.</p>
        <button onclick="openFaqModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus"></i> Crear FAQ
        </button>
    </div>
    @endif
</div>

{{-- FAQ Modal --}}
<div id="faqModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl relative z-10">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900" id="faqModalTitle">Nueva FAQ</h3>
                <button id="faqCloseModal" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            <form id="faqForm" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <input type="hidden" name="_method" id="faqFormMethod" value="POST">
                    <div>
                        <label for="faqQuestion" class="block text-sm font-medium text-gray-700 mb-1">Pregunta</label>
                        <input type="text" name="question" id="faqQuestion" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="faqAnswer" class="block text-sm font-medium text-gray-700 mb-1">Respuesta</label>
                        <textarea name="answer" id="faqAnswer" rows="6" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Activa</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    <button type="button" id="faqCancelBtn" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openFaqModal(title, method, action, data) {
    const modal = document.getElementById('faqModal');
    document.getElementById('faqModalTitle').textContent = title || 'Nueva FAQ';
    document.getElementById('faqFormMethod').value = method || 'POST';
    document.getElementById('faqForm').action = action || '{{ route("admin.faqs.store") }}';
    document.getElementById('faqQuestion').value = data?.question || '';
    document.getElementById('faqAnswer').value = data?.answer || '';
    document.querySelector('#faqForm [name="is_active"]').checked = data?.active !== false;
    modal.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-faq-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            openFaqModal('Editar FAQ', 'PUT', '/panel-agencia/faqs/' + this.dataset.id, {
                question: this.dataset.question,
                answer: this.dataset.answer,
                active: this.dataset.active === '1',
            });
        });
    });

    const close = () => document.getElementById('faqModal').classList.add('hidden');
    document.getElementById('faqCloseModal')?.addEventListener('click', close);
    document.getElementById('faqCancelBtn')?.addEventListener('click', close);
    document.getElementById('faqModal')?.addEventListener('click', function (e) { if (e.target === this) close(); });

    // Drag & Drop reorder
    const list = document.getElementById('faq-list');
    if (!list) return;

    let dragItem = null;
    let touchId = null;

    list.querySelectorAll('.faq-item').forEach(item => {
        item.setAttribute('draggable', 'true');
        const handle = item.querySelector('.drag-handle');
        if (handle) {
            handle.addEventListener('mousedown', function () {
                item.setAttribute('draggable', 'true');
            });
            handle.addEventListener('touchstart', function (e) {
                item.setAttribute('draggable', 'true');
            }, { passive: true });
        }
    });

    list.addEventListener('dragstart', function (e) {
        const item = e.target.closest('.faq-item');
        if (!item) return;
        dragItem = item;
        item.classList.add('opacity-50', 'bg-blue-50');
        e.dataTransfer.effectAllowed = 'move';
    });

    list.addEventListener('dragend', function (e) {
        const item = e.target.closest('.faq-item');
        if (item) {
            item.classList.remove('opacity-50', 'bg-blue-50');
            item.setAttribute('draggable', 'true');
        }
        dragItem = null;
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('border-t-2', 'border-blue-400'));
    });

    list.addEventListener('dragover', function (e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        const target = e.target.closest('.faq-item');
        if (!target || target === dragItem) return;
        const rect = target.getBoundingClientRect();
        const mid = rect.top + rect.height / 2;
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('border-t-2', 'border-blue-400'));
        if (e.clientY < mid) {
            target.classList.add('border-t-2', 'border-blue-400');
        } else {
            target.nextElementSibling?.classList.add('border-t-2', 'border-blue-400');
        }
    });

    list.addEventListener('drop', function (e) {
        e.preventDefault();
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('border-t-2', 'border-blue-400'));
        if (!dragItem) return;
        const target = e.target.closest('.faq-item');
        if (!target || target === dragItem) return;
        const rect = target.getBoundingClientRect();
        const mid = rect.top + rect.height / 2;
        if (e.clientY < mid) {
            target.parentNode.insertBefore(dragItem, target);
        } else {
            target.parentNode.insertBefore(dragItem, target.nextSibling);
        }
        updateFaqOrder();
    });

    // Touch DnD support
    let touchDragItem = null;
    let touchGhost = null;

    list.addEventListener('touchstart', function (e) {
        const handle = e.target.closest('.drag-handle');
        if (!handle) return;
        const item = handle.closest('.faq-item');
        if (!item) return;
        touchDragItem = item;
        touchId = e.changedTouches[0].identifier;
        item.classList.add('opacity-50', 'bg-blue-50');
        e.preventDefault();
    }, { passive: false });

    list.addEventListener('touchmove', function (e) {
        if (!touchDragItem) return;
        e.preventDefault();
        const touch = Array.from(e.changedTouches).find(t => t.identifier === touchId) || e.changedTouches[0];
        const target = document.elementFromPoint(touch.clientX, touch.clientY);
        const dropTarget = target?.closest('.faq-item');
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('border-t-2', 'border-blue-400'));
        if (dropTarget && dropTarget !== touchDragItem) {
            const rect = dropTarget.getBoundingClientRect();
            const mid = rect.top + rect.height / 2;
            if (touch.clientY < mid) {
                dropTarget.classList.add('border-t-2', 'border-blue-400');
            } else {
                dropTarget.nextElementSibling?.classList.add('border-t-2', 'border-blue-400');
            }
        }
    }, { passive: false });

    list.addEventListener('touchend', function (e) {
        if (!touchDragItem) return;
        e.preventDefault();
        const touch = Array.from(e.changedTouches).find(t => t.identifier === touchId) || e.changedTouches[0];
        const target = document.elementFromPoint(touch.clientX, touch.clientY);
        const dropTarget = target?.closest('.faq-item');
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('border-t-2', 'border-blue-400'));
        touchDragItem.classList.remove('opacity-50', 'bg-blue-50');
        if (dropTarget && dropTarget !== touchDragItem) {
            const rect = dropTarget.getBoundingClientRect();
            const mid = rect.top + rect.height / 2;
            if (touch.clientY < mid) {
                dropTarget.parentNode.insertBefore(touchDragItem, dropTarget);
            } else {
                dropTarget.parentNode.insertBefore(touchDragItem, dropTarget.nextSibling);
            }
            updateFaqOrder();
        }
        touchDragItem = null;
        touchId = null;
    }, { passive: false });

    function updateFaqOrder() {
        const items = [];
        list.querySelectorAll('.faq-item').forEach((el, index) => {
            items.push({ id: el.dataset.id, order: index + 1 });
            el.querySelector('.text-gray-400.font-mono').textContent = '#' + (index + 1);
        });
        fetch('{{ route("admin.faqs.reorder") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ items }),
        }).catch(() => {});
    }
});
</script>
@endpush
