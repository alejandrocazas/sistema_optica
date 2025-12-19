<x-app>
    {{-- ENCABEZADO --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Historial de Compras</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Registro de ingresos de mercancía al inventario.</p>
        </div>
        
        <a href="{{ route('purchases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Registrar Nueva Compra
        </a>
    </div>

    {{-- TABLA DE COMPRAS --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Fecha y Hora
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Registrado Por
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Sucursal Destino
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Total Compra
                        </th>
                        {{-- <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Acciones
                        </th> --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($purchases as $purchase)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition bg-white dark:bg-gray-800">
                        
                        {{-- FECHA --}}
                        <td class="px-5 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-300 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-gray-900 dark:text-white font-bold whitespace-no-wrap">
                                        {{ $purchase->created_at->format('d/m/Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $purchase->created_at->format('H:i A') }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- USUARIO --}}
                        <td class="px-5 py-4 text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-8 h-8 bg-gray-200 dark:bg-gray-600 rounded-full flex items-center justify-center text-xs font-bold uppercase text-gray-600 dark:text-gray-300">
                                    {{ substr($purchase->user->name ?? '?', 0, 2) }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-gray-900 dark:text-white whitespace-no-wrap">
                                        {{ $purchase->user->name ?? 'Desconocido' }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- SUCURSAL (Si usas Multitenantable, esto mostrará la sucursal donde se guardó) --}}
                        <td class="px-5 py-4 text-sm">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 border border-blue-200 dark:border-blue-800">
                                {{ $purchase->branch->name ?? 'General' }}
                            </span>
                        </td>

                        {{-- TOTAL --}}
                        <td class="px-5 py-4 text-sm text-right">
                            <span class="text-lg font-bold text-gray-800 dark:text-white">
                                Bs {{ number_format($purchase->total_cost, 2) }}
                            </span>
                        </td>

                        {{-- ACCIONES (Opcional, si agregas una vista de detalle en el futuro) --}}
                        {{-- <td class="px-5 py-4 text-center text-sm">
                            <a href="#" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-bold">Ver Detalle</a>
                        </td> --}}
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <p>No se han registrado compras aún.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        @if(method_exists($purchases, 'links'))
            <div class="px-5 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</x-app>