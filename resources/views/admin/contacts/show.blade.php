@extends('layouts.admin')

@section('breadcrumbs', 'Contenido / Mensajes / ' . $contact->name)

@section('title', 'Mensaje de ' . $contact->name)

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.contacts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-1"></i> Volver a Mensajes
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-900">{{ $contact->subject }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $contact->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $contact->is_read ? 'bg-gray-100 text-gray-600' : 'bg-blue-100 text-blue-800' }}">
                {{ $contact->is_read ? 'Leído' : 'Nuevo' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 p-4 bg-gray-50 rounded-lg">
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Nombre</dt>
                <dd class="text-sm text-gray-900 mt-1">{{ $contact->name }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Email</dt>
                <dd class="text-sm text-gray-900 mt-1"><a href="mailto:{{ $contact->email }}" class="text-blue-600 hover:underline">{{ $contact->email }}</a></dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide">Teléfono</dt>
                <dd class="text-sm text-gray-900 mt-1">{{ $contact->phone ?? '--' }}</dd>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-medium text-gray-700 mb-2">Mensaje</h3>
            <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-800 leading-relaxed whitespace-pre-wrap">
                {{ $contact->message }}
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
            <a href="mailto:{{ $contact->email }}?subject=Re: {{ $contact->subject }}" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-reply mr-1"></i> Responder
            </a>
            <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors" data-confirm="¿Eliminar este mensaje?">
                    <i class="fas fa-trash mr-1"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
