<x-app>
    {{-- ENCABEZADO --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Registrar Nueva Compra</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">Ingreso de mercadería al inventario.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-bold flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Volver al Inventario
        </a>
    </div>

    {{-- FORMULARIO --}}
    <form action="{{ route('purchases.store') }}" method="POST" id="purchaseForm">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- SECCIÓN 1: DATOS DEL PROVEEDOR --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <h3 class="font-bold text-lg text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Datos Generales</h3>
                    

                    <div class="mb-4">
    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Destino del Stock</label>
    
    @if(auth()->user()->role === 'admin')
        {{-- Si es ADMIN: Puede elegir a qué tienda va la mercadería --}}
        <select name="branch_id" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
            @foreach($branches as $branch)
                <option value="{{ $branch->id }}" {{ auth()->user()->branch_id == $branch->id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Selecciona dónde se almacenará esta compra.</p>
    @else
        {{-- Si es EMPLEADO: Se asigna forzosamente a su sucursal --}}
        <input type="text" value="{{ auth()->user()->branch->name }}" class="w-full border p-2 rounded bg-gray-100 dark:bg-gray-600 text-gray-500 dark:text-gray-300 cursor-not-allowed" readonly>
        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
    @endif
</div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Proveedor</label>
                        <input type="text" name="supplier" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ej: Distribuidora La Paz" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">Fecha de Compra</label>
                        <input type="date" name="purchase_date" value="{{ date('Y-m-d') }}" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" required>
                    </div>

                    <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded border border-blue-100 dark:border-blue-800">
                        <p class="text-xs text-blue-800 dark:text-blue-300 text-center">
                            <strong>Nota:</strong> Al registrar esta compra, el stock de los productos aumentará automáticamente y se actualizará el costo unitario.
                        </p>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN 2: DETALLE DE PRODUCTOS --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 dark:text-white">Detalle de Productos</h3>
                        <button type="button" onclick="addProductRow()" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-3 rounded flex items-center gap-1 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Agregar Fila
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 uppercase text-xs">
                                <tr>
                                    <th class="px-4 py-3 text-left w-1/3">Producto</th>
                                    <th class="px-4 py-3 text-center w-24">Stock Actual</th>
                                    <th class="px-4 py-3 text-right w-32">Costo Unit. (Bs)</th>
                                    <th class="px-4 py-3 text-center w-24">Cantidad</th>
                                    <th class="px-4 py-3 text-right w-32">Subtotal</th>
                                    <th class="px-4 py-3 text-center w-10"></th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody" class="dark:text-gray-300">
                                {{-- Aquí se insertan las filas con JS --}}
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700 font-bold text-gray-800 dark:text-white border-t dark:border-gray-600">
                                <tr>
                                    <td colspan="4" class="px-4 py-3 text-right text-base">TOTAL COMPRA:</td>
                                    <td class="px-4 py-3 text-right text-xl text-green-600 dark:text-green-400">
                                        Bs <span id="grandTotal">0.00</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    
                    {{-- Mensaje si no hay productos --}}
                    <div id="emptyMessage" class="p-8 text-center text-gray-400 dark:text-gray-500">
                        No has agregado productos a la compra.
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded shadow-lg transform transition hover:-translate-y-1">
                        GUARDAR COMPRA
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- SCRIPTS PARA LA LÓGICA --}}
    <script>
        let rowCount = 0;
        const productsList = @json($products); // Pasamos los productos de PHP a JS

        function addProductRow() {
            document.getElementById('emptyMessage').style.display = 'none';
            
            const tbody = document.getElementById('productsTableBody');
            const rowId = `row-${rowCount}`;
            
            // Crear opciones del select
            let optionsHtml = '<option value="">Seleccione...</option>';
            productsList.forEach(p => {
                optionsHtml += `<option value="${p.id}" data-stock="${p.stock}" data-cost="${p.price_buy || 0}">${p.code} - ${p.name}</option>`;
            });

            const row = document.createElement('tr');
            row.id = rowId;
            row.className = 'border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition';
            
            row.innerHTML = `
                <td class="px-2 py-2">
                    <select name="products[${rowCount}][id]" class="w-full border p-2 rounded text-xs dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none" onchange="updateRowInfo(this, '${rowId}')" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td class="px-2 py-2 text-center text-gray-500 dark:text-gray-400">
                    <span id="stock-${rowId}">-</span>
                </td>
                <td class="px-2 py-2">
                    <input type="number" name="products[${rowCount}][cost]" step="0.50" class="w-full text-right border p-2 rounded text-xs dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none" oninput="calculateRow('${rowId}')" placeholder="0.00" required>
                </td>
                <td class="px-2 py-2">
                    <input type="number" name="products[${rowCount}][quantity]" class="w-full text-center border p-2 rounded text-xs dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none font-bold text-blue-600 dark:text-blue-400" oninput="calculateRow('${rowId}')" placeholder="1" required>
                </td>
                <td class="px-2 py-2 text-right font-bold text-gray-800 dark:text-white">
                    Bs <span id="subtotal-${rowId}">0.00</span>
                </td>
                <td class="px-2 py-2 text-center">
                    <button type="button" onclick="removeRow('${rowId}')" class="text-red-400 hover:text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
            rowCount++;
        }

        function updateRowInfo(select, rowId) {
            const selectedOption = select.options[select.selectedIndex];
            const stock = selectedOption.getAttribute('data-stock');
            const lastCost = selectedOption.getAttribute('data-cost'); // Precio de compra sugerido (el último)

            // Actualizar stock visual
            document.getElementById(`stock-${rowId}`).innerText = stock || '-';
            
            // Sugerir costo (si el campo está vacío o es 0)
            const costInput = document.querySelector(`#${rowId} input[name*="[cost]"]`);
            if(costInput.value == '' || costInput.value == 0) {
                costInput.value = lastCost;
            }

            calculateRow(rowId);
        }

        function calculateRow(rowId) {
            const cost = parseFloat(document.querySelector(`#${rowId} input[name*="[cost]"]`).value) || 0;
            const qty = parseFloat(document.querySelector(`#${rowId} input[name*="[quantity]"]`).value) || 0;
            const subtotal = cost * qty;

            document.getElementById(`subtotal-${rowId}`).innerText = subtotal.toFixed(2);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            const subtotals = document.querySelectorAll('[id^="subtotal-"]');
            subtotals.forEach(span => {
                total += parseFloat(span.innerText);
            });
            document.getElementById('grandTotal').innerText = total.toFixed(2);
        }

        function removeRow(rowId) {
            document.getElementById(rowId).remove();
            calculateGrandTotal();
            
            // Si no quedan filas, mostrar mensaje
            if(document.getElementById('productsTableBody').children.length === 0) {
                document.getElementById('emptyMessage').style.display = 'block';
            }
        }

        // Iniciar con una fila vacía
        document.addEventListener('DOMContentLoaded', () => {
            addProductRow();
        });
    </script>
</x-app>