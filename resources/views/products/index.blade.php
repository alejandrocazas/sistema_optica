<x-app>
    {{-- ENCABEZADO Y BOTONES --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Inventario de Productos</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                Gestiona el catálogo y existencias de: <span class="font-bold text-blue-600 dark:text-blue-400">{{ auth()->user()->branch->name ?? 'Todas las Sucursales' }}</span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-2">
            {{-- Buscador --}}
            <form action="{{ route('products.index') }}" method="GET" class="relative mr-2">
                <input type="text" name="search" placeholder="Buscar producto..." value="{{ request('search') }}" 
                    class="pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white placeholder-gray-400 shadow-sm transition">
                <svg class="w-4 h-4 absolute left-3 top-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>

            <button onclick="document.getElementById('inventoryModal').showModal()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded shadow transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Inventario Físico
            </button>
            
            <a href="{{ route('categories.index') }}" class="bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold py-2 px-4 rounded border border-gray-300 dark:border-gray-600 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Categorías
            </a>
            
            <a href="{{ route('products.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg transition flex items-center gap-2 transform hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nuevo Producto
            </a>
        </div>
    </div>

    {{-- TABLA DE PRODUCTOS --}}
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Producto
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Categoría
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Precio Venta
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Stock (Sucursal)
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-700 text-center text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($products as $product)
                    
                    @php
                        // 1. OBTENEMOS EL STOCK DE LA SUCURSAL ACTUAL USANDO EL ACCESOR DEL MODELO
                        $currentStock = $product->stock_actual; 

                        // 2. Definimos el color basado en ese stock real
                        $stockClass = match(true) {
                            $currentStock < 5 => 'bg-red-100 text-red-800 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800',
                            $currentStock < 15 => 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-300 dark:border-yellow-800',
                            default => 'bg-green-100 text-green-800 border-green-200 dark:bg-green-900/30 dark:text-green-300 dark:border-green-800'
                        };
                    @endphp

                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition bg-white dark:bg-gray-800">
                        
                        {{-- IMAGEN Y NOMBRE --}}
                        <td class="px-5 py-4 text-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-16 h-16 relative">
                                    @if($product->image_path)
                                        <img class="w-full h-full rounded-lg object-cover border dark:border-gray-600 shadow-sm" src="{{ Storage::url($product->image_path) }}" alt="{{ $product->name }}" />
                                    @else
                                        <div class="w-full h-full rounded-lg bg-gray-100 dark:bg-gray-700 flex flex-col items-center justify-center text-gray-400 dark:text-gray-500 border dark:border-gray-600">
                                            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="text-[9px]">Sin Foto</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <p class="text-gray-900 dark:text-white font-bold text-base leading-tight">{{ $product->name }}</p>
                                    <div class="flex flex-col gap-0.5 mt-1">
                                        <span class="text-xs text-gray-500 dark:text-gray-400 font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded w-fit">
                                            COD: {{ $product->code }}
                                        </span>
                                        @if($product->batch)
                                            <span class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">
                                                Lote: {{ $product->batch }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        {{-- CATEGORÍA --}}
                        <td class="px-5 py-4 text-sm">
                            <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-xs font-bold text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                {{ $product->category->name }}
                            </span>
                        </td>

                        {{-- PRECIO --}}
                        <td class="px-5 py-4 text-sm">
                            <p class="font-bold text-gray-800 dark:text-white text-base">
                                Bs {{ number_format($product->price_sell, 2) }}
                            </p>
                            @if($product->price_buy)
                                <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5" title="Precio de Compra">
                                    (C: {{ number_format($product->price_buy, 2) }})
                                </p>
                            @endif
                        </td>

                        {{-- STOCK (ACTUALIZADO) --}}
                        <td class="px-5 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 font-bold text-xs rounded-full border {{ $stockClass }}">
                                @if($currentStock < 10) 
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                @endif
                                {{ $currentStock }} un.
                            </span>
                        </td>

                        {{-- ACCIONES --}}
                        <td class="px-5 py-4 text-center">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ route('products.edit', $product->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-full transition" title="Editar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                    </svg>
                                </a>

                                <form id="delete-form-{{ $product->id }}" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
                                    @csrf @method('DELETE')
                                </form>

                                <button onclick="confirmDelete(event, 'delete-form-{{ $product->id }}')" class="p-2 text-red-500 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-full transition" title="Eliminar">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                <p>No hay productos registrados.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINACIÓN --}}
        @if(method_exists($products, 'links'))
            <div class="px-5 py-4 bg-white dark:bg-gray-800 border-t dark:border-gray-700">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL PARA IMPRIMIR --}}
    <dialog id="inventoryModal" class="p-6 rounded-lg shadow-xl border w-96 backdrop:bg-gray-900/50 dark:bg-gray-800 dark:border-gray-700 dark:text-white">
        <div class="flex justify-between items-center mb-4 border-b dark:border-gray-700 pb-2">
            <h3 class="font-bold text-lg text-gray-800 dark:text-white">Imprimir Inventario</h3>
            <button onclick="document.getElementById('inventoryModal').close()" class="text-gray-400 hover:text-red-500 text-xl">&times;</button>
        </div>
        
        <form action="{{ route('inventory.print') }}" method="GET" target="_blank">
            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                Generar reporte de stock físico para la sucursal: <strong>{{ auth()->user()->branch->name ?? 'Actual' }}</strong>
            </p>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Categoría</label>
                <select name="category_id" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">-- TODAS LAS CATEGORÍAS --</option>
                    @foreach(\App\Models\Category::all() as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="document.getElementById('inventoryModal').close()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded font-bold hover:bg-purple-700 shadow flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Generar PDF
                </button>
            </div>
        </form>
    </dialog>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto. El producto se eliminará globalmente.",
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