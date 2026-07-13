@extends('layouts.admin')

@section('breadcrumbs', 'Blog / Categorías')

@section('title', 'Categorías de Blog')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Categorías de Blog</h1>
    <button class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2" id="openCreateModal">
        <i class="fas fa-plus"></i> Nueva Categoría
    </button>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    @if($categories->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Artículos</th>
                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                        @if($category->description)<p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($category->description, 60) }}</p>@endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $category->slug }}</td>
                    <td class="px-6 py-4 text-center text-sm text-gray-600">{{ $category->posts_count }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                            {{ $category->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button class="edit-category-btn p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                            data-id="{{ $category->id }}"
                            data-name="{{ $category->name }}"
                            data-description="{{ $category->description }}"
                            data-active="{{ $category->is_active }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.blog-categories.destroy', $category) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar esta categoría? Los artículos asociados quedarán sin categoría.">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $categories->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-folder-open"></i></div>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No hay categorías de blog</h3>
        <p class="text-sm text-gray-400 mb-4">Crea tu primera categoría para organizar los artículos.</p>
        <button id="openCreateModalEmpty" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus"></i> Crear Categoría
        </button>
    </div>
    @endif
</div>

{{-- Create/Edit Modal --}}
<div id="categoryModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/50"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg relative z-10">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Nueva Categoría</h3>
                <button id="closeModalBtn" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
            </div>
            <form id="categoryForm" method="POST">
                @csrf
                <div class="p-6 space-y-4">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div>
                        <label for="catName" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="name" id="catName" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="catDescription" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="description" id="catDescription" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-700">Activo</span>
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    <button type="button" id="cancelModalBtn" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const formMethod = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');
    const nameInput = document.getElementById('catName');
    const descInput = document.getElementById('catDescription');
    const activeCheck = document.querySelector('[name="is_active"]');

    function openModal(title, method, action, data) {
        modalTitle.textContent = title;
        formMethod.value = method;
        form.action = action;
        nameInput.value = data?.name || '';
        descInput.value = data?.description || '';
        activeCheck.checked = data?.active !== false;
        modal.classList.remove('hidden');
    }

    function closeModal() { modal.classList.add('hidden'); }

    document.querySelectorAll('#openCreateModal, #openCreateModalEmpty').forEach(el => {
        el?.addEventListener('click', () => openModal('Nueva Categoría', 'POST', '{{ route("admin.blog-categories.store") }}', {}));
    });

    document.querySelectorAll('.edit-category-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            openModal('Editar Categoría', 'PUT', '/panel-agencia/blog-categories/' + id, {
                name: this.dataset.name,
                description: this.dataset.description,
                active: this.dataset.active === '1',
            });
        });
    });

    document.getElementById('closeModalBtn')?.addEventListener('click', closeModal);
    document.getElementById('cancelModalBtn')?.addEventListener('click', closeModal);
    modal?.addEventListener('click', function (e) { if (e.target === this) closeModal(); });
});
</script>
@endpush
