<div> <div class="h-screen flex flex-col md:flex-row bg-gray-100 dark:bg-gray-900 overflow-hidden transition-colors duration-300">
        
        <div class="w-full md:w-2/3 flex flex-col p-4">
            
            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-4 transition-colors">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </span>
                    <input wire:model.live="searchProduct" type="text" class="w-full py-3 pl-10 pr-4 bg-gray-50 dark:bg-gray-700 border-transparent dark:border-gray-600 rounded-lg focus:bg-white dark:focus:bg-gray-600 focus:border-blue-500 dark:text-white shadow-sm transition-colors" placeholder="Buscar producto por nombre o código..." autofocus>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-800 rounded-lg shadow p-2 transition-colors">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs uppercase sticky top-0 z-10">
                        <tr>
                            <th class="p-3">Imagen</th>
                            <th class="p-3">Descripción</th>
                            <th class="p-3 text-center">Código</th>
                            <th class="p-3 text-center">Stock</th>
                            <th class="p-3 text-right">Precio</th>
                            <th class="p-3 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 dark:text-gray-300">
                        @forelse($products as $product)
                        <tr class="border-b dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-gray-700 transition group">
                            <td class="p-3 w-16">
                                @if($product->image_path)
                                    <img src="{{ Storage::url($product->image_path) }}" class="w-12 h-12 object-cover rounded border dark:border-gray-600">
                                @else
                                    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 rounded flex items-center justify-center text-gray-400">📷</div>
                                @endif
                            </td>
                            <td class="p-3">
    <p class="font-bold text-gray-800 dark:text-white">{{ $product->name }}</p>
    <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($product->category->name ?? '', 20) }}</p>
</td>

<td class="p-3 text-center font-mono text-gray-600 dark:text-gray-400">{{ $product->code }}</td>

<td class="p-3 text-center">
    {{-- LÓGICA MULTI-SUCURSAL --}}
    @php
        // Usamos el accessor que creamos en el Modelo Product
        $stockReal = $product->stock_actual; 
    @endphp

    <span class="px-2 py-1 rounded-full text-xs font-bold {{ $stockReal < 10 ? 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300' : 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' }}">
        {{ $stockReal }}
    </span>
