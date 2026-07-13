@extends('layouts.admin')

@section('breadcrumbs', 'Sistema / Categorías')

@section('title', 'Categorías')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h2 class="text-lg font-semibold text-gray-900">Categorías de Viajes</h2>
            <button id="openCreateModal" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-plus"></i>
                Nueva Categoría
            </button>
        </div>

        <div class="overflow-x-auto">
            @if (isset($categories) && $categories->count() > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-left">
                            <th class="px-6 py-3 font-medium">Nombre</th>
                            <th class="px-6 py-3 font-medium">Slug</th>
                            <th class="px-6 py-3 font-medium">Icono</th>
                            <th class="px-6 py-3 font-medium">Viajes Activos</th>
                            <th class="px-6 py-3 font-medium">Estado</th>
                            <th class="px-6 py-3 font-medium text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($categories as $category)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                                <td class="px-6 py-4 text-gray-600 text-lg">
                                    @if ($category->icon)
                                        <i class="fas fa-{{ $category->icon }}"></i>
                                    @else
                                        <span class="text-gray-300">--</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $category->trips_count ?? $category->trips->count() ?? 0 }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                        {{ $category->is_active ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" class="open-edit-modal p-2 text-gray-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Editar"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            data-slug="{{ $category->slug }}"
                                            data-icon="{{ $category->icon }}"
                                            data-description="{{ $category->description }}"
                                            data-active="{{ $category->is_active ? '1' : '0' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar" data-confirm="¿Eliminar esta categoría? No se puede eliminar si tiene viajes asociados.">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $categories->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-gray-300 text-5xl mb-4">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-500 mb-2">No hay categorías registradas</h3>
                    <p class="text-sm text-gray-400 mb-4">Crea tu primera categoría para organizar los viajes.</p>
                    <button id="openCreateModalEmpty" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus"></i>
                        Crear Categoría
                    </button>
                </div>
            @endif
        </div>
    </div>

    {{-- Create Modal --}}
    <div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-black/50" onclick="closeCreateModal()"></div>
        <div class="relative bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Nueva Categoría</h3>
                <button type="button" onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="create_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="name" id="create_name" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="create_icon" class="block text-sm font-medium text-gray-700 mb-1">Icono (Font Awesome)</label>
                        <input type="text" name="icon" id="create_icon" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ej: mountain, beach, city">
                        <p class="mt-1 text-xs text-gray-400">Nombre del icono Font Awesome sin prefijo "fa-"</p>
                    </div>

                    <div>
                        <label for="create_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="description" id="create_description" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="create_is_active" value="1" checked class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="create_is_active" class="text-sm text-gray-700">Activo</label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeCreateModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancelar</button>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-black/50" onclick="closeEditModal()"></div>
        <div class="relative bg-white rounded-xl shadow-lg w-full max-w-lg mx-4 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Editar Categoría</h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="name" id="edit_name" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    </div>

                    <div>
                        <label for="edit_icon" class="block text-sm font-medium text-gray-700 mb-1">Icono (Font Awesome)</label>
                        <input type="text" name="icon" id="edit_icon" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Ej: mountain, beach, city">
                    </div>

                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea name="description" id="edit_description" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="edit_is_active" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <label for="edit_is_active" class="text-sm text-gray-700">Activo</label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">Cancelar</button>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-1"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        const createBtn = document.getElementById('openCreateModal');
        const createBtnEmpty = document.getElementById('openCreateModalEmpty');
        if (createBtn) createBtn.addEventListener('click', openCreateModal);
        if (createBtnEmpty) createBtnEmpty.addEventListener('click', openCreateModal);

        document.querySelectorAll('.open-edit-modal').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.getElementById('edit_name').value = this.dataset.name;
                document.getElementById('edit_icon').value = this.dataset.icon;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_is_active').checked = this.dataset.active === '1';
                document.getElementById('editForm').action = '{{ url('admin/categories') }}/' + this.dataset.id;
                document.getElementById('editModal').classList.remove('hidden');
            });
        });
    });
</script>
@endpush
