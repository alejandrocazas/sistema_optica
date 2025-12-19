<x-app>
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg border-t-4 border-yellow-500 dark:border-yellow-600 transition-colors">
        
        {{-- ENCABEZADO --}}
        <div class="flex justify-between items-center mb-8 border-b dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar Paciente
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Modificando datos de: <span class="font-bold text-yellow-600 dark:text-yellow-400">{{ $patient->name }}</span>
                </p>
            </div>
            <a href="{{ route('patients.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white flex items-center gap-1 text-sm font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver a la Lista
            </a>
        </div>

        <form action="{{ route('patients.update', $patient->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                
                {{-- Nombre Completo --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nombre Completo</label>
                    <input type="text" name="name" value="{{ $patient->name }}" required class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                {{-- CI / DNI --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">CI / DNI</label>
                    <input type="text" name="ci" value="{{ $patient->ci }}" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                {{-- Edad --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Edad</label>
                    <input type="number" name="age" value="{{ $patient->age }}" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>

                {{-- Celular --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Celular / WhatsApp</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500 dark:text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        </span>
                        <input type="text" name="phone" value="{{ $patient->phone }}" class="w-full py-3 pl-10 pr-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                    </div>
                </div>

                {{-- Ocupación --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Ocupación</label>
                    <input type="text" name="occupation" value="{{ $patient->occupation }}" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t dark:border-gray-700">
                <a href="{{ route('patients.index') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 bg-yellow-500 text-white font-bold rounded-lg hover:bg-yellow-600 shadow-lg transform transition hover:-translate-y-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Actualizar Datos
                </button>
            </div>
        </form>
    </div>
</x-app>