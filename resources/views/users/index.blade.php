<x-app>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Gestión de Personal</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Administra los usuarios, sus roles y ubicación.</p>
        </div>
        
        <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nuevo Usuario
        </a>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Email
                        </th>
                        {{-- NUEVA COLUMNA: SUCURSAL --}}
                        <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Sucursal
                        </th>
                        <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Rol
                        </th>
                        <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition bg-white dark:bg-gray-800">
                        
                        <td class="px-5 py-4 text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 rounded-full flex items-center justify-center font-bold uppercase border dark:border-indigo-800">
                                    {{ substr($user->name, 0, 2) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-900 dark:text-white font-bold whitespace-no-wrap">
                                        {{ $user->name }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="px-5 py-4 text-sm">
                            <p class="text-gray-900 dark:text-gray-300 font-mono text-xs md:text-sm">
                                {{ $user->email }}
                            </p>
                        </td>

                        {{-- DATO DE SUCURSAL --}}
                        <td class="px-5 py-4 text-sm text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                {{ $user->branch->name ?? 'Global / Sin Asignar' }}
                            </span>
                        </td>

                        <td class="px-5 py-4 text-sm text-center">
                            @if($user->role === 'admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                    Administrador
                                </span>
                            @elseif($user->role === 'optometrista')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                    Optometrista
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                    Vendedor
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-sm text-center">
                            <div class="flex justify-center items-center gap-3">
                                
                                {{-- Editar --}}
                                <a href="{{ route('users.edit', $user) }}" class="p-2 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition" title="Editar Usuario">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                {{-- Eliminar (Solo si no es uno mismo) --}}
                                @if(auth()->id() !== $user->id)
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button onclick="confirmDelete(event, 'delete-form-{{ $user->id }}')" class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Eliminar Usuario">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                @else
                                    <span class="p-2 text-gray-300 dark:text-gray-600 cursor-not-allowed" title="No puedes eliminarte a ti mismo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 6.524l8.367 8.368zm1.414-1.414L6.524 5.11a6 6 0 018.367 8.367zM18 10a8 8 0 11-16 0 8 8 0 0116 0z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- PAGINACIÓN --}}
        @if(method_exists($users, 'links'))
            <div class="px-5 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "El usuario perderá acceso al sistema inmediatamente.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
</x-app>