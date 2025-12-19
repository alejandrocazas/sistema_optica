<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Acceso - KAD Óptica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex">
        <div class="hidden lg:flex w-1/2 bg-blue-900 relative items-center justify-center overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-700 to-blue-900 opacity-90 z-10"></div>
            <div class="relative z-20 text-white text-center px-12">
                <svg class="w-20 h-20 mx-auto mb-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <h2 class="text-3xl font-bold mb-2">Recuperación de Cuenta</h2>
                <p class="text-blue-100">Ingresa tu correo para recibir las instrucciones de seguridad.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">¿Olvidaste tu contraseña?</h3>
                    <p class="text-sm text-gray-500 mt-2">No hay problema. Solo escribe tu email y te enviaremos un enlace para elegir una nueva.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                        <input type="email" name="email" required autofocus class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition" placeholder="nombre@correo.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow transition transform active:scale-95">
                            ENVIAR ENLACE
                        </button>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-600 underline">Volver al inicio de sesión</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>