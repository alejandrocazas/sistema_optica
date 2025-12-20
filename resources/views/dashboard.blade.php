<x-app>
{{-- AQUI VA EL MENSAJE DE ÉXITO --}}
    @if (session('status') === 'profile-updated')
        <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm dark:bg-green-900/50 dark:text-green-300 dark:border-green-500 animate-fade-in-down" role="alert">
            <div class="flex items-center">
                <div class="py-1"><svg class="fill-current h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                <div>
                    <p class="font-bold">¡Operación Exitosa!</p>
                    <p class="text-sm">Tu perfil y contraseña se han actualizado correctamente.</p>
                </div>
            </div>
        </div>
    @endif
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 dark:text-white">Panel de Control</h1>
            <p class="text-gray-600 dark:text-gray-400">Bienvenido, {{ auth()->user()->name }}. Aquí tienes el resumen de hoy.</p>
        </div>
        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 w-fit shadow-sm">
                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                {{ auth()->user()->role }} 
                <span class="mx-2 text-indigo-300 dark:text-indigo-600">|</span> 
                {{ auth()->user()->branch->name ?? 'Sucursal Central' }}
            </span>
        <div class="text-right">
            <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-semibold px-3 py-1 rounded-full border border-blue-200 dark:border-blue-700">
                {{ now()->isoFormat('D [de] MMMM, YYYY') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-blue-500 dark:border-blue-400 flex items-center justify-between transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ventas Hoy</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">Bs {{ number_format($ventasHoy, 2) }}</p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-full text-blue-600 dark:text-blue-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-yellow-400 dark:border-yellow-500 flex items-center justify-between transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">En Laboratorio</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $trabajosPendientes }}</p>
            </div>
            <div class="p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-full text-yellow-600 dark:text-yellow-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-indigo-500 dark:border-indigo-400 flex items-center justify-between transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pacientes</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $pacientesTotal }}</p>
            </div>
            <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-full text-indigo-600 dark:text-indigo-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-l-4 border-red-500 dark:border-red-600 flex items-center justify-between transition hover:shadow-md">
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Stock Bajo</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $productosBajoStock }}</p>
            </div>
            <div class="p-3 bg-red-50 dark:bg-red-900/30 rounded-full text-red-600 dark:text-red-400">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm h-fit border dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white mb-4 border-b dark:border-gray-700 pb-2">Accesos Rápidos</h3>
            <div class="grid grid-cols-2 gap-4">
                
                <a href="{{ route('sales.create') }}" class="flex flex-col items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/40 transition text-blue-700 dark:text-blue-300 border border-transparent dark:border-blue-800/50">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="font-bold text-sm">Nueva Venta</span>
                </a>

                <a href="{{ route('patients.create') }}" class="flex flex-col items-center justify-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/40 transition text-green-700 dark:text-green-300 border border-transparent dark:border-green-800/50">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                    <span class="font-bold text-sm">Nuevo Paciente</span>
                </a>

                <a href="{{ route('cash.index') }}" class="flex flex-col items-center justify-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/40 transition text-purple-700 dark:text-purple-300 border border-transparent dark:border-purple-800/50">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span class="font-bold text-sm">Caja</span>
                </a>

                <a href="{{ route('products.create') }}" class="flex flex-col items-center justify-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg hover:bg-orange-100 dark:hover:bg-orange-900/40 transition text-orange-700 dark:text-orange-300 border border-transparent dark:border-orange-800/50">
                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    <span class="font-bold text-sm">Producto</span>
                </a>

            </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border dark:border-gray-700">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800 dark:text-white">Últimas Ventas Registradas</h3>
                <a href="{{ route('sales.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Ver todas</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase font-medium">
                        <tr>
                            <th class="px-4 py-3 rounded-tl-lg">Cliente</th>
                            <th class="px-4 py-3 text-center">Estado</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center rounded-tr-lg">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700 dark:text-gray-300">
                        @foreach($ultimasVentas as $v)
                        <tr class="border-b dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <td class="px-4 py-3 font-medium">{{ $v->patient->name ?? 'Cliente General' }}</td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $estadoClases = match($v->status) {
                                        'entregado' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800',
                                        'cancelado' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800',
                                        'listo' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
                                        default => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800'
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-bold {{ $estadoClases }}">
                                    {{ ucfirst($v->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold">Bs {{ number_format($v->total, 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('sales.print', $v->id) }}" target="_blank" class="text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition" title="Imprimir">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app>