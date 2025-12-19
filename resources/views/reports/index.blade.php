<x-app>
    <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6">Reportes Financieros</h1>

    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-6 border dark:border-gray-700">
        
        <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap items-end justify-between gap-4">
            
            <div class="flex flex-wrap items-end gap-4">
            {{-- NUEVO: SELECTOR DE SUCURSAL (SOLO ADMIN) --}}
                @if(auth()->user()->role === 'admin')
                    <div>
                        <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1">Sucursal</label>
                        <select name="branch_id" class="border p-2 rounded bg-indigo-50 dark:bg-indigo-900/20 dark:border-indigo-700  focus:ring-2 focus:ring-indigo-500 outline-none font-bold text-indigo-700 dark:text-indigo-300" onchange="this.form.submit()">
                            <option value="">-- TODAS LAS SUCURSALES --</option>
                            @foreach(\App\Models\Branch::all() as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
                {{-- FIN NUEVO SELECTOR --}}
                <div>
                    <label class="block font-bold text-sm text-gray-700 dark:text-gray-300 mb-1">Periodo</label>
                    <select name="filter" class="border p-2 rounded bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none" onchange="if(this.value!='custom') this.form.submit()">
                        <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hoy</option>
                        <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>Esta Semana</option>
                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>Este Mes</option>
                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>Este Año</option>
                        <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Personalizado</option>
                    </select>
                </div>
                
                @if($filter == 'custom')
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Desde</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1">Hasta</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="border p-2 rounded dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none">
                </div>
                @endif

                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-bold shadow transition">
                    Filtrar
                </button>
            </div>

            <div>
                <a href="{{ route('reports.pdf', request()->all()) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-bold flex items-center gap-2 shadow transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar PDF
                </a>
            </div>

        </form>
        
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 border-t dark:border-gray-700 pt-2">
            Mostrando datos del: <strong>{{ $startDate->format('d/m/Y') }}</strong> al <strong>{{ $endDate->format('d/m/Y') }}</strong>
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-blue-100 dark:bg-blue-900/40 p-6 rounded shadow border-l-4 border-blue-500 dark:border-blue-400">
            <h3 class="font-bold text-blue-800 dark:text-blue-300">Ventas Totales</h3>
            <p class="text-2xl font-bold dark:text-white">Bs {{ number_format($totalSales, 2) }}</p>
            <span class="text-xs dark:text-blue-200">(Incluye deudas)</span>
        </div>

        <div class="bg-green-100 dark:bg-green-900/40 p-6 rounded shadow border-l-4 border-green-500 dark:border-green-400">
            <h3 class="font-bold text-green-800 dark:text-green-300">Ingresos Reales</h3>
            <p class="text-2xl font-bold dark:text-white">Bs {{ number_format($totalIncome, 2) }}</p>
            <span class="text-xs dark:text-green-200">(Dinero recibido)</span>
        </div>

        <div class="bg-red-100 dark:bg-red-900/40 p-6 rounded shadow border-l-4 border-red-500 dark:border-red-400">
            <h3 class="font-bold text-red-800 dark:text-red-300">Gastos</h3>
            <p class="text-2xl font-bold dark:text-white">Bs {{ number_format($totalExpenses, 2) }}</p>
        </div>

        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded shadow border-l-4 border-gray-600 dark:border-gray-500">
            <h3 class="font-bold text-gray-800 dark:text-gray-300">Flujo Neto</h3>
            <p class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                Bs {{ number_format($netProfit, 2) }}
            </p>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden border dark:border-gray-700">
        <div class="px-6 py-4 border-b dark:border-gray-700 font-bold text-gray-800 dark:text-white">
            Detalle de Ventas del Periodo
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-300 border-b dark:border-gray-600">
                        <th class="px-5 py-3 text-left">Fecha</th>
                        <th class="px-5 py-3 text-left">Comprobante</th>
                        <th class="px-5 py-3 text-left">Cliente</th>
                        <th class="px-5 py-3 text-right">Total</th>
                        <th class="px-5 py-3 text-right">Pagado</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 dark:text-gray-300">
                    @foreach($sales as $sale)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-5 py-3 text-sm">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-5 py-3 font-mono text-sm text-blue-600 dark:text-blue-400">{{ $sale->receipt_number }}</td>
                        <td class="px-5 py-3 font-medium">{{ $sale->patient->name ?? 'S/N' }}</td>
                        <td class="px-5 py-3 text-right font-bold text-gray-800 dark:text-white">Bs {{ number_format($sale->total, 2) }}</td>
                        <td class="px-5 py-3 text-right font-bold {{ $sale->balance > 0 ? 'text-red-500 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                            Bs {{ number_format($sale->paid_amount, 2) }}
                        </td>
                    </tr>
                    @endforeach
                    
                    @if($sales->isEmpty())
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-500 dark:text-gray-400">
                            No hay movimientos registrados en este periodo.
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</x-app>