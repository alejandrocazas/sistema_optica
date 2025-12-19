<x-app>
    <div class="max-w-6xl mx-auto">
        
        {{-- TARJETA DE PACIENTE --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8 flex flex-col md:flex-row justify-between items-center gap-4 border-l-4 border-blue-500 dark:border-blue-600 transition-colors">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    {{ $patient->name }}
                </h1>
                <div class="mt-2 text-gray-600 dark:text-gray-300 flex flex-wrap gap-4 text-sm font-medium">
                    <span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">CI: {{ $patient->ci }}</span>
                    <span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">Edad: {{ $patient->age }} años</span>
                    <span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                        {{ $patient->phone }}
                    </span>
                </div>
            </div>
            
            <a href="{{ route('prescriptions.create', $patient->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-lg flex items-center gap-2 transition transform hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nueva Consulta
            </a>
        </div>

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200">Historial Médico</h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $prescriptions->count() }} registros encontrados</span>
        </div>

        @if($prescriptions->isEmpty())
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 text-yellow-700 dark:text-yellow-400 p-6 rounded-lg shadow" role="alert">
                <p class="font-bold text-lg mb-1">Sin historial</p>
                <p>Este paciente aún no tiene consultas registradas en el sistema.</p>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                    Diagnóstico
                                </th>
                                <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                    Especialista
                                </th>
                                <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($prescriptions as $recipe)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition bg-white dark:bg-gray-800">
                                
                                <td class="px-5 py-4 text-sm">
                                    <p class="text-gray-900 dark:text-white font-mono font-bold">
                                        {{ $recipe->created_at->format('d/m/Y') }}
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 text-xs">
                                        {{ $recipe->created_at->format('H:i') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-sm">
                                    <span class="inline-block bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase mb-1 border border-green-200 dark:border-green-800">
                                        {{ $recipe->diagnostico ?? 'General' }}
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate w-64 italic" title="{{ $recipe->observaciones }}">
                                        {{ $recipe->observaciones }}
                                    </p>
                                </td>

                                <td class="px-5 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center text-[10px] font-bold text-gray-600 dark:text-gray-300">
                                            {{ substr($recipe->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300 font-medium text-xs">
                                            {{ $recipe->user->name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-sm text-center">
                                    <div class="flex justify-center items-center gap-2">
                                        
                                        {{-- Imprimir --}}
                                        <a href="{{ route('prescriptions.print', $recipe->id) }}" target="_blank" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition" title="Imprimir PDF">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('prescriptions.edit', $recipe->id) }}" class="p-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition" title="Editar Receta">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        {{-- Eliminar --}}
                                        <form action="{{ route('prescriptions.destroy', $recipe->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar esta receta permanentemente?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Eliminar del Historial">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app>