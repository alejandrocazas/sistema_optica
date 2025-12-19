<x-app>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Control de Caja y Entregas</h1>
        <div class="text-sm text-gray-500 dark:text-gray-400">
            {{ now()->format('d M Y') }}
        </div>
    </div>

    @if(!$currentRegister)
        {{-- VISTA: CAJA CERRADA --}}
        <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-8 rounded shadow mb-8">
            <h2 class="text-2xl font-bold text-red-700 dark:text-red-400 mb-2">🔴 Caja Cerrada</h2>
            <p class="mb-4 text-gray-600 dark:text-gray-300">Para comenzar a operar, ingrese el monto de cambio inicial.</p>
            
            <form id="form-open-box" action="{{ route('cash.open') }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end">
                @csrf
                <div class="w-full sm:w-64">
                    <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1">Monto Inicial (Bs)</label>
                    <input type="number" name="amount" class="border p-3 rounded w-full text-lg font-bold text-gray-700 dark:text-white dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-green-500 outline-none" placeholder="0.00" required>
                </div>
                <button onclick="confirmAction(event, 'form-open-box', '¿Abrir Caja?', 'Se registrará el inicio de operaciones del día.', 'Sí, abrir caja')" 
                        class="w-full sm:w-auto bg-green-600 text-white font-bold py-3 px-8 rounded hover:bg-green-700 shadow-lg transition transform hover:-translate-y-1">
                    ABRIR CAJA
                </button>
            </form>
        </div>
    @else
        {{-- VISTA: CAJA ABIERTA (DASHBOARD) --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            
            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow border-l-4 border-blue-500">
                <h3 class="font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Fondo Inicial</h3>
                <p class="text-2xl font-bold text-blue-700 dark:text-blue-400 mt-1">Bs {{ number_format($currentRegister->opening_amount, 2) }}</p>
                <div class="text-xs text-gray-400 mt-1">
                    {{ $currentRegister->opened_at ? $currentRegister->opened_at->format('H:i A') : 'Hora desconocida' }}
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow border-l-4 border-green-500">
                <h3 class="font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Ventas del Turno</h3>
                <p class="text-2xl font-bold text-green-700 dark:text-green-400 mt-1">+ Bs {{ number_format($totalIngresos ?? 0, 2) }}</p>
                <div class="text-xs text-gray-400 mt-1">Efectivo, QR y Tarjeta</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded shadow border-l-4 border-red-500 relative group">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-bold text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider">Gastos / Salidas</h3>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-400 mt-1">- Bs {{ number_format($totalEgresos ?? 0, 2) }}</p>
                    </div>
                    <button onclick="document.getElementById('modal-gasto').showModal()" class="text-red-500 hover:bg-red-50 dark:hover:bg-red-900/30 p-2 rounded-full transition" title="Registrar Gasto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </button>
                </div>
            </div>

            <div class="bg-gray-800 dark:bg-black p-6 rounded shadow border-l-4 border-gray-600 text-white flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-gray-400 text-xs uppercase tracking-wider">Total Estimado</h3>
                    <p class="text-3xl font-bold mt-1">Bs {{ number_format(($currentRegister->opening_amount + ($totalIngresos ?? 0)) - ($totalEgresos ?? 0), 2) }}</p>
                </div>
                
                <form id="form-close-box" action="{{ route('cash.close') }}" method="POST" target="_blank"> 
                    @csrf
                    <button onclick="closeRegister(event)" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-bold text-sm transition">
                        CERRAR CAJA Y REPORTAR
                    </button>
                </form>
            </div>
        </div>
    @endif

    {{-- TABLA DE PENDIENTES --}}
    {{-- TABLA DE PENDIENTES --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 font-bold text-gray-700 dark:text-gray-200 flex justify-between items-center">
            <span>📦 Trabajos Pendientes de Entrega / Cobro</span>
            <span class="text-xs font-normal bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded-full">{{ $pendingWorks->total() }} pendientes</span>
        </div>
        
        @if($pendingWorks->isEmpty())
            <div class="p-10 text-center text-gray-400 dark:text-gray-500">
                <p>¡Todo al día! No hay trabajos pendientes ni saldos por cobrar.</p>
            </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="text-xs uppercase bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-b dark:border-gray-700">
                        <th class="px-4 py-3 text-left">Comprobante</th>
                        <th class="px-4 py-3 text-left">Cliente</th>
                        <th class="px-4 py-3 text-center">Estado Trabajo</th>
                        <th class="px-4 py-3 text-right">Saldo Pendiente</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    @foreach($pendingWorks as $work)
                    <tr class="border-b dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-4 font-mono font-bold text-blue-600 dark:text-blue-400">{{ $work->receipt_number }}</td>
                        <td class="px-4 py-4">
                            <div class="font-bold text-gray-800 dark:text-white">{{ $work->patient->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $work->created_at->format('d/m/Y') }}</div>
                        </td>
                        
                        <td class="px-4 py-4 text-center">
                            @if($work->status == 'laboratorio')
                                <span class="bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-200 text-xs px-2 py-1 rounded-full font-bold border border-yellow-200 dark:border-yellow-800">
                                    🔨 En Taller
                                </span>
                                <br>
                                <a href="{{ route('work.status', [$work->id, 'listo']) }}" class="text-xs text-blue-600 dark:text-blue-400 font-bold hover:underline mt-2 block">
                                    → Marcar Listo
                                </a>
                            @elseif($work->status == 'listo')
                                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded-full font-bold border border-blue-200 dark:border-blue-800 animate-pulse">
                                    ✅ Listo para recoger
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-right">
                            @if($work->balance > 0)
                                <span class="text-red-600 dark:text-red-400 font-bold text-lg block mb-1">
                                    Bs {{ number_format($work->balance, 2) }}
                                </span>
                                
                                @if($currentRegister)
                                    <button onclick="openPayModal('{{ route('work.pay', $work->id) }}', '{{ $work->balance }}', '{{ $work->patient->name }}', '{{ $work->receipt_number }}')"
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs px-4 py-2 rounded shadow flex items-center gap-2 ml-auto transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Cobrar
                                    </button>
                                @endif
                            @else
                                <div class="text-green-600 dark:text-green-400 font-bold text-xs bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded border border-green-200 dark:border-green-800 inline-block">
                                    PAGADO TOTAL
                                </div>
                            @endif
                        </td>

                        <td class="px-4 py-4 text-center">
                            @if($work->status == 'listo' && $work->balance == 0)
                                <a href="#" onclick="confirmDelivery(event, '{{ route('work.status', [$work->id, 'entregado']) }}')" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 font-bold text-xs inline-flex items-center gap-1 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    ENTREGAR
                                </a>
                            @elseif($work->status == 'listo' && $work->balance > 0)
                                <span class="text-xs text-red-400 font-bold bg-red-50 dark:bg-red-900/20 px-2 py-1 rounded">
                                    ⛔ Pagar saldo primero
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">Esperando...</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="px-5 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
            {{ $pendingWorks->links() }}
        </div>
        
        @endif
    </div>

    {{-- MODAL GASTOS --}}
    <dialog id="modal-gasto" class="p-6 rounded-lg shadow-xl border w-96 backdrop:bg-gray-900/50 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        <h3 class="font-bold text-lg mb-4 text-gray-800 dark:text-white">Registrar Egreso de Caja</h3>
        <form action="{{ route('cash.expense') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-bold mb-1 text-gray-600 dark:text-gray-300">Monto</label>
                <div class="relative">
                    <span class="absolute left-3 top-2 text-gray-500">Bs</span>
                    <input type="number" name="amount" step="0.50" class="w-full border p-2 pl-8 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-red-500 outline-none" required>
                </div>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-bold mb-1 text-gray-600 dark:text-gray-300">Motivo</label>
                <input type="text" name="description" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-red-500 outline-none" placeholder="Ej: Compra de limpieza" required>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('modal-gasto').close()" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 rounded hover:bg-gray-300 dark:hover:bg-gray-500 font-bold text-gray-600 dark:text-gray-200">Cancelar</button>
                <button class="px-4 py-2 bg-red-600 text-white rounded font-bold hover:bg-red-700 shadow">Registrar</button>
            </div>
        </form>
    </dialog>

    {{-- MODAL COBROS --}}
    <dialog id="paymentModal" class="p-0 rounded-lg shadow-2xl border-0 w-[400px] backdrop:bg-gray-900/70 dark:bg-gray-800 dark:text-white">
        <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b dark:border-gray-600 flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white">Registrar Cobro</h3>
            <button onclick="closePayModal()" class="text-gray-400 hover:text-red-500 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="paymentForm" method="POST" class="p-6">
            @csrf
            
            <div class="mb-6 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400" id="payClientName">Cliente...</p>
                <p class="text-xs text-gray-400" id="payReceipt">Comprobante...</p>
                <div class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">
                    Bs <span id="payAmountDisplay">0.00</span>
                </div>
                <p class="text-xs text-red-500 font-bold mt-1 uppercase">Saldo Pendiente</p>
            </div>

            <input type="hidden" name="amount" id="payAmountInput">

            <div class="mb-5">
                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-300">Método de Pago</label>
                <select name="method" id="payMethod" onchange="togglePaymentFields()" class="w-full border p-2 rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="Efectivo">💵 Efectivo</option>
                    <option value="QR">📱 QR / Transferencia</option>
                </select>
            </div>

            <div id="cashFields" class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded border border-blue-100 dark:border-blue-800">
                <div class="mb-3">
                    <label class="block text-xs font-bold text-blue-800 dark:text-blue-300 mb-1">Monto Recibido (Billete)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">Bs</span>
                        <input type="number" step="0.50" id="cashReceived" onkeyup="calculateChange()" class="w-full pl-8 p-2 border rounded dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 outline-none font-bold text-gray-800 dark:text-white" placeholder="0.00">
                    </div>
                </div>
                <div class="flex justify-between items-center border-t border-blue-200 dark:border-blue-800 pt-2 mt-2">
                    <span class="text-sm font-bold text-gray-600 dark:text-gray-400">Cambio a devolver:</span>
                    <span class="text-xl font-bold text-green-600 dark:text-green-400" id="cashChange">Bs 0.00</span>
                </div>
            </div>

            <div id="qrFields" class="hidden bg-purple-50 dark:bg-purple-900/20 p-4 rounded border border-purple-100 dark:border-purple-800">
                <label class="block text-xs font-bold text-purple-800 dark:text-purple-300 mb-1">Nro. Comprobante / Referencia</label>
                <input type="text" name="reference_number" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 outline-none text-sm" placeholder="Ej: 12345678">
            </div>

            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closePayModal()" class="flex-1 px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded font-bold hover:bg-gray-300 transition">
                    Cancelar
                </button>
                
                {{-- Botón manual para activar SweetAlert --}}
                <button type="button" onclick="confirmPayment()" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded font-bold hover:bg-blue-700 shadow-lg transition transform hover:-translate-y-1">
                    Confirmar Cobro
                </button>
            </div>
        </form>
    </dialog>

    <script>
        // Variables globales
        let currentDebt = 0;

        // CORRECCIÓN: Ahora recibimos 'url' en vez de 'id'
        function openPayModal(url, amount, name, receipt) {
            const modal = document.getElementById('paymentModal');
            const form = document.getElementById('paymentForm');
            
            // 1. Configurar datos visuales
            document.getElementById('payClientName').innerText = name;
            document.getElementById('payReceipt').innerText = 'Recibo: ' + receipt;
            document.getElementById('payAmountDisplay').innerText = parseFloat(amount).toFixed(2);
            
            // 2. Configurar datos del formulario
            currentDebt = parseFloat(amount);
            document.getElementById('payAmountInput').value = amount;
            
            // 3. Resetear campos
            document.getElementById('payMethod').value = 'Efectivo';
            document.getElementById('cashReceived').value = '';
            document.getElementById('cashChange').innerText = 'Bs 0.00';
            togglePaymentFields(); 

            // 4. Asignar la URL exacta generada por Laravel Blade
            form.action = url; 

            modal.showModal();
        }

        function closePayModal() {
            document.getElementById('paymentModal').close();
        }

        function togglePaymentFields() {
            const method = document.getElementById('payMethod').value;
            const cashDiv = document.getElementById('cashFields');
            const qrDiv = document.getElementById('qrFields');
            const receivedInput = document.getElementById('cashReceived');

            if (method === 'Efectivo') {
                cashDiv.classList.remove('hidden');
                qrDiv.classList.add('hidden');
                receivedInput.required = true; 
                setTimeout(() => receivedInput.focus(), 100); 
            } else {
                cashDiv.classList.add('hidden');
                qrDiv.classList.remove('hidden');
                receivedInput.required = false;
            }
        }

        function calculateChange() {
            const received = parseFloat(document.getElementById('cashReceived').value) || 0;
            const change = received - currentDebt;
            const changeDisplay = document.getElementById('cashChange');
            
            if (change < 0) {
                changeDisplay.innerText = "Faltan Bs " + Math.abs(change).toFixed(2);
                changeDisplay.className = "text-xl font-bold text-red-500";
            } else {
                changeDisplay.innerText = "Bs " + change.toFixed(2);
                changeDisplay.className = "text-xl font-bold text-green-600 dark:text-green-400";
            }
        }

        function confirmPayment() {
            const method = document.getElementById('payMethod').value;
            const received = parseFloat(document.getElementById('cashReceived').value) || 0;
            
            if (method === 'Efectivo' && received < currentDebt) {
                Swal.fire({
                    target: document.getElementById('paymentModal'), // Importante para que se vea sobre el modal
                    icon: 'error',
                    title: 'Monto Insuficiente',
                    text: 'El dinero recibido es menor al saldo pendiente.',
                    confirmButtonColor: '#d33'
                });
                return;
            }

            Swal.fire({
                target: document.getElementById('paymentModal'), // Importante
                title: '¿Confirmar Cobro?',
                text: `Se registrará un ingreso de Bs ${currentDebt.toFixed(2)}`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, procesar pago',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('paymentForm').submit();
                }
            });
        }

        // Scripts existentes
        function confirmDelivery(event, url) {
            event.preventDefault();
            Swal.fire({
                title: '¿Confirmar Entrega?',
                text: "El trabajo saldrá del inventario de pendientes.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, entregar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        function closeRegister(event) {
            event.preventDefault();
            Swal.fire({
                title: '¿Cerrar Caja?',
                text: "Se generará el reporte final y se cerrará el turno.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, cerrar caja'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-close-box').submit();
                    setTimeout(() => {
                        location.reload();
                    }, 4000);
                }
            });
        }
    </script>
</x-app>