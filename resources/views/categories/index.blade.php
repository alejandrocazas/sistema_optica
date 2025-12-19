<x-app>
    <div class="max-w-4xl mx-auto">
        
        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Gestión de Categorías</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm">Administra las clasificaciones de tus productos.</p>
            </div>
            
            <a href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white flex items-center gap-1 text-sm font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a Productos
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- FORMULARIO DE CREACIÓN --}}
            <div class="md:col-span-1">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg border-t-4 border-blue-600 dark:border-blue-500 transition-colors">
                    <h2 class="font-bold text-xl mb-4 text-gray-700 dark:text-white flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Nueva Categoría
                    </h2>
                    
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-600 dark:text-gray-300 text-sm font-bold mb-2">Nombre</label>
                            <input type="text" name="name" placeholder="Ej: Monturas, Líquidos..." class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" required>
                        </div>
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                            Guardar Categoría
                        </button>
                    </form>
                </div>
            </div>

            {{-- LISTADO DE CATEGORÍAS --}}
            <div class="md:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 transition-colors">
                    <div class="bg-gray-100 dark:bg-gray-700 px-6 py-4 border-b dark:border-gray-600">
                        <h3 class="font-bold text-gray-700 dark:text-gray-200 uppercase text-sm tracking-wide">Listado Existente</h3>
                    </div>
                    
                    @if($categories->isEmpty())
                        <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p>No hay categorías registradas aún.</p>
                        </div>
                    @else
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($categories as $category)
                                <li class="px-6 py-4 flex justify-between items-center hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <div>
                                        <span class="font-bold text-lg text-gray-800 dark:text-white block">{{ $category->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 text-xs font-bold px-3 py-1 rounded-full border border-blue-200 dark:border-blue-800">
                                            {{ $category->products_count }} productos
                                        </span>
                                        
                                        @if($category->products_count == 0)
                                            <form id="delete-form-{{ $category->id }}" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: none;">
                                                @csrf @method('DELETE')
                                            </form>

                                            <button onclick="confirmDelete(event, 'delete-form-{{ $category->id }}')" class="p-2 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition" title="Eliminar Categoría">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-600" title="No se puede eliminar porque tiene productos asociados">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                                </svg>
                                            </span>
                                        @endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function confirmDelete(event, formId) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
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