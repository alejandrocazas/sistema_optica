<x-app>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Gestión de Ventas</h1>
            <p class="text-gray-600 dark:text-gray-400">Control de entregas, observaciones y saldos.</p>
        </div>
        
        {{-- BOTÓN NUEVA VENTA CON VALIDACIÓN DE CAJA --}}
        @if($hasOpenRegister)
            {{-- Si tiene caja abierta, va directo a crear venta --}}
            <a href="{{ route('sales.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 transition transform hover:-translate-y-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Nueva Venta
            </a>
        @else
            {{-- Si NO tiene caja, mostramos alerta con SweetAlert --}}
            <button onclick="alertNoCaja()" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center gap-2 cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Nueva Venta (Caja Cerrada)
            </button>
        @endif
    </div>

    @if($sales->isEmpty())
        <div class="bg-white dark:bg-gray-800 p-10 rounded-lg shadow text-center">
            <p class="text-xl mb-2 text-gray-600 dark:text-gray-300">No hay ventas registradas aún.</p>
            <p class="text-gray-500 dark:text-gray-400">¡Realiza la primera venta del día!</p>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Comprobante
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Cliente / Obs.
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Entrega Programada
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Estado Trabajo
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Pago
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-right text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Total
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    
                    <tbody class="text-gray-700 dark:text-gray-300">
                        @foreach($sales as $sale)
                        
                        @php
                            $statusStyles = [
                                'pendiente' => 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-gray-700 dark:text-gray-300',
                                'laboratorio' => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/50 dark:text-yellow-200',
                                'listo' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/50 dark:text-blue-200',
                                'entregado' => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/50 dark:text-green-200',
                                'cancelado' => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/50 dark:text-red-200',
                            ];
                            $currentStyle = $statusStyles[$sale->status] ?? $statusStyles['pendiente'];
                        @endphp

                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition border-b dark:border-gray-700">
                            
                            <td class="px-5 py-4 text-sm">
                                <p class="font-bold font-mono text-gray-900 dark:text-white">{{ $sale->receipt_number }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
                                <p class="text-[10px] text-gray-400 uppercase mt-1">Vend: {{ strtok($sale->user->name, " ") }}</p>
                            </td>

                            <td class="px-5 py-4 text-sm max-w-xs">
                                <div class="font-bold text-gray-900 dark:text-white mb-1">{{ $sale->patient->name ?? 'Cliente General' }}</div>
                                
                                @if($sale->observations)
                                    <div class="text-xs bg-yellow-50 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 p-1 rounded border border-yellow-200 dark:border-yellow-800/50" title="{{ $sale->observations }}">
                                        Obs: {{ Str::limit($sale->observations, 30) }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sin observaciones</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if($sale->status == 'entregado' || $sale->status == 'cancelado')
                                    <span class="text-xs text-gray-500">
                                        {{ $sale->delivery_date ? $sale->delivery_date->format('d/m/Y H:i') : '--' }}
                                    </span>
                                @else
                                    <form action="{{ route('sales.updateDate', $sale->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input 
                                            type="datetime-local" 
                                            name="delivery_date" 
                                            value="{{ $sale->delivery_date ? $sale->delivery_date->format('Y-m-d\TH:i') : '' }}"
                                            class="text-xs border border-gray-300 dark:border-gray-600 rounded p-1 w-32 bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-blue-500"
                                            onchange="this.form.submit()"
                                            title="Cambia la fecha para guardar automáticamente"
                                        >
                                    </form>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $currentStyle }}">
                                    {{ ucfirst($sale->status) }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if($sale->balance > 0)
                                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-2 py-1 rounded inline-block">
                                        <p class="font-bold text-[10px] uppercase">Pendiente</p>
                                        <p class="font-bold text-xs">Bs {{ number_format($sale->balance, 2) }}</p>
                                    </div>
                                @else
                                    <span class="px-2 py-1 inline-flex text-xs font-semibold rounded bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800">
                                        Pagado
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right font-bold text-gray-800 dark:text-white">
                                Bs {{ number_format($sale->total, 2) }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                <div class="flex item-center justify-center gap-2">
                                    
                                    {{-- Botón IMPRIMIR (Siempre visible) --}}
                                    <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="p-2 rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-300 transition" title="Imprimir Recibo">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </a>

                                    {{-- === LÓGICA DE BLOQUEO (Editar Observaciones y Anular) === --}}
                                    
                                    @if($sale->status === 'entregado' || $sale->status === 'cancelado')
                                        {{-- ESTADO BLOQUEADO: Muestra íconos grises sin acción --}}
                                        
                                        {{-- Editar Obs Bloqueado --}}
                                        <button class="p-2 rounded-full text-gray-300 dark:text-gray-600 cursor-not-allowed" title="Venta cerrada (No se pueden editar observaciones)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Anular Bloqueado --}}
                                        <button class="p-2 rounded-full text-gray-300 dark:text-gray-600 cursor-not-allowed" title="Venta cerrada (No se puede anular)">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </button>

                                    @else
                                        {{-- ESTADO ACTIVO: Botones funcionan --}}

                                        {{-- Editar Obs Activo --}}
                                        <button onclick="openObsModal('{{ $sale->id }}', '{{ $sale->observations }}')" class="p-2 rounded-full hover:bg-yellow-100 dark:hover:bg-yellow-900 text-gray-500 dark:text-gray-400 hover:text-yellow-600 dark:hover:text-yellow-300 transition" title="Editar Observaciones">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Anular Activo (Solo Admin) --}}
                                        @if(auth()->user()->role === 'admin')
                                            <button onclick="confirmAnnulment('{{ $sale->id }}', '{{ $sale->receipt_number }}')" class="p-2 rounded-full hover:bg-red-100 dark:hover:bg-red-900 text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-300 transition" title="Anular y Restaurar Stock">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>

                                            <form id="delete-form-{{ $sale->id }}" action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display: none;">
                                                @csrf @method('DELETE')
                                                <input type="hidden" name="reason" id="reason-{{ $sale->id }}">
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if(method_exists($sales, 'links'))
                <div class="px-5 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                    {{ $sales->links() }}
                </div>
            @endif

        </div>
    @endif

    {{-- MODAL OBSERVACIONES --}}
    <dialog id="obsModal" class="p-6 rounded-lg shadow-xl border w-96 dark:bg-gray-800 dark:text-white backdrop:bg-gray-900/50">
        <h3 class="font-bold text-lg mb-4">Editar Observaciones</h3>
        <form id="obsForm" method="POST">
            @csrf @method('PATCH')
            <textarea name="observations" id="obsText" rows="4" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 focus:ring-blue-500" placeholder="Escribe detalles del lente..."></textarea>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" onclick="document.getElementById('obsModal').close()" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded text-gray-800 dark:text-white font-bold">Cancelar</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700">Guardar</button>
            </div>
        </form>
    </dialog>

    <script>
        // Alerta si no hay caja abierta
        function alertNoCaja() {
            Swal.fire({
                title: '¡Caja Cerrada!',
                text: "Debes abrir tu caja antes de realizar ventas.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ir a Caja',
                cancelButtonText: 'Cerrar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('cash.index') }}"; // Redirige a la gestión de caja
                }
            });
        }

        // Modal Observaciones
        function openObsModal(id, obs) {
            const modal = document.getElementById('obsModal');
            const form = document.getElementById('obsForm');
            const textarea = document.getElementById('obsText');
            form.action = `/ventas/${id}/observaciones`; 
            textarea.value = obs || '';
            modal.showModal();
        }
    
        // Modal Anulación
        function confirmAnnulment(id, receipt) {
            Swal.fire({
                title: '¿Anular Venta ' + receipt + '?',
                text: "Esta acción devolverá los productos al stock. Es OBLIGATORIO indicar el motivo.",
                icon: 'warning',
                input: 'textarea',
                inputPlaceholder: 'Ej: Error de tipeo, devolución del cliente...',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, anular venta',
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('¡Necesitas escribir una justificación para auditoría!')
                    }
                    return reason
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reason-' + id).value = result.value;
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-app>