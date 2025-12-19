<x-app>
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Sucursales</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Gestiona tus diferentes ópticas y locales.</p>
        </div>
        
        <button onclick="document.getElementById('createBranchModal').showModal()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition transform hover:-translate-y-0.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Nueva Sucursal
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($branches as $branch)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 rounded-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    
                    {{-- Acciones --}}
                    <div class="flex gap-2">
                        <button onclick="openEditModal({{ $branch }})" class="text-gray-400 hover:text-blue-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        @if($branch->id !== 1)
                            <form action="{{ route('branches.destroy', $branch->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta sucursal?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <h3 class="mt-4 text-xl font-bold text-gray-800 dark:text-white">{{ $branch->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    {{ $branch->address ?? 'Sin dirección' }}
                </p>

                <div class="mt-4 pt-4 border-t dark:border-gray-700 flex justify-between items-center">
                    <span class="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider">Personal</span>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold px-2 py-1 rounded-full">
                        {{ $branch->users_count }} Usuarios
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- MODAL CREAR --}}
    <dialog id="createBranchModal" class="p-0 rounded-lg shadow-xl w-full max-w-md backdrop:bg-gray-900/50 bg-transparent">
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Nueva Sucursal</h3>
                <form action="{{ route('branches.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre Comercial</label>
                        <input type="text" name="name" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none focus:ring-2 focus:ring-blue-500" placeholder="Ej: Óptica Look">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Dirección</label>
                        <input type="text" name="address" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                        <input type="text" name="phone" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="document.getElementById('createBranchModal').close()" class="px-4 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">Cancelar</button>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    {{-- MODAL EDITAR (Script simple para rellenar datos) --}}
    <dialog id="editBranchModal" class="p-0 rounded-lg shadow-xl w-full max-w-md backdrop:bg-gray-900/50 bg-transparent">
        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">Editar Sucursal</h3>
                <form id="editForm" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre</label>
                        <input type="text" id="editName" name="name" required class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Dirección</label>
                        <input type="text" id="editAddress" name="address" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Teléfono</label>
                        <input type="text" id="editPhone" name="phone" class="w-full p-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-2 mt-6">
                        <button type="button" onclick="document.getElementById('editBranchModal').close()" class="px-4 py-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white">Cancelar</button>
                        <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function openEditModal(branch) {
            document.getElementById('editName').value = branch.name;
            document.getElementById('editAddress').value = branch.address;
            document.getElementById('editPhone').value = branch.phone;
            // Actualizar la acción del formulario con el ID correcto
            document.getElementById('editForm').action = `/branches/${branch.id}`;
            document.getElementById('editBranchModal').showModal();
        }
    </script>
</x-app>