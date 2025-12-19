<x-app>
    {{-- ENCABEZADO Y BOTONES --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Listado de Pacientes</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Gestiona la información y el historial clínico.</p>
        </div>

        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            {{-- BUSCADOR --}}
            <form action="{{ route('patients.index') }}" method="GET" class="relative flex-grow md:flex-grow-0">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, CI..." 
                    class="w-full md:w-64 pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-400 shadow-sm">
                <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            {{-- BOTÓN NUEVO --}}
            <a href="{{ route('patients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition transform hover:-translate-y-0.5 whitespace-nowrap">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                Nuevo Paciente
            </a>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">
        
        @if($patients->isEmpty())
            <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <p>No se encontraron pacientes.</p>
                    @if(request('search'))
                        <a href="{{ route('patients.index') }}" class="text-blue-500 hover:underline mt-2 text-sm">Limpiar búsqueda</a>
                    @endif
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Teléfono
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                CI / DNI
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Edad
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($patients as $patient)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition bg-white dark:bg-gray-800">
                            
                            <td class="px-5 py-4 text-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold uppercase text-sm border dark:border-blue-800">
                                        {{ substr($patient->name, 0, 2) }}
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-900 dark:text-white font-bold text-base whitespace-no-wrap">
                                            {{ $patient->name }}
                                        </p>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs">{{ $patient->occupation ?? 'Sin ocupación' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <span class="text-gray-700 dark:text-gray-300 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                    {{ $patient->phone ?? '-' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <p class="text-gray-900 dark:text-white font-mono">{{ $patient->ci }}</p>
                            </td>

                            <td class="px-5 py-4 text-sm text-center">
                                <span class="inline-block px-3 py-1 font-bold text-xs leading-tight text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full border border-green-200 dark:border-green-800">
                                    {{ $patient->age }} años
                                </span>
                            </td>

                            <td class="px-5 py-4 text-sm text-center">
                                <div class="flex justify-center items-center gap-2">
                                    {{-- Editar --}}
                                    <a href="{{ route('patients.edit', $patient->id) }}" class="p-2 text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-200 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition" title="Editar Datos">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </a>

                                    {{-- Historial --}}
                                    <a href="{{ route('prescriptions.history', $patient->id) }}" class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition" title="Ver Historial Médico">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" />
                                        </svg>
                                    </a>

                                    {{-- Eliminar --}}
                                    <form id="delete-form-{{ $patient->id }}" action="{{ route('patients.destroy', $patient->id) }}" method="POST" style="display: none;">
                                        @csrf @method('DELETE')
                                    </form>
                                    <button onclick="confirmDelete(event, 'delete-form-{{ $patient->id }}')" class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Eliminar Paciente">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINACIÓN --}}
            @if(method_exists($patients, 'links'))
                <div class="px-5 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                    {{ $patients->links() }}
                </div>
            @endif
        @endif
    </div>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Se eliminará el paciente y todo su historial médico.",
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