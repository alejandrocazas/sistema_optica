<x-app>
    <div class="max-w-5xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg border-t-4 border-blue-500 dark:border-blue-600 transition-colors">
        
        {{-- ENCABEZADO --}}
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 border-b dark:border-gray-700 pb-4 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Nueva Consulta Oftalmológica
                </h2>
                <div class="mt-1 text-gray-600 dark:text-gray-400">
                    Paciente: <span class="font-bold text-blue-600 dark:text-blue-400 text-lg">{{ $patient->name }}</span>
                    <span class="mx-2 text-gray-300 dark:text-gray-600">|</span>
                    <span class="text-sm">Edad: {{ $patient->age }} años</span>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Fecha Consulta</p>
                <p class="text-xl font-mono font-bold text-gray-800 dark:text-white">{{ date('d/m/Y') }}</p>
            </div>
        </div>

        <form action="{{ route('prescriptions.store', $patient->id) }}" method="POST">
            @csrf

            {{-- ANAMNESIS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Anamnesis (Motivo de Consulta)</label>
                    <textarea name="anamnesis" rows="3" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Describe el motivo..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Antecedentes</label>
                    <textarea name="antecedentes" rows="3" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition" placeholder="Uso de lentes previos, enfermedades..."></textarea>
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
                        <div class="col-span-2">EJE (Grados)</div>
                    </div>

                    {{-- OD --}}
                    <div class="grid grid-cols-7 gap-4 items-center mb-3">
                        <div class="font-bold text-xl text-blue-700 dark:text-blue-400 text-center">OD</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="od_esfera" placeholder="0.00" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="od_cilindro" placeholder="0.00" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="od_eje" placeholder="0°" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    </div>

                    {{-- OI --}}
                    <div class="grid grid-cols-7 gap-4 items-center">
                        <div class="font-bold text-xl text-blue-700 dark:text-blue-400 text-center">OI</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="oi_esfera" placeholder="0.00" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="oi_cilindro" placeholder="0.00" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="oi_eje" placeholder="0°" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none"></div>
                    </div>
                </div>
            </div>

            {{-- ADICIÓN --}}
            <div class="flex flex-col md:flex-row gap-4 mb-8 items-center bg-yellow-50 dark:bg-yellow-900/10 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800/30">
                <span class="font-bold text-yellow-700 dark:text-yellow-500 uppercase text-sm">ADD (Adición):</span>
                <div class="flex gap-4 w-full md:w-auto">
                    <input type="number" step="0.25" name="add_od" placeholder="OD +0.00" class="flex-1 p-2 border rounded font-mono text-center bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                    <input type="number" step="0.25" name="add_oi" placeholder="OI +0.00" class="flex-1 p-2 border rounded font-mono text-center bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-yellow-500 outline-none">
                </div>
            </div>

            {{-- SECCIÓN CERCA --}}
            <div class="mb-8">
                <h3 class="text-sm font-bold text-green-600 dark:text-green-400 uppercase mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span> Visión Cerca
                </h3>
                
                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-xl border border-green-100 dark:border-green-800/50">
                    <div class="grid grid-cols-7 gap-4 items-center mb-3">
                        <div class="font-bold text-xl text-green-700 dark:text-green-400 text-center">OD</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_od_esfera" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_od_cilindro" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="cerca_od_eje" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                    </div>

                    <div class="grid grid-cols-7 gap-4 items-center">
                        <div class="font-bold text-xl text-green-700 dark:text-green-400 text-center">OI</div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_oi_esfera" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" step="0.25" name="cerca_oi_cilindro" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                        <div class="col-span-2"><input type="number" name="cerca_oi_eje" class="w-full p-2 text-center border rounded font-mono bg-white dark:bg-gray-800 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-green-500 outline-none"></div>
                    </div>
                </div>
            </div>

            {{-- OTROS DATOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">DIP (Distancia Interpupilar)</label>
                    <input type="text" name="dip" placeholder="Ej: 60/62 mm" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Diagnóstico</label>
                    <div class="relative">
                        <select name="diagnostico" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none appearance-none">
                            <option value="">Seleccione...</option>
                            <option value="Miopía">Miopía</option>
                            <option value="Hipermetropía">Hipermetropía</option>
                            <option value="Astigmatismo">Astigmatismo</option>
                            <option value="Presbicia">Presbicia</option>
                            <option value="Miopía con Astigmatismo">Miopía con Astigmatismo</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700 dark:text-gray-300">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase">Observaciones / Sugerencias</label>
                <textarea name="observaciones" rows="3" class="w-full p-3 border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" placeholder="Ej: Recomendar Blue Block, Lente progresivo..."></textarea>
            </div>

            <div class="flex justify-end pt-6 border-t dark:border-gray-700">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform transition hover:-translate-y-1 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Guardar e Imprimir Receta
                </button>
            </div>
        </form>
    </div>
</x-app>