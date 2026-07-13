@extends('layouts.admin')

@section('breadcrumbs', 'Viajes / Editar Viaje')

@section('title', 'Editar Viaje')

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-4xl mx-auto">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Editar Viaje: {{ $trip->name }}</h2>
        </div>

        <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Referencia</label>
                    <input type="text" name="reference" id="reference" value="{{ old('reference', $trip->reference) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('reference') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $trip->name) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug', $trip->slug) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
                    @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="trip_category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                    <select name="trip_category_id" id="trip_category_id" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="">Seleccionar categoría</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('trip_category_id', $trip->trip_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('trip_category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="destination" class="block text-sm font-medium text-gray-700 mb-1">Destino</label>
                    <input type="text" name="destination" id="destination" value="{{ old('destination', $trip->destination) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('destination') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                        <option value="active" {{ old('status', $trip->status) == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="on_sale" {{ old('status', $trip->status) == 'on_sale' ? 'selected' : '' }}>En Venta</option>
                        <option value="completed" {{ old('status', $trip->status) == 'completed' ? 'selected' : '' }}>Completado</option>
                        <option value="inactive" {{ old('status', $trip->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        <option value="cancelled" {{ old('status', $trip->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                    </select>
                    @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Inicio</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $trip->start_date ? $trip->start_date->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('start_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Fecha Fin</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $trip->end_date ? $trip->end_date->format('Y-m-d') : '') }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('end_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="total_spots" class="block text-sm font-medium text-gray-700 mb-1">Plazas Totales</label>
                    <input type="number" min="1" name="total_spots" id="total_spots" value="{{ old('total_spots', $trip->total_spots) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                    @error('total_spots') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="occupied_spots" class="block text-sm font-medium text-gray-700 mb-1">Plazas Ocupadas</label>
                    <input type="number" min="0" name="occupied_spots" id="occupied_spots" value="{{ old('occupied_spots', $trip->occupied_spots) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('occupied_spots') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Imagen Actual</label>
                    @if ($trip->image)
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/' . $trip->image) }}" alt="{{ $trip->name }}" class="w-20 h-20 object-cover rounded-lg border">
                            <span class="text-sm text-gray-500">{{ basename($trip->image) }}</span>
                        </div>
                    @else
                        <p class="text-sm text-gray-400">Sin imagen</p>
                    @endif
                    <label for="image" class="block text-sm font-medium text-gray-700 mt-2 mb-1">Cambiar Imagen</label>
                    <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                <textarea name="description" id="description" rows="5" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $trip->description) }}</textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.trips.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-1"></i> Actualizar Viaje
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');

        if (nameInput && slugInput && !slugInput.dataset.initial) {
            slugInput.dataset.initial = slugInput.value;
        }

        if (nameInput && slugInput) {
            nameInput.addEventListener('blur', function () {
                if (slugInput.value === slugInput.dataset.initial) {
                    slugInput.value = nameInput.value
                        .toLowerCase()
                        .replace(/[^\w\s-]/g, '')
                        .replace(/[\s_]+/g, '-')
                        .replace(/^-+|-+$/g, '');
                    slugInput.dataset.initial = slugInput.value;
                }
            });
        }
    });
</script>
@endpush
