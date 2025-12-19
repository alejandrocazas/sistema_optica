<x-app>
    <div class="max-w-md mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg border-t-4 border-yellow-500 dark:border-yellow-600 transition-colors">
        
        {{-- ENCABEZADO --}}
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Usuario</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Actualiza los datos y permisos del empleado.</p>
        </div>

        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') {{-- Importante para actualizaciones --}}

            {{-- NOMBRE --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre Completo</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
            </div>

            {{-- EMAIL --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Correo Electrónico</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
            </div>

            {{-- SUCURSAL --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Sucursal Asignada</label>
                <div class="relative">
                    <select name="branch_id" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none appearance-none transition cursor-pointer">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $user->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 dark:text-gray-300">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            {{-- ROL --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Rol / Cargo</label>
                <div class="relative">
                    <select name="role" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none appearance-none transition cursor-pointer">
                        <option value="vendedor" {{ $user->role == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                        <option value="optometrista" {{ $user->role == 'optometrista' ? 'selected' : '' }}>Optometrista</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 dark:text-gray-300">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </div>
                </div>
            </div>

            <hr class="my-6 border-gray-200 dark:border-gray-700">

            {{-- CONTRASEÑA (OPCIONAL) --}}
            <div class="mb-5">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nueva Contraseña <span class="text-xs font-normal text-gray-500">(Dejar en blanco para mantener la actual)</span></label>
                <input type="password" name="password" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition" placeholder="••••••••">
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Confirmar Nueva Contraseña</label>
                <input type="password" name="password_confirmation" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition" placeholder="••••••••">
            </div>

            {{-- BOTONES --}}
            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('users.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform transition hover:-translate-y-0.5 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</x-app>