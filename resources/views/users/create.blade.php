<x-app>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg border-t-4 border-blue-600 dark:border-blue-500 transition-colors">
        
        {{-- ENCABEZADO --}}
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Registrar Nuevo Empleado</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Crea las credenciales de acceso y asigna sucursal.</p>
        </div>

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            {{-- NOMBRE --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre Completo</label>
                <input type="text" name="name" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Ej: Ana Gomez">
            </div>

            {{-- EMAIL --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Correo Electrónico (Login)</label>
                <input type="email" name="email" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="ana@optica.com">
            </div>

            {{-- === NUEVO CAMPO: SUCURSAL === --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Sucursal Asignada</label>
                <div class="relative">
                    <select name="branch_id" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none appearance-none transition cursor-pointer">
                        <option value="" disabled selected>Seleccione una sucursal...</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    {{-- Icono flecha --}}
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 dark:text-gray-300">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-1">El usuario solo verá datos de esta sucursal.</p>
            </div>

            {{-- ROL --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Rol / Cargo</label>
                <div class="relative">
                    <select name="role" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none appearance-none transition cursor-pointer">
                        <option value="vendedor">Vendedor (Caja y Ventas)</option>
                        <option value="optometrista">Optometrista (Pacientes y Recetas)</option>
                        <option value="admin">Administrador (Acceso Total)</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 dark:text-gray-300">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            {{-- PASSWORD --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Contraseña</label>
                <input type="password" name="password" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••">
            </div>

            {{-- CONFIRMAR PASSWORD --}}
            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="••••••••">
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 pt-6 border-t dark:border-gray-700">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
</x-app>