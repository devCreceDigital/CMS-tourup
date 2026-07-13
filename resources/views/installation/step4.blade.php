<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Instalación - Paso 4: Tema | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-3xl">
                <div class="text-center mb-8">
                    <i class="fas fa-globe-americas text-green-600 text-5xl mb-3"></i>
                    <h1 class="text-3xl font-bold text-gray-900">Instalación de {{ config('app.name') }}</h1>
                    <p class="text-gray-500 mt-2">Configura tu sistema en pocos pasos</p>
                </div>

                <div class="mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div class="ml-3 hidden sm:block">
                                <p class="font-semibold text-green-600 text-sm">Paso 1</p>
                                <p class="text-xs text-gray-500">Base de Datos</p>
                            </div>
                        </div>
                        <div class="hidden sm:block flex-1 mx-4 h-1 bg-gray-200 rounded">
                            <div class="h-1 bg-green-500 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div class="ml-3 hidden sm:block">
                                <p class="font-semibold text-green-600 text-sm">Paso 2</p>
                                <p class="text-xs text-gray-500">Admin</p>
                            </div>
                        </div>
                        <div class="hidden sm:block flex-1 mx-4 h-1 bg-gray-200 rounded">
                            <div class="h-1 bg-green-500 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">
                                <i class="fas fa-check text-sm"></i>
                            </div>
                            <div class="ml-3 hidden sm:block">
                                <p class="font-semibold text-green-600 text-sm">Paso 3</p>
                                <p class="text-xs text-gray-500">Agencia</p>
                            </div>
                        </div>
                        <div class="hidden sm:block flex-1 mx-4 h-1 bg-gray-200 rounded">
                            <div class="h-1 bg-green-500 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">4</div>
                            <div class="ml-3">
                                <p class="font-semibold text-green-600 text-sm">Paso 4</p>
                                <p class="text-xs text-gray-500">Tema</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Selecciona un Tema Visual</h2>
                    <p class="text-gray-500 mb-6">Elige la apariencia visual de tu sitio web. Puedes cambiarlo más adelante desde el panel de administración.</p>

                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                            <div>
                                <p class="font-semibold text-red-800">Errores de validación:</p>
                                <ul class="list-disc list-inside text-red-700 text-sm mt-1">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ url('/install/step4') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="theme" value="ethos_earth" class="sr-only peer" {{ old('theme', 'ethos_earth') === 'ethos_earth' ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-2xl overflow-hidden peer-checked:border-green-600 peer-checked:ring-2 peer-checked:ring-green-200 group-hover:shadow-md transition">
                                    <div class="h-40 bg-gradient-to-br from-green-800 via-emerald-700 to-lime-600 flex items-center justify-center">
                                        <i class="fas fa-leaf text-white text-5xl opacity-80"></i>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-gray-900">Earth</h3>
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:bg-green-600 peer-checked:border-green-600 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Tonos verdes y naturales. Perfecto para agencias sostenibles.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="theme" value="ethos_ocean" class="sr-only peer" {{ old('theme') === 'ethos_ocean' ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-2xl overflow-hidden peer-checked:border-green-600 peer-checked:ring-2 peer-checked:ring-green-200 group-hover:shadow-md transition">
                                    <div class="h-40 bg-gradient-to-br from-blue-800 via-cyan-700 to-teal-500 flex items-center justify-center">
                                        <i class="fas fa-water text-white text-5xl opacity-80"></i>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-gray-900">Ocean</h3>
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:bg-green-600 peer-checked:border-green-600 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Azules oceánicos. Ideal para destinos costeros y aventura.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="theme" value="ethos_peak" class="sr-only peer" {{ old('theme') === 'ethos_peak' ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-2xl overflow-hidden peer-checked:border-green-600 peer-checked:ring-2 peer-checked:ring-green-200 group-hover:shadow-md transition">
                                    <div class="h-40 bg-gradient-to-br from-stone-800 via-gray-700 to-slate-600 flex items-center justify-center">
                                        <i class="fas fa-mountain text-white text-5xl opacity-80"></i>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-gray-900">Peak</h3>
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:bg-green-600 peer-checked:border-green-600 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Tonos grises y neutros. Estilo elegante y profesional.</p>
                                    </div>
                                </div>
                            </label>

                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="theme" value="ethos_sunset" class="sr-only peer" {{ old('theme') === 'ethos_sunset' ? 'checked' : '' }}>
                                <div class="border-2 border-gray-200 rounded-2xl overflow-hidden peer-checked:border-green-600 peer-checked:ring-2 peer-checked:ring-green-200 group-hover:shadow-md transition">
                                    <div class="h-40 bg-gradient-to-br from-orange-700 via-rose-600 to-purple-700 flex items-center justify-center">
                                        <i class="fas fa-sun text-white text-5xl opacity-80"></i>
                                    </div>
                                    <div class="p-4">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-gray-900">Sunset</h3>
                                            <div class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:bg-green-600 peer-checked:border-green-600 flex items-center justify-center">
                                                <i class="fas fa-check text-white text-xs hidden peer-checked:block"></i>
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Tonos cálidos y vibrantes. Para agencias con energía.</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="flex justify-between pt-4">
                            <a href="{{ url('/install/step3') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold px-6 py-3 rounded-full border border-gray-300 hover:border-gray-400 transition">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Anterior
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ url('/?preview_theme=' . old('theme', 'ethos_earth')) }}" target="_blank" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold px-6 py-3 rounded-full border border-gray-300 hover:border-gray-400 transition">
                                    <i class="fas fa-eye mr-2"></i>
                                    Vista Previa
                                </a>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-10 py-3 rounded-full transition shadow-md hover:shadow-lg flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Finalizar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <p class="text-center text-xs text-gray-400 mt-6">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
