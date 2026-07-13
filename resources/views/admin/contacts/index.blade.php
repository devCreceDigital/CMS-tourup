@extends('layouts.admin')

@section('breadcrumbs', 'Contenido / Mensajes')

@section('title', 'Mensajes de Contacto')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Mensajes</h1>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    @if($contacts->count())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200 bg-gray-50">
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Nombre</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Asunto</th>
                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="text-left px-6 py-3 text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="text-right px-6 py-3 text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($contacts as $contact)
                <tr class="hover:bg-gray-50 transition-colors {{ !$contact->is_read ? 'bg-blue-50/50 font-medium' : '' }}">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $contact->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:underline">{{ $contact->email }}</a>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $contact->subject }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contact->is_read ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                            {{ $contact->is_read ? 'Leído' : 'Nuevo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors inline-block">
                            <i class="fas fa-eye"></i>
                        </a>
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" data-confirm="¿Eliminar este mensaje?">
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
        {{ $contacts->links() }}
    </div>
    @else
    <div class="text-center py-16">
        <div class="text-gray-300 text-5xl mb-4"><i class="fas fa-envelope-open-text"></i></div>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No hay mensajes</h3>
        <p class="text-sm text-gray-400">Los mensajes del formulario de contacto aparecerán aquí.</p>
    </div>
    @endif
</div>
@endsection
