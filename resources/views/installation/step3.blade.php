<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Instalación - Paso 3: Agencia | {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow flex items-center justify-center px-4 py-8">
            <div class="w-full max-w-2xl">
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
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">3</div>
                            <div class="ml-3">
                                <p class="font-semibold text-green-600 text-sm">Paso 3</p>
                                <p class="text-xs text-gray-500">Agencia</p>
                            </div>
                        </div>
                        <div class="hidden sm:block flex-1 mx-4 h-1 bg-gray-200 rounded">
                            <div class="h-1 bg-gray-200 rounded"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">4</div>
                            <div class="ml-3 hidden sm:block">
                                <p class="font-semibold text-gray-400 text-sm">Paso 4</p>
                                <p class="text-xs text-gray-400">Tema</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Información de la Agencia</h2>
                    <p class="text-gray-500 mb-6">Configura los datos principales de tu agencia de viajes.</p>

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

                    <form action="{{ url('/install/step3') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-building mr-1 text-gray-400"></i>Nombre de la Agencia
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200 @error('name') border-red-300 @enderror"
                                placeholder="TOUR UP">
                        </div>

                        <div>
                            <label for="welcome_phrase" class="block text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-quote-right mr-1 text-gray-400"></i>Frase de Bienvenida
                            </label>
                            <textarea id="welcome_phrase" name="welcome_phrase" rows="2"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200 @error('welcome_phrase') border-red-300 @enderror"
                                placeholder="Diseñamos viajes responsables a medida">{{ old('welcome_phrase') }}</textarea>
                        </div>

                        <div>
                            <label for="about" class="block text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-info-circle mr-1 text-gray-400"></i>Sobre la Agencia
                            </label>
                            <textarea id="about" name="about" rows="3"
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200 @error('about') border-red-300 @enderror"
                                placeholder="Breve descripción de la agencia...">{{ old('about') }}</textarea>
                        </div>

                        <div>
                            <label for="logo" class="block text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-image mr-1 text-gray-400"></i>Logo de la Agencia
                            </label>
                            <div class="mt-1 flex items-center space-x-4">
                                <div id="logo-preview" class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 border-2 border-dashed border-gray-300">
                                    <i class="fas fa-camera text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <input type="file" id="logo" name="logo" accept="image/*"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100 @error('logo') border-red-300 @enderror">
                                    <p class="text-xs text-gray-400 mt-1">Formatos: JPG, PNG, WEBP. Máx: 2MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between pt-4">
                            <a href="{{ url('/install/step2') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold px-6 py-3 rounded-full border border-gray-300 hover:border-gray-400 transition">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Anterior
                            </a>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-full transition shadow-md hover:shadow-lg flex items-center">
                                Siguiente
                                <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <p class="text-center text-xs text-gray-400 mt-6">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('logo')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('logo-preview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Logo preview" class="w-full h-full object-contain rounded-xl">';
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
