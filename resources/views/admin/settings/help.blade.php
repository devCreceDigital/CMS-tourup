@extends('layouts.admin')

@section('breadcrumbs', 'Sistema / Configuración / Ayuda')

@section('title', 'Ayuda - TOUR UP CMS')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.index') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">General</a>
    <a href="{{ route('admin.settings.phrases') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.phrases*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Frases</a>
    <a href="{{ route('admin.settings.social') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.social*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Redes Sociales</a>
    <a href="{{ route('admin.settings.email') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.email*') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Email</a>
    <a href="{{ route('admin.settings.help') }}" class="px-4 py-2 text-sm font-medium {{ request()->routeIs('admin.settings.help') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg transition-colors">Ayuda</a>
</div>

<div class="space-y-6">
    {{-- Welcome --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Bienvenido a TOUR UP CMS</h2>
            <p class="text-sm text-gray-500 mt-1">Tu panel de administración todo-en-uno para gestionar tu agencia de viajes.</p>
        </div>
        <div class="p-6 text-sm text-gray-700 leading-relaxed space-y-4">
            <p>TOUR UP CMS es un sistema de gestión integral para agencias de turismo. Te permite administrar viajes, viajeros, reservas, contenido web y relaciones con clientes desde un solo panel.</p>
            <p>Aquí encontrarás una guía rápida de las secciones principales para que puedas empezar a usar el sistema de inmediato.</p>
        </div>
    </div>

    {{-- Sections --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Viajes --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-suitcase text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Gestión de Viajes</h3>
            </div>
            <div class="p-6 text-sm text-gray-700 space-y-3">
                <p>Desde el panel principal puedes ver todos tus viajes activos, próximas salidas y estadísticas generales.</p>
                <p>Cada viaje tiene una ficha con pestañas donde puedes configurar:</p>
                <ul class="list-disc pl-5 space-y-1 text-gray-600">
                    <li><strong>Información:</strong> datos generales del viaje</li>
                    <li><strong>Itinerario:</strong> plan día a día con actividades</li>
                    <li><strong>Transporte:</strong> autobuses y mapa de asientos</li>
                    <li><strong>Alojamiento:</strong> hoteles y asignación de habitaciones</li>
                    <li><strong>Tarifas:</strong> grupos de pago y plazos</li>
                    <li><strong>Viajeros:</strong> pasajeros inscritos</li>
                    <li><strong>Documentación:</strong> documentos de cada viajero</li>
                </ul>
                <div class="mt-2 p-3 bg-blue-50 rounded-lg text-blue-700">
                    <i class="fas fa-lightbulb mr-1"></i> Para crear un nuevo viaje, haz clic en "Nuevo Viaje" desde el Dashboard o duplica un programa existente.
                </div>
            </div>
        </div>

        {{-- Programas --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                    <i class="fas fa-layer-group text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Programas y Categorías</h3>
            </div>
            <div class="p-6 text-sm text-gray-700 space-y-3">
                <p>Los programas son plantillas base de viajes. Puedes crear un programa con la información general de un destino y luego duplicarlo para crear viajes activos con fechas concretas.</p>
                <p>Las categorías te permiten clasificar los viajes (Aventura, Cultural, Idiomas, etc.) y controlar su visibilidad en la web pública.</p>
            </div>
        </div>

        {{-- Pasajeros --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                    <i class="fas fa-users text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Pasajeros</h3>
            </div>
            <div class="p-6 text-sm text-gray-700 space-y-3">
                <p>Aquí puedes ver todos los viajeros registrados en el sistema, buscar por nombre o DNI y acceder a su ficha detallada.</p>
                <p>Cada viajero puede estar vinculado a uno o varios viajes. Desde su ficha puedes ver su historial de pagos y documentación.</p>
            </div>
        </div>

        {{-- Contenido Web --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center text-amber-600">
                    <i class="fas fa-newspaper text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Contenido Web</h3>
            </div>
            <div class="p-6 text-sm text-gray-700 space-y-3">
                <p>Administra todo el contenido visible de tu sitio web:</p>
                <ul class="list-disc pl-5 space-y-1 text-gray-600">
                    <li><strong>Blog:</strong> escribe artículos con imágenes y categorías</li>
                    <li><strong>FAQ:</strong> crea preguntas frecuentes y ordénalas</li>
                    <li><strong>Servicios:</strong> describe los servicios que ofreces</li>
                    <li><strong>Mensajes:</strong> revisa los mensajes del formulario de contacto</li>
                </ul>
            </div>
        </div>

        {{-- Apariencia --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-pink-100 flex items-center justify-center text-pink-600">
                    <i class="fas fa-paint-brush text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Apariencia</h3>
            </div>
            <div class="p-6 text-sm text-gray-700 space-y-3">
                <p>Elige entre 4 temas visuales para tu sitio web. Puedes previsualizar cada tema antes de activarlo.</p>
                <p>Los temas solo cambian el aspecto visual; el contenido y la funcionalidad se mantienen intactos.</p>
                <p>Más adelante podrás gestionar imágenes y frases públicas desde Configuración.</p>
            </div>
        </div>

        {{-- Configuración --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-600">
                    <i class="fas fa-cog text-sm"></i>
                </div>
                <h3 class="font-semibold text-gray-900">Configuración</h3>
            </div>
            <div class="p-6 text-sm text-gray-700 space-y-3">
                <p>Desde Configuración puedes personalizar:</p>
                <ul class="list-disc pl-5 space-y-1 text-gray-600">
                    <li><strong>Frases:</strong> textos visibles en la web pública</li>
                    <li><strong>Redes Sociales:</strong> enlaces a tus perfiles</li>
                    <li><strong>Email:</strong> configuración SMTP para notificaciones</li>
                    <li><strong>Perfil:</strong> tu nombre, email, teléfono y avatar</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Tips --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Consejos Rápidos</h3>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="flex gap-3 p-3 bg-green-50 rounded-lg">
                <div class="text-green-600 text-lg"><i class="fas fa-check-circle"></i></div>
                <p class="text-green-800">Usa el buscador global del header para encontrar viajes, viajeros o contenido rápidamente.</p>
            </div>
            <div class="flex gap-3 p-3 bg-blue-50 rounded-lg">
                <div class="text-blue-600 text-lg"><i class="fas fa-check-circle"></i></div>
                <p class="text-blue-800">Los programas son plantillas; duplícalos para crear nuevas temporadas sin partir de cero.</p>
            </div>
            <div class="flex gap-3 p-3 bg-amber-50 rounded-lg">
                <div class="text-amber-600 text-lg"><i class="fas fa-check-circle"></i></div>
                <p class="text-amber-800">Configura el email SMTP para recibir notificaciones de nuevas reservas automáticamente.</p>
            </div>
            <div class="flex gap-3 p-3 bg-purple-50 rounded-lg">
                <div class="text-purple-600 text-lg"><i class="fas fa-check-circle"></i></div>
                <p class="text-purple-800">Puedes previsualizar los temas visuales antes de activarlos desde Apariencia → Temas.</p>
            </div>
        </div>
    </div>
</div>
@endsection
