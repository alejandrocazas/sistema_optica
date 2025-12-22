<x-app>
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg border-t-4 border-blue-500 dark:border-blue-600">
        
        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4 border-b dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Registrar Nuevo Producto</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Ingresa los detalles para agregar al catálogo.</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white flex items-center gap-1 text-sm font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Listado
            </a>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- TRUCO: Enviamos stock 0 oculto para que la base de datos no falle si el campo es obligatorio --}}
            <input type="hidden" name="stock" value="0">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- COLUMNA IZQUIERDA --}}
                <div class="space-y-6">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Categoría</label>
                        <div class="flex gap-2">
                            <div class="relative w-full">
                                <select name="category_id" class="w-full border p-2 pl-3 rounded bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none appearance-none" required>
                                    <option value="" disabled selected>Seleccione una categoría...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                                </div>
                            </div>
                            <a href="{{ route('categories.index') }}" class="bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-3 py-2 rounded font-bold hover:bg-gray-300 dark:hover:bg-gray-600 transition flex items-center" title="Crear Categoría">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            </a>
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Código</label>
                        <input type="text" name="code" class="w-full border p-2 rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none font-mono" placeholder="Ej: LEN-001" required>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Lote</label>
                        <input type="text" name="batch" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Opcional">
                    </div>
                    
                    {{-- NOTA: Se eliminó el campo Stock Inicial visualmente --}}
                </div>

                {{-- COLUMNA DERECHA --}}
                <div class="space-y-6">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Descripción / Nombre</label>
                        <input type="text" name="name" class="w-full border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ej: Lente Blue Cut..." required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1 text-xs uppercase">Precio Compra</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Bs</span>
                                <input type="number" step="0.01" name="price_buy" class="w-full pl-8 p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="0.00">
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold mb-1 text-xs uppercase text-green-600 dark:text-green-400">Precio Venta</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500">Bs</span>
                                <input type="number" step="0.01" name="price_sell" class="w-full pl-8 p-2 border rounded dark:bg-gray-700 dark:border-gray-600 font-bold text-green-700 dark:text-green-300 focus:ring-2 focus:ring-green-500 outline-none" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>

                    {{-- SUBIDA DE IMAGEN CON PREVIEW --}}
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-gray-300 mb-1">Imagen del Producto</label>
                        
                        <div class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg h-48 flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-700/30 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition overflow-hidden group">
                            
                            {{-- Contenido por defecto (Icono y Texto) --}}
                            <div id="upload-placeholder" class="flex flex-col items-center pointer-events-none">
                                <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-blue-600 dark:text-blue-400 font-bold">Sube una foto</p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPG hasta 2MB</p>
                            </div>

                            {{-- Imagen de Previsualización (Oculta al inicio) --}}
                            <img id="image-preview" class="hidden absolute inset-0 w-full h-full object-contain bg-white dark:bg-gray-800" />

                            {{-- Input invisible que cubre todo el área --}}
                            <input type="file" name="image" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImage(this)" accept="image/*">
                        </div>
                    </div>

                </div>
            </div>

            <div class="mt-8 pt-6 border-t dark:border-gray-700 flex justify-end gap-3">
                <a href="{{ route('products.index') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded hover:bg-blue-700 shadow-lg transform transition hover:-translate-y-1">
                    Guardar Producto
                </button>
            </div>
        </form>
    </div>

    {{-- Script para manejar la previsualización --}}
    <script>
        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Muestra la imagen
                    placeholder.classList.add('hidden'); // Oculta el texto de "Subir foto"
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    {{-- Actualización forzada del formulario --}}
</x-app>