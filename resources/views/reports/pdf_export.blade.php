<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Financiero</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        
        /* Encabezado */
        .header-table { width: 100%; margin-bottom: 25px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .header-left { text-align: left; vertical-align: top; }
        .header-right { text-align: right; vertical-align: top; }
        
        .company-name { font-size: 18px; font-weight: bold; color: #000; margin-bottom: 5px; }
        .report-title { font-size: 14px; font-weight: bold; color: #555; text-transform: uppercase; }
        
        .meta-label { font-weight: bold; color: #666; }
        .meta-val { margin-bottom: 3px; }

        /* Tablas */
        .section-title { font-size: 12px; font-weight: bold; margin-top: 15px; margin-bottom: 5px; padding-bottom: 3px; }
        .title-income { border-bottom: 2px solid #2d8a3e; color: #2d8a3e; }
        .title-expense { border-bottom: 2px solid #d32f2f; color: #d32f2f; }

        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .data-table th { background-color: #f0f0f0; font-weight: bold; text-align: left; padding: 6px; border: 1px solid #ccc; font-size: 9px; text-transform: uppercase; }
        .data-table td { border: 1px solid #ddd; padding: 6px; vertical-align: middle; }
        .data-table tr:nth-child(even) { background-color: #fafafa; }

        /* Utilidades */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-mono { font-family: monospace; font-size: 11px; }
        
        .text-green { color: #2d8a3e; }
        .text-red { color: #d32f2f; }

        /* Resumen */
        .summary-container { width: 100%; display: block; margin-top: 20px; }
        .summary-box { float: right; width: 280px; border: 1px solid #ccc; padding: 10px; background-color: #f9f9f9; border-radius: 4px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 11px; }
        .summary-row.total { border-top: 2px solid #333; margin-top: 5px; padding-top: 5px; font-size: 13px; font-weight: bold; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td class="header-left">
                <div class="company-name">REPORTE DE TRANSACCIONES</div>
                <div class="meta-val"><span class="meta-label">Generado por:</span> {{ auth()->user()->name }}</div>
                <div class="meta-val"><span class="meta-label">Rol:</span> {{ ucfirst(auth()->user()->role) }}</div>
                <div class="meta-val"><span class="meta-label">Sucursal Origen:</span> {{ auth()->user()->branch->name ?? 'Casa Matriz' }}</div>
            </td>
            <td class="header-right">
                <div class="report-title">Periodo del Reporte</div>
                <div class="meta-val" style="font-size: 12px; font-weight: bold; margin: 5px 0;">
                    {{ $startDate->format('d/m/Y') }} al {{ $endDate->format('d/m/Y') }}
                </div>
                <div class="meta-val"><span class="meta-label">Filtro aplicado:</span> {{ ucfirst($filter) }}</div>
                <div class="meta-val"><span class="meta-label">Fecha Impresión:</span> {{ now()->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    {{-- TABLA DE INGRESOS --}}
    <div class="section-title title-income">INGRESOS (Ventas Cobradas)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">Fecha</th>
                <th width="12%">Comprobante</th>
                <th>Cliente</th>
                <th width="15%">Método Pago</th>
                <th width="15%">Vendedor</th>
                <th width="12%" class="text-right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @forelse($incomes as $income)
            <tr>
                <td>{{ $income->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $income->sale->receipt_number ?? '---' }}</td>
                <td>{{ $income->sale->patient->name ?? 'Cliente General' }}</td>
                <td>{{ ucfirst($income->method) }}</td>
                <td>{{ $income->sale->user->name ?? '---' }}</td>
                <td class="text-right font-mono text-green">
                    {{ number_format($income->amount, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 15px; color: #999;">
                    No se registraron ingresos en este periodo.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    {{-- TABLA DE EGRESOS --}}
    <div class="section-title title-expense">EGRESOS (Gastos de Caja)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">Fecha</th>
                <th>Descripción / Motivo</th>
                <th width="20%">Usuario Responsable</th>
                <th width="12%" class="text-right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $expense)
            <tr>
                <td>{{ $expense->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $expense->description }}</td>
                <td>
                    {{-- CORRECCIÓN: Busca el usuario del gasto O el usuario dueño de la caja --}}
                    {{ $expense->user->name ?? ($expense->cashRegister->user->name ?? 'Sistema') }}
                </td>
                <td class="text-right font-mono text-red">
                    {{ number_format($expense->amount, 2) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 15px; color: #999;">
                    No se registraron gastos en este periodo.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- RESUMEN FINAL --}}
    <div class="summary-container">
        <div class="summary-box">
            <div class="summary-row">
                <span>(+) Total Ingresos:</span>
                <span class="font-mono text-green">{{ number_format($totalIngresos, 2) }}</span>
            </div>
            <div class="summary-row">
                <span>(-) Total Gastos:</span>
                <span class="font-mono text-red">{{ number_format($totalEgresos, 2) }}</span>
            </div>
            <div class="summary-row total">
                <span>(=) FLUJO NETO:</span>
                <span class="font-mono">{{ number_format($saldoTotal, 2) }}</span>
            </div>
        </div>
    </div>

</body>
</html>