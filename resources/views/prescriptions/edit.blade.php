<x-app>
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg border-t-4 border-yellow-500 dark:border-yellow-600 transition-colors">
        
        {{-- ENCABEZADO --}}
        <div class="flex justify-between items-center mb-8 border-b dark:border-gray-700 pb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Editar Receta #{{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Paciente: <span class="font-bold text-yellow-600 dark:text-yellow-400">{{ $patient->name }}</span>
                </p>
            </div>
            <a href="{{ route('prescriptions.history', $patient->id) }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white flex items-center gap-1 text-sm font-bold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Volver al Historial
            </a>
        </div>

        <form action="{{ route('prescriptions.update', $prescription->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ANAMNESIS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Anamnesis</label>
                    <textarea name="anamnesis" rows="2" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">{{ $prescription->anamnesis }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Antecedentes</label>
                    <textarea name="antecedentes" rows="2" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">{{ $prescription->antecedentes }}</textarea>
                </div>
            </div>

            {{-- SECCIÓN LEJOS --}}
            <div class="mb-8">
                <h3 class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span> Refracción (Lejos)
                </h3>
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50">
                    
                    {{-- Encabezados --}}
                    <div class="grid grid-cols-7 gap-4 text-center mb-2 text-[10px] font-bold text-blue-800 dark:text-blue-300 uppercase tracking-wider">
                        <div>OJO</div>
                        <div class="col-span-2">ESFERA</div>
                        <div class="col-span-2">CILINDRO</div>
                        <div class="col-span-2">EJE</div>
                    </div>

                    {{-- OD --}}
                    <div class="grid grid-cols-7 gap-4 items-center mb-3">
                        <div class="font-bold text-xl text-blue-700 dark:text-blue-400 text-center">OD</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="od_esfera" value="{{ $prescription->od_esfera }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="od_cilindro" value="{{ $prescription->od_cilindro }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="od_eje" value="{{ $prescription->od_eje }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    </div>

                    {{-- OI --}}
                    <div class="grid grid-cols-7 gap-4 items-center">
                        <div class="font-bold text-xl text-blue-700 dark:text-blue-400 text-center">OI</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="oi_esfera" value="{{ $prescription->oi_esfera }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="oi_cilindro" value="{{ $prescription->oi_cilindro }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="oi_eje" value="{{ $prescription->oi_eje }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    </div>
                </div>
            </div>

            {{-- ADICIÓN --}}
            <div class="flex flex-col md:flex-row gap-4 mb-8 items-center bg-yellow-50 dark:bg-yellow-900/10 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800/30">
                <span class="font-bold text-yellow-700 dark:text-yellow-500 uppercase text-sm">ADD (Adición):</span>
                <div class="flex gap-4 w-full md:w-auto">
                    <input type="number" step="0.25" name="add_od" value="{{ $prescription->add_od }}" placeholder="OD +0.00" class="flex-1 p-2 border rounded font-mono text-center bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                    <input type="number" step="0.25" name="add_oi" value="{{ $prescription->add_oi }}" placeholder="OI +0.00" class="flex-1 p-2 border rounded font-mono text-center bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>
            </div>

            {{-- SECCIÓN CERCA --}}
            <div class="mb-8">
                <h3 class="text-sm font-bold text-green-600 dark:text-green-400 uppercase mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span> Visión Cerca
                </h3>
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl border border-green-100 dark:border-green-800/50">
                    
                    {{-- OD --}}
                    <div class="grid grid-cols-7 gap-4 items-center mb-3">
                        <div class="font-bold text-xl text-green-700 dark:text-green-400 text-center">OD</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_od_esfera" value="{{ $prescription->cerca_od_esfera }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_od_cilindro" value="{{ $prescription->cerca_od_cilindro }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="cerca_od_eje" value="{{ $prescription->cerca_od_eje }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                    </div>

                    {{-- OI --}}
                    <div class="grid grid-cols-7 gap-4 items-center">
                        <div class="font-bold text-xl text-green-700 dark:text-green-400 text-center">OI</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_oi_esfera" value="{{ $prescription->cerca_oi_esfera }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_oi_cilindro" value="{{ $prescription->cerca_oi_cilindro }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="cerca_oi_eje" value="{{ $prescription->cerca_oi_eje }}" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                    </div>
                </div>
            </div>

            {{-- OTROS DATOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">DIP (Distancia Interpupilar)</label>
                    <input type="text" name="dip" value="{{ $prescription->dip }}" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Diagnóstico</label>
                    <div class="relative">
                        <select name="diagnostico" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none appearance-none">
                            <option value="Miopía" {{ $prescription->diagnostico == 'Miopía' ? 'selected' : '' }}>Miopía</option>
                            <option value="Hipermetropía" {{ $prescription->diagnostico == 'Hipermetropía' ? 'selected' : '' }}>Hipermetropía</option>
                            <option value="Astigmatismo" {{ $prescription->diagnostico == 'Astigmatismo' ? 'selected' : '' }}>Astigmatismo</option>
                            <option value="Presbicia" {{ $prescription->diagnostico == 'Presbicia' ? 'selected' : '' }}>Presbicia</option>
                            <option value="Miopía con Astigmatismo" {{ $prescription->diagnostico == 'Miopía con Astigmatismo' ? 'selected' : '' }}>Miopía con Astigmatismo</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 dark:text-gray-300">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Observaciones</label>
                <textarea name="observaciones" rows="3" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none transition">{{ $prescription->observaciones }}</textarea>
            </div>

            <div class="flex justify-end pt-6 border-t dark:border-gray-700 gap-3">
                <a href="{{ route('prescriptions.history', $patient->id) }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white font-bold rounded hover:bg-yellow-600 shadow-lg transform transition hover:-translate-y-1">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</x-app>