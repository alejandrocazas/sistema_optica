<x-app>
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg border-t-4 border-yellow-500 dark:border-yellow-600">
        
        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Editar Producto</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Modificando: <span class="font-mono text-yellow-600 dark:text-yellow-400">{{ $product->name }}</span></p>
            </div>
            <a href="{{ route('products.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white flex items-center gap-1 text-sm font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Cancelar y Volver
            </a>
        </div>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- COLUMNA IZQUIERDA --}}
                <div class="space-y-6">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                        <div class="relative">
                            <select name="category_id" class="w-full border p-2 pl-3 rounded bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none appearance-none">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Código</label>
                        <input type="text" name="code" value="{{ $product->code }}" class="w-full border p-2 rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none font-mono">
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Lote</label>
                        <input type="text" name="batch" value="{{ $product->batch }}" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none" placeholder="Opcional">
                    </div>

                    {{-- STOCK BLOQUEADO (SEGURIDAD) --}}
                    <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded border border-red-100 dark:border-red-800/30">
                        <label class="font-bold text-gray-700 dark:text-gray-300 mb-1 flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            Stock Actual
                        </label>
                        <input type="number" name="stock" value="{{ $product->stock }}" 
                               class="bg-gray-200 dark:bg-gray-600 text-gray-500 dark:text-gray-400 font-bold cursor-not-allowed border dark:border-gray-500 p-2 rounded w-full text-center text-lg" 
                               readonly>
                        <p class="text-xs text-red-500 dark:text-red-400 mt-2 flex items-start gap-1">
                            <svg class="w-3 h-3 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            El stock no se puede editar manualmente. Usa el módulo de "Compras" para agregar mercadería.
                        </p>
                    </div>
                </div>

                {{-- COLUMNA DERECHA --}}
                <div class="space-y-6">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Descripción / Nombre</label>
                        <input type="text" name="name" value="{{ $product->name }}" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1 text-xs uppercase">Precio Compra</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Bs</span>
                                <input type="number" step="0.01" name="price_buy" value="{{ $product->price_buy }}" class="w-full pl-8 p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold mb-1 text-xs uppercase text-green-600 dark:text-green-400">Precio Venta</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Bs</span>
                                <input type="number" step="0.01" name="price_sell" value="{{ $product->price_sell }}" class="w-full pl-8 p-2 border rounded dark:bg-gray-700 dark:border-gray-600  font-bold text-green-700 dark:text-green-300 focus:ring-2 focus:ring-green-500 outline-none">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN IMAGEN --}}
                    <div class="border p-4 rounded-lg bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-600">
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-3">Imagen del Producto</label>
                        <div class="flex items-center gap-6">
                            @if($product->image_path)
                                <div class="text-center group relative">
                                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wide">Actual</p>
                                    <img src="{{ Storage::url($product->image_path) }}" class="w-24 h-24 object-cover rounded-lg border dark:border-gray-500 shadow-sm group-hover:opacity-75 transition">
                                </div>
                            @else
                                <div class="w-24 h-24 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center text-gray-400 dark:text-gray-300 border dark:border-gray-500">
                                    <span class="text-xs">Sin Foto</span>
                                </div>
                            @endif
                            
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 dark:text-gray-300 mb-2">Subir nueva imagen <span class="text-xs text-gray-400">(Opcional)</span></p>
                                <label class="block">
                                    <span class="sr-only">Elegir archivo</span>
                                    <input type="file" name="image" class="block w-full text-sm text-gray-500 dark:text-gray-300
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-yellow-50 file:text-yellow-700
                                      hover:file:bg-yellow-100
                                      dark:file:bg-yellow-900/30 dark:file:text-yellow-300
                                    "/>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white font-bold rounded hover:bg-yellow-600 shadow-lg transform transition hover:-translate-y-1">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-app>