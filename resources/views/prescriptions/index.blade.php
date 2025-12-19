<x-app>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Reporte General de Atenciones</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Listado completo de todas las consultas realizadas en la óptica.</p>
        </div>
        
        <a href="{{ route('prescriptions.selectPatient') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Nueva Consulta
        </a>
    </div>

    @if($prescriptions->isEmpty())
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 text-blue-700 dark:text-blue-300 p-6 rounded-lg shadow" role="alert">
            <p class="font-bold text-lg mb-1">Sin registros</p>
            <p>Aún no se han realizado consultas oftalmológicas en el sistema.</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Fecha / Hora
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Paciente
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Diagnóstico
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Atendido Por
                            </th>
                            <th class="px-5 py-4 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-bold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($prescriptions as $prescription)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition bg-white dark:bg-gray-800">
                            
                            <td class="px-5 py-4 text-sm">
                                <p class="text-gray-900 dark:text-white font-bold whitespace-no-wrap font-mono">
                                    {{ $prescription->created_at->format('d/m/Y') }}
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">
                                    {{ $prescription->created_at->format('H:i A') }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 rounded-full flex items-center justify-center font-bold uppercase text-xs border dark:border-blue-800">
                                        {{ substr($prescription->patient->name, 0, 2) }}
                                    </div>
                                    <div class="ml-3">
                                        <a href="{{ route('prescriptions.history', $prescription->patient_id) }}" class="text-gray-900 dark:text-white font-bold hover:text-blue-600 dark:hover:text-blue-400 hover:underline transition">
                                            {{ $prescription->patient->name }}
                                        </a>
                                        <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5 font-mono">
                                            CI: {{ $prescription->patient->ci }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <td class="px-5 py-4 text-sm">
                                <span class="inline-block bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wide border border-green-200 dark:border-green-800">
                                    {{ $prescription->diagnostico ?? 'General' }}
                                </span>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1 truncate w-48 italic" title="{{ $prescription->observaciones }}">
                                    {{ $prescription->observaciones }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="text-gray-600 dark:text-gray-300 font-medium text-xs bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full border dark:border-gray-600">
                                    {{ $prescription->user->name }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center items-center gap-3">
                                    <a href="{{ route('prescriptions.print', $prescription->id) }}" target="_blank" class="p-2 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition" title="Imprimir Receta">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>
                                    
                                    <a href="{{ route('prescriptions.history', $prescription->patient_id) }}" class="p-2 text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-200 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-full transition" title="Ver Historial Clínico">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- PAGINACIÓN --}}
            @if(method_exists($prescriptions, 'links'))
                <div class="px-5 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                    {{ $prescriptions->links() }}
                </div>
            @endif
        </div>
    @endif
</x-app>