<x-app>
    <div class="max-w-3xl mx-auto mt-10">
        <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg text-center transition-colors border-t-4 border-blue-500 dark:border-blue-600">
            
            {{-- Encabezado --}}
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Nueva Consulta Oftalmológica</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">Busca al paciente para cargar sus datos automáticamente.</p>

            {{-- Formulario de Búsqueda --}}
            <form action="{{ route('prescriptions.selectPatient') }}" method="GET" class="relative mb-8">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}"
                           class="w-full pl-12 pr-32 py-4 border rounded-xl shadow-sm outline-none transition
                                  bg-gray-50 text-gray-800 border-gray-200 focus:ring-2 focus:ring-blue-500 focus:bg-white
                                  dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:focus:ring-blue-500"
                           placeholder="Buscar por Nombre o Carnet (CI)..." autofocus>
                    
                    {{-- Icono Lupa --}}
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>

                    {{-- Botón Buscar --}}
                    <button type="submit" class="absolute right-2 top-2 bottom-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow">
                        Buscar
                    </button>
                </div>
            </form>

            {{-- Resultados --}}
            @if($search)
                @if(count($patients) > 0)
                    <div class="text-left mb-2 text-sm text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider pl-1">
                        Resultados Encontrados:
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-600 shadow-sm">
                        @foreach($patients as $patient)
                        <div class="flex justify-between items-center p-4 border-b dark:border-gray-600 last:border-0 hover:bg-blue-50 dark:hover:bg-gray-600/50 transition group">
                            <div class="flex items-center gap-4 text-left">
                                {{-- Avatar con Iniciales --}}
                                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold text-sm uppercase shrink-0">
                                    {{ substr($patient->name, 0, 2) }}
                                </div>
                                
                                <div>
                                    <p class="font-bold text-lg text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                        {{ $patient->name }}
                                    </p>
                                    <div class="flex flex-wrap gap-3 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1" title="Carnet de Identidad">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg> 
                                            {{ $patient->ci }}
                                        </span>
                                        <span class="hidden sm:inline text-gray-300 dark:text-gray-600">|</span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg> 
                                            {{ $patient->age }} años
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('prescriptions.create', $patient->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2 transition transform hover:scale-105 ml-2">
                                <span class="hidden sm:inline">Seleccionar</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    {{-- Estado Vacío --}}
                    <div class="p-8 bg-yellow-50 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-400 rounded-lg border border-yellow-200 dark:border-yellow-800">
                        <svg class="w-12 h-12 mx-auto mb-3 text-yellow-500 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p class="font-bold text-lg mb-2">No se encontraron resultados</p>
                        <p class="mb-6 text-sm">No existe ningún paciente registrado con ese nombre o carnet.</p>
                        
                        <a href="{{ route('patients.create') }}" class="inline-flex items-center bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded-lg shadow transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            Registrar Nuevo Paciente
                        </a>
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app>