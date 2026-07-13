@extends('layouts.admin')

@section('breadcrumbs', 'Blog / Artículos')

@section('title', 'Artículos del Blog')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Artículos</h1>
    <a href="{{ route('admin.blog-posts.create') }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
        <i class="fas fa-plus"></i> Nuevo Artículo
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    @if($posts->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Título</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Categoría</th>
                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($posts as $post)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-gray-900">{{ $post->title }}</p>
                        @if($post->excerpt)<p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($post->excerpt, 80) }}</p>@endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $post->category->name ?? 'Sin categoría' }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $post->status === 'published' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $post->status === 'published' ? 'Publicado' : 'Borrador' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $post->published_at ? $post->published_at->format('d/m/Y') : ($post->created_at->format('d/m/Y')) }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.blog-posts.edit', $post) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors inline-block">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar este artículo?">
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
        {{ $posts->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-newspaper"></i></div>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No hay artículos</h3>
        <p class="text-sm text-gray-400 mb-4">Crea tu primer artículo para el blog.</p>
        <a href="{{ route('admin.blog-posts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus"></i> Nuevo Artículo
        </a>
    </div>
    @endif
</div>
@endsection
