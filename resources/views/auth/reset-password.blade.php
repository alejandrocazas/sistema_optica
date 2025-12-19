<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Contraseña - KAD Óptica</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex">
        
        <div class="hidden lg:flex w-1/2 bg-blue-900 relative items-center justify-center overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-blue-800 to-blue-900 opacity-90 z-10"></div>
            <div class="relative z-20 text-white text-center px-12">
                <svg class="w-20 h-20 mx-auto mb-6 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h2 class="text-3xl font-bold mb-2">Restablecer Acceso</h2>
                <p class="text-blue-100">Crea una contraseña segura para proteger tu información.</p>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-white">
            <div class="w-full max-w-md">
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-800">Crear Nueva Contraseña</h3>
                    <p class="text-sm text-gray-500 mt-2">Asegúrate de que sea distinta a la anterior.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}" required readonly class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                        <input type="password" name="password" required autocomplete="new-password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required autocomplete="new-password" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow transition transform active:scale-95">
                            RESTABLECER CONTRASEÑA
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
