<!DOCTYPE html>
<html>
<head>
    <title>Cierre de Caja</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .h1 { font-size: 18px; font-weight: bold; margin: 0; }
        .meta { font-size: 10px; color: #555; margin-top: 5px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th { background-color: #eee; text-align: left; padding: 5px; border-bottom: 1px solid #aaa; }
        td { padding: 5px; border-bottom: 1px solid #ddd; }
        .amount { text-align: right; }
        
        /* Ajuste para resaltar QR en la tabla */
        .method-qr { color: #2563eb; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        
        .summary-box { float: right; width: 45%; border: 1px solid #000; padding: 10px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; }
        
        /* Estilos para totales diferenciados */
        .total-final { font-weight: bold; font-size: 16px; border-top: 2px solid #000; padding-top: 5px; margin-top: 5px; }
        .total-digital { border-top: 1px dashed #aaa; margin-top: 10px; padding-top: 5px; color: #555; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 5px; text-transform: uppercase; border-bottom: 1px solid #333; }
    </style>
</head>
<body>

    {{-- LÓGICA DE CÁLCULO EN LA VISTA --}}
    @php
        // Separamos los ingresos en Efectivo y Digitales (QR)
        $ingresosEfectivo = $payments->where('method', 'Efectivo')->sum('amount');
        $ingresosDigital  = $payments->where('method', '!=', 'Efectivo')->sum('amount');
        
        // El dinero real en caja es: Apertura + Ventas Efectivo - Gastos
        $efectivoRealEnCaja = $register->opening_amount + $ingresosEfectivo - $totalEgresos;
    @endphp

    <div class="header">
        <p class="h1">REPORTE DE CIERRE DE CAJA</p>
        <p class="meta">
            CAJERO: {{ strtoupper($user->name) }} <br>
            APERTURA: {{ $register->created_at->format('d/m/Y H:i') }} <br>
            CIERRE: {{ date('d/m/Y H:i') }}
        </p>
    </div>

    <div class="section-title">Detalle de Cobros (Ingresos)</div>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Comp.</th>
                <th>Cliente</th>
                <th>Método</th>
                <th class="amount">Monto (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('H:i') }}</td>
                <td>{{ $payment->sale->receipt_number }}</td>
                <td>{{ $payment->sale->patient->name }}</td>
                <td>
                    @if($payment->method == 'Efectivo')
                        Efectivo
                    @else
                        {{-- Resaltamos visualmente si es QR --}}
                        <span class="method-qr">{{ $payment->method }}</span>
                    @endif
                </td>
                <td class="amount">{{ number_format($payment->amount, 2) }}</td>
            </tr>
            @endforeach
            @if($payments->isEmpty())
                <tr><td colspan="5" style="text-align: center;">Sin cobros registrados</td></tr>
            @endif
        </tbody>
    </table>

    <div class="section-title">Detalle de Gastos (Egresos)</div>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Descripción / Motivo</th>
                <th class="amount">Monto (Bs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
            <tr>
                <td>{{ $expense->created_at->format('H:i') }}</td>
                <td>{{ $expense->description }}</td>
                <td class="amount">{{ number_format($expense->amount, 2) }}</td>
            </tr>
            @endforeach
             @if($expenses->isEmpty())
                <tr><td colspan="3" style="text-align: center;">Sin gastos registrados</td></tr>
            @endif
        </tbody>
    </table>

    <div style="clear: both;"></div>
    <br>
    
    {{-- CUADRO DE RESUMEN MODIFICADO --}}
    <div class="summary-box">
        <div class="summary-row">
            <span>(+) Fondo Inicial:</span>
            <span>{{ number_format($register->opening_amount, 2) }}</span>
        </div>
        <div class="summary-row">
            <span>(+) Ventas en Efectivo:</span>
            <span>{{ number_format($ingresosEfectivo, 2) }}</span>
        </div>
        <div class="summary-row" style="color: #b91c1c;">
            <span>(-) Total Egresos (Gastos):</span>
            <span>- {{ number_format($totalEgresos, 2) }}</span>
        </div>
        
        <div class="summary-row total-final">
            <span>(=) EFECTIVO EN CAJA:</span>
            <span>Bs {{ number_format($efectivoRealEnCaja, 2) }}</span>
        </div>

        @if($ingresosDigital > 0)
        <div class="summary-row total-digital">
            <span>(i) Ventas Digitales (QR/Banco):</span>
            <span style="font-weight: bold;">Bs {{ number_format($ingresosDigital, 2) }}</span>
        </div>
        <div style="font-size: 9px; text-align: right; color: #666; margin-top:2px;">
            * Este monto está en banco, no en caja.
        </div>
        @endif
    </div>

    <div style="margin-top: 100px; text-align: center; font-size: 10px;">
        __________________________________<br>
        Firma del Cajero Responsable
    </div>

</body>
</html>