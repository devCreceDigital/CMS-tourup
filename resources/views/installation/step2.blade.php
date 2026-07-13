<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Instalación - Paso 2: Cuenta Admin | {{ config('app.name') }}</title>
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
                            <div class="ml-3">
                                <p class="font-semibold text-green-600 text-sm">Paso 1</p>
                                <p class="text-xs text-gray-500">Base de Datos</p>
                            </div>
                        </div>
                        <div class="hidden sm:block flex-1 mx-4 h-1 bg-gray-200 rounded">
                            <div class="h-1 bg-green-500 rounded" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 text-white rounded-full flex items-center justify-center font-bold shadow-md">2</div>
                            <div class="ml-3">
                                <p class="font-semibold text-green-600 text-sm">Paso 2</p>
                                <p class="text-xs text-gray-500">Admin</p>
                            </div>
                        </div>
                        <div class="hidden sm:block flex-1 mx-4 h-1 bg-gray-200 rounded">
                            <div class="h-1 bg-gray-200 rounded"></div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-bold">3</div>
                            <div class="ml-3 hidden sm:block">
                                <p class="font-semibold text-gray-400 text-sm">Paso 3</p>
                                <p class="text-xs text-gray-400">Agencia</p>
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Cuenta de Administrador</h2>
                    <p class="text-gray-500 mb-6">Crea la cuenta de administrador principal del sistema.</p>

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

                    <form action="{{ url('/install/step2') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">
                                <i class="fas fa-user mr-1 text-gray-400"></i>Nombre Completo
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200 @error('name') border-red-300 @enderror"
                                placeholder="Tu nombre">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <i class="fas fa-envelope mr-1 text-gray-400"></i>Correo Electrónico
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200 @error('email') border-red-300 @enderror"
                                    placeholder="admin@ejemplo.com">
                            </div>
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <i class="fas fa-phone mr-1 text-gray-400"></i>Teléfono
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200 @error('phone') border-red-300 @enderror"
                                    placeholder="+34 600 000 000">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <i class="fas fa-lock mr-1 text-gray-400"></i>Contraseña
                                </label>
                                <input type="password" id="password" name="password" required
                                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200 @error('password') border-red-300 @enderror"
                                    placeholder="Mín. 8 caracteres">
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <i class="fas fa-lock mr-1 text-gray-400"></i>Confirmar Contraseña
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="w-full rounded-lg border-gray-300 px-4 py-2.5 focus:border-green-500 focus:ring focus:ring-green-200"
                                    placeholder="Repite la contraseña">
                            </div>
                        </div>

                        <div class="flex justify-between pt-4">
                            <a href="{{ url('/install/step1') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 font-semibold px-6 py-3 rounded-full border border-gray-300 hover:border-gray-400 transition">
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
</body>
</html>
