<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ingresar - Óptica Alfa</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Importamos una fuente moderna de Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800;900&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-100">

    <div class="min-h-screen flex">
        
        {{-- PANEL IZQUIERDO (DECORATIVO) --}}
        <div class="hidden lg:flex w-1/2 bg-blue-900 relative items-center justify-center overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-700 to-indigo-900 opacity-90 z-10"></div>
            
            {{-- Formas decorativas de fondo --}}
            <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-blue-500 opacity-20 blur-3xl z-0"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full bg-cyan-400 opacity-20 blur-3xl z-0"></div>
            
            {{-- Imagen de fondo --}}
            <img src="https://images.unsplash.com/photo-1570222094114-2819cd98731e?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-30 z-0">

            <div class="relative z-20 text-white text-center px-10">
                
                {{-- NUEVO LOGO EN TEXTO (Panel Izquierdo) --}}
                <div class="mb-6 flex flex-col justify-center items-center">
                    <h1 class="text-6xl font-black tracking-tighter" style="font-family: 'Montserrat', sans-serif;">
                        ÓPTICA <span class="text-cyan-400">ALFA</span>
                    </h1>
                    <div class="h-1 w-24 bg-cyan-400 mt-4 rounded-full"></div>
                </div>

                <h2 class="text-3xl font-bold mb-2">Bienvenido de nuevo</h2>
                <p class="text-blue-200 text-lg font-light">Sistema Integral de Gestión Oftalmológica</p>
            </div>
        </div>

        {{-- PANEL DERECHO (FORMULARIO) --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 relative">
            
            {{-- Decoración sutil fondo derecho --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-blue-50 rounded-bl-full opacity-50"></div>

            <div class="w-full max-w-md z-10">
                
                <div class="mb-10 text-center lg:text-left">
                    {{-- LOGO EN TEXTO PARA MOVIL / CABECERA --}}
                    <h1 class="text-4xl font-black text-blue-900 tracking-tight mb-2" style="font-family: 'Montserrat', sans-serif;">
                        ÓPTICA <span class="text-blue-600">ALFA</span>
                    </h1>
                    <h3 class="text-2xl font-bold text-gray-700">Iniciar Sesión</h3>
                    <p class="text-gray-500 mt-2 text-sm">Ingresa tus credenciales para acceder al panel.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" /></svg>
                            </span>
                            <input type="email" name="email" required autofocus class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none font-medium" placeholder="ejemplo@gmail.com">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Contraseña</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            </span>
                            <input type="password" name="password" required class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none font-medium" placeholder="••••••••">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-600">Recuérdame</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-blue-800 font-bold hover:underline" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white font-bold py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5 active:scale-95 flex justify-center tracking-wide uppercase text-sm">
                        INGRESAR AL SISTEMA
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    © {{ date('Y') }} Óptica Alfa. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
</body>
</html>