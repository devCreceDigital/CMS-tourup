@extends('layouts.admin')

@section('breadcrumbs', 'Blog / Artículos / Editar')

@section('title', 'Editar Artículo')

@push('styles')
<link rel="stylesheet" href="{{ asset('js/jodit/jodit.min.css') }}">
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.blog-posts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Artículos
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <form action="{{ route('admin.blog-posts.update', $blogPost) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" name="title" id="title" value="{{ old('title', $blogPost->title) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="blog_category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
                <select name="blog_category_id" id="blog_category_id" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Sin categoría</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($blogPost->blog_category_id ?? old('blog_category_id')) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="author" class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                <input type="text" name="author" id="author" value="{{ old('author', $blogPost->author) }}" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="draft" {{ ($blogPost->status ?? old('status')) === 'draft' ? 'selected' : '' }}>Borrador</option>
                    <option value="published" {{ ($blogPost->status ?? old('status')) === 'published' ? 'selected' : '' }}>Publicado</option>
                </select>
            </div>

            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Imagen Destacada</label>
                @if($blogPost->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $blogPost->image) }}" class="h-20 w-auto rounded-lg object-cover">
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*" class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div></div>
        </div>

        <div>
            <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Extracto</label>
            <textarea name="excerpt" id="excerpt" rows="2" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('excerpt', $blogPost->excerpt) }}</textarea>
        </div>

        <div>
            <label for="body" class="block text-sm font-medium text-gray-700 mb-1">Contenido</label>
            <textarea name="body" id="body" rows="20" class="w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500" required>{{ old('body', $blogPost->body) }}</textarea>
            @error('body') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
            <a href="{{ route('admin.blog-posts.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors">Cancelar</a>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-1"></i> Actualizar Artículo
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/jodit/jodit.min.js') }}"></script>
<script>
const editor = Jodit.make('#body', {
    height: 500,
    language: 'es',
    toolbarAdaptive: false,
    buttons: ['bold', 'italic', 'underline', 'strikethrough', '|', 'ul', 'ol', '|', 'font', 'fontsize', 'brush', 'paragraph', '|', 'image', 'link', 'table', '|', 'align', 'undo', 'redo', 'hr', 'eraser', 'fullsize'],
    uploader: { insertImageAsBase64URI: true },
    defaultMode: 'wysiwyg',
});
</script>
@endpush