</td>
                            <td class="p-3 text-right font-bold text-blue-600 dark:text-blue-400">Bs {{ $product->price_sell }}</td>
                            <td class="p-3 text-center">
                                <button wire:click="addToCart({{ $product->id }})" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 shadow flex items-center gap-1 mx-auto text-xs transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Agregar
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400 dark:text-gray-500">
                                @if($searchProduct) No se encontraron productos. @else Usa el buscador. @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="w-full md:w-1/3 bg-white dark:bg-gray-800 border-l dark:border-gray-700 flex flex-col h-full shadow-2xl relative z-20 transition-colors">
            
            <div class="p-4 bg-blue-600 dark:bg-blue-800 text-white shadow transition-colors">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-xs opacity-75">Vendedor: {{ auth()->user()->name }}</span>
                    <span class="text-xs font-mono bg-blue-700 dark:bg-blue-900 px-2 py-1 rounded tracking-wider">
                        COMP-{{ time() }}
                    </span>
                </div>
                
                @if($selectedPatientName)
                    <div class="bg-blue-500 dark:bg-blue-700 p-2 rounded shadow-inner mb-2 flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-white truncate max-w-[150px]">{{ $selectedPatientName }}</span>
                            <button wire:click="$set('selectedPatientName', null); $set('patient_id', null)" class="text-xs text-blue-200 hover:text-white underline">Cambiar</button>
                        </div>
                        <button onclick="document.getElementById('modal-receta').showModal()" class="w-full bg-white text-blue-700 text-xs font-bold py-1.5 px-2 rounded shadow-sm hover:bg-blue-50 flex items-center justify-center gap-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Ingresar Receta
                        </button>
                    </div>
                @else
                    <div class="relative w-full"> 
                        <input wire:model.live="searchPatient" type="text" placeholder="Buscar Cliente..." class="w-full p-2 text-gray-900 rounded text-sm border-0 focus:ring-2 focus:ring-blue-300 placeholder-gray-400 dark:bg-gray-100" autocomplete="off">
                        @if(strlen($searchPatient) > 0)
                            <div class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 rounded-md shadow-xl overflow-hidden border border-gray-200 dark:border-gray-600">
                                @forelse($patients as $patient)
                                    <div wire:click="selectPatient({{ $patient->id }}, '{{ $patient->name }}')" class="px-4 py-2 hover:bg-blue-50 dark:hover:bg-gray-600 cursor-pointer border-b dark:border-gray-600 last:border-0 transition-colors text-sm">
                                        <p class="font-bold text-gray-800 dark:text-white">{{ $patient->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">CI: {{ $patient->ci ?? 'S/N' }}</p>
                                    </div>
                                @empty
                                    <div class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No encontrado. <a href="{{ route('patients.create') }}" target="_blank" class="text-blue-300 font-bold hover:underline">¿Crear?</a>
                                    </div>
                                @endforelse
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900 border-y border-gray-200 dark:border-gray-700">
                @forelse($cart as $id => $item)
                <div wire:key="cart-item-{{ $item['id'] }}" class="bg-white dark:bg-gray-800 p-3 mb-3 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 flex justify-between items-center transition hover:shadow-md">
                    <div class="flex-1 pr-2">
                        <h4 class="font-bold text-gray-800 dark:text-white leading-tight">{{ $item['name'] }}</h4>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 font-mono">
                            {{ $item['quantity'] }} x Bs {{ number_format($item['price'], 2) }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <div class="font-bold text-blue-700 dark:text-blue-400 text-lg">Bs {{ number_format($item['subtotal'], 2) }}</div>
                        <div class="flex items-center bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">
                            <button wire:click="decreaseQuantity({{ $item['id'] }})" class="px-3 py-1 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-white font-bold transition">-</button>
                            <span class="px-2 text-sm font-bold bg-white dark:bg-gray-800 text-gray-800 dark:text-white h-full flex items-center border-x border-gray-300 dark:border-gray-600">{{ $item['quantity'] }}</span>
                            <button wire:click="addToCart({{ $item['id'] }})" class="px-3 py-1 hover:bg-gray-200 dark:hover:bg-gray-600 text-blue-600 dark:text-blue-400 font-bold transition">+</button>
                        </div>
                    </div>
                    <button wire:click="removeFromCart({{ $item['id'] }})" class="ml-4 text-gray-400 hover:text-red-500 transition p-2 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 opacity-60">
                        <div class="bg-gray-200 dark:bg-gray-800 p-6 rounded-full mb-4">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <p class="font-medium text-lg">Tu carrito está vacío</p>
                    </div>
                @endforelse
            </div>

            <div class="bg-white dark:bg-gray-800 p-4 border-t border-gray-200 dark:border-gray-700 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)] z-30">
                
                <div class="mb-4 space-y-3 bg-gray-50 dark:bg-gray-700/50 p-3 rounded border border-gray-200 dark:border-gray-700">
                    <div class="flex gap-2">
                        <div class="w-1/2">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Fecha Entrega</label>
                            <input wire:model="delivery_date" type="datetime-local" class="w-full p-1.5 text-xs border rounded bg-white dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-blue-500">
                        </div>
                        <div class="w-1/2">
                            <label class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase">Observación</label>
                            <textarea wire:model="observations" rows="1" class="w-full p-1.5 text-xs border rounded bg-white dark:bg-gray-800 dark:text-white dark:border-gray-600 focus:ring-blue-500" placeholder="Detalles..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-2 text-lg">
                    <span class="font-bold text-gray-600 dark:text-gray-300">Total:</span>
                    <span class="font-bold text-2xl text-blue-800 dark:text-blue-400">Bs {{ number_format($total, 2) }}</span>
                </div>
                
                <div class="grid grid-cols-2 gap-2 mb-4">
    <div>
        <label class="text-[10px] font-bold uppercase text-gray-500 dark:text-gray-400">Método</label>
        <select wire:model.live="paymentMethod" class="w-full p-2 border rounded text-sm ...">
            <option value="Efectivo">Efectivo</option>
            <option value="QR">QR / Transferencia</option>
            <option value="Tarjeta">Tarjeta</option>
        </select>
    </div>

    <div>
        <label class="text-[10px] font-bold uppercase text-gray-500 dark:text-gray-400">Monto</label>
        <input wire:model.live="amountPaid" type="number" ... >
    </div>
</div>

@if($paymentMethod == 'QR' || $paymentMethod == 'Tarjeta')
    <div class="mb-4 bg-blue-50 dark:bg-blue-900/20 p-2 rounded border border-blue-100 dark:border-blue-800">
        <label class="text-[10px] font-bold uppercase text-blue-600 dark:text-blue-400">
            Nro. Comprobante / Referencia Bancaria
        </label>
        <input wire:model="paymentReference" type="text" 
               class="w-full p-2 border rounded text-sm uppercase font-mono tracking-wider focus:ring-blue-500" 
               placeholder="Ej: 12345678">
    </div>
@endif

                @php
                    $pagado = (float)$amountPaid;
                    $saldo = $total - $pagado;
                    $cambio = 0;
                    if($saldo < 0) { $cambio = abs($saldo); $saldo = 0; }
                @endphp

                <div class="space-y-1 mb-4 border-t border-dashed border-gray-300 dark:border-gray-600 pt-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Saldo Pendiente:</span>
                        <span class="font-bold {{ $saldo > 0 ? 'text-red-500 dark:text-red-400' : 'text-gray-400' }}">
                            Bs {{ number_format($saldo, 2) }}
                        </span>
                    </div>
                    @if($cambio > 0)
                    <div class="flex justify-between text-sm bg-blue-50 dark:bg-blue-900/30 p-1 rounded px-2">
                        <span class="font-bold text-blue-600 dark:text-blue-300">Cambio:</span>
                        <span class="font-bold text-blue-800 dark:text-blue-200">Bs {{ number_format($cambio, 2) }}</span>
                    </div>
                    @endif
                </div>

                <button onclick="confirmSale()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow text-lg transition transform active:scale-95 flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    CONFIRMAR VENTA
                </button>
            </div>
        </div>
    </div>

    <dialog id="modal-receta" class="p-0 rounded-lg shadow-2xl w-[600px] backdrop:bg-gray-900/60 dark:backdrop:bg-gray-900/80 bg-transparent" wire:ignore.self>
    <div class="bg-blue-600 text-white px-4 py-3 flex justify-between items-center rounded-t-lg">
        <h3 class="font-bold text-lg flex items-center gap-2">Registrar Receta Externa</h3>
        <button type="button" onclick="document.getElementById('modal-receta').close()" class="text-blue-200 hover:text-white text-xl">&times;</button>
    </div>
    
    <div class="p-6 bg-white dark:bg-gray-800 dark:text-white rounded-b-lg">
        
        {{-- ENCABEZADOS --}}
        <div class="grid grid-cols-6 gap-2 mb-2 text-center text-[10px] font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider">
            <div class="text-left pl-2">Ojo</div>
            <div>Esfera</div>
            <div>Cilindro</div>
            <div>Eje</div>
            <div>Adición</div>
            <div>D.I.P</div>
        </div>

        {{-- FILA OJO DERECHO (OD) --}}
        <div class="grid grid-cols-6 gap-2 mb-3 items-center">
            <div class="font-bold text-blue-600 dark:text-blue-400 text-sm pl-2">OD</div>
            
            {{-- Fíjate que ahora los wire:model coinciden con tus variables PHP --}}
            <input wire:model="od_esfera" type="number" step="0.25" placeholder="0.00" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            
            <input wire:model="od_cilindro" type="number" step="0.25" placeholder="0.00" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            
            <input wire:model="od_eje" type="number" placeholder="0°" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            
            <input wire:model="add_od" type="number" step="0.25" placeholder="+0.00" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            
            {{-- D.I.P (General) --}}
            <input wire:model="dip" type="number" placeholder="mm" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
        </div>

        {{-- FILA OJO IZQUIERDO (OI) --}}
        <div class="grid grid-cols-6 gap-2 mb-4 items-center">
            <div class="font-bold text-green-600 dark:text-green-400 text-sm pl-2">OI</div>
            
            <input wire:model="oi_esfera" type="number" step="0.25" placeholder="0.00" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none">
            
            <input wire:model="oi_cilindro" type="number" step="0.25" placeholder="0.00" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none">
            
            <input wire:model="oi_eje" type="number" placeholder="0°" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none">
            
            <input wire:model="add_oi" type="number" step="0.25" placeholder="+0.00" 
                class="w-full p-2 text-center border rounded text-sm bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none">
            
            <div class="text-center text-xs text-gray-400 dark:text-gray-500">-</div>
        </div>

        {{-- DIAGNÓSTICO Y OBSERVACIONES --}}
        <div class="grid grid-cols-1 gap-3 mb-4">
            <div>
                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Diagnóstico (Opcional)</label>
                <input wire:model="diagnostico" type="text" class="w-full mt-1 p-2 text-sm border rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 outline-none" placeholder="Ej: Miopía leve...">
            </div>
            
            <div>
                <label class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase">Observaciones / Tipo de Lente</label>
                {{-- Aquí corregimos para usar tu variable PHP --}}
                <textarea wire:model="observaciones_receta" rows="2" class="w-full mt-1 p-2 text-sm border rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 outline-none" placeholder="Ej: Material fotocromático..."></textarea>
            </div>
        </div>
        
        {{-- BOTONES --}}
        <div class="flex justify-end gap-3 pt-4 border-t dark:border-gray-700">
            <button onclick="document.getElementById('modal-receta').close()" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded font-bold text-sm">
                Cancelar
            </button>
            <button wire:click="savePrescription" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded font-bold text-sm shadow">
                Guardar Receta
            </button>
        </div>
    </div>
</dialog>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('close-prescription-modal', () => document.getElementById('modal-receta').close());
            
            Livewire.on('sale-success', (event) => {
                const inputMonto = document.getElementById('inputMonto');
                if(inputMonto) inputMonto.value = ''; 
                
                Swal.fire({ title: '¡Venta Exitosa!', icon: 'success', timer: 1500, showConfirmButton: false });

                // Detectar ID de venta
                let saleId = null;
                if(Array.isArray(event) && event[0].saleId) saleId = event[0].saleId;
                else if (event.saleId) saleId = event.saleId;
                else if (event.detail && event.detail.saleId) saleId = event.detail.saleId;

                if(saleId) setTimeout(() => window.open('/ventas/' + saleId + '/imprimir', '_blank'), 1000);
            });
        });

        function confirmSale() {
            Swal.fire({
                title: '¿Confirmar?',
                text: "Verifica los datos antes de procesar.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, vender'
            }).then((result) => {
                if (result.isConfirmed) Livewire.dispatch('confirmSale');
            })
        }
    </script>
</div>