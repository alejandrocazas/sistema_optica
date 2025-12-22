<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Recibo {{ $sale->receipt_number }}</title>
    <style>
        /* Ajuste para impresora térmica de 80mm */
        @page { margin: 0; size: 72mm auto; }
        
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10px;
            margin: 4mm;
            color: #000;
        }
        
        .header { text-align: center; margin-bottom: 5px; }
        /* Aumentamos un poco la fuente del nombre para que destaque la sucursal */
        .company { font-size: 13px; font-weight: bold; text-transform: uppercase; line-height: 1.2; }
        .sub-header { font-size: 9px; margin-top: 2px; text-transform: uppercase;}
        
        .title { 
            text-align: center; 
            font-weight: bold; 
            margin-top: 5px; 
            font-size: 11px;
            text-transform: uppercase;
        }
        
        .dashed { border-top: 1px dashed #000; margin: 4px 0; }
        
        .info-table { width: 100%; font-size: 9px; margin-bottom: 5px; }
        .info-table td { vertical-align: top; padding-bottom: 2px; }
        .label { font-weight: bold; width: 60px; }
        
        /* Tabla de Productos estilo Ticket */
        .products-table { width: 100%; font-size: 9px; border-collapse: collapse; }
        .products-table th { text-align: left; border-bottom: 1px dashed #000; padding-bottom: 2px; }
        .products-table td { padding-top: 2px; vertical-align: top; }
        
        .col-total { text-align: right; width: 25%; }
        
        /* Totales */
        .totals-table { width: 100%; font-size: 10px; margin-top: 5px; }
        .totals-table td { text-align: right; padding-bottom: 2px; }
        .total-final { font-size: 12px; font-weight: bold; }
        
        .footer { text-align: center; margin-top: 10px; font-size: 9px; }
        
        /* Simulación de QR */
        .qr-placeholder {
            margin: 10px auto;
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Estilo para el aviso fiscal */
        .fiscal-warning {
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            margin: 8px 0;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    <div class="header">
        {{-- CAMBIO 1: Nombre Dinámico de la Sucursal --}}
        <div class="company">
            ÓPTICA ALFA<br>
            {{-- Usamos ?? '' para evitar errores si la sucursal fue borrada --}}
            SUCURSAL: {{ $sale->branch->name ?? 'CENTRAL' }}
        </div>
        
        <div class="sub-header">DE: JUAN PÉREZ (PROPIETARIO)</div>
        
        {{-- CAMBIO 2: Dirección Dinámica de la Sucursal (Si la tabla branches tiene columna address) --}}
        {{-- Si no tienes columna address, puedes dejar la fija o usar un if --}}
        <div class="sub-header">
            {{ $sale->branch->address ?? 'CALLE BOLÍVAR #123 - ORURO' }}
        </div>

        {{-- CAMBIO 3: Teléfono Dinámico --}}
        <div class="sub-header">
            Telf: {{ $sale->branch->phone ?? '9999999' }}
        </div>
    </div>

    <div class="title"> NOTA DE VENTA </div>
    <div class="text-center font-bold" style="font-size: 11px; text-align: center;">{{ $sale->receipt_number }}</div>

    <div class="dashed"></div>

    <table class="info-table">
        <tr>
            <td class="label">CLIENTE:</td>
            <td>{{ $sale->patient ? $sale->patient->name : 'PÚBLICO GENERAL' }}</td>
        </tr>
        @if($sale->patient && $sale->patient->ci)
        <tr>
            <td class="label">CI/NIT:</td>
            <td>{{ $sale->patient->ci }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">EMISIÓN:</td>
            <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        
        {{-- FECHA DE ENTREGA --}}
        @if($sale->delivery_date)
        <tr>
            <td class="label" style="padding-top: 3px;">ENTREGA:</td>
            <td style="padding-top: 3px; font-weight: bold;">
                {{ $sale->delivery_date->format('d/m/Y') }} - {{ $sale->delivery_date->format('H:i') }}
            </td>
        </tr>
        @endif
    </table>

    <div class="dashed"></div>

    <table class="products-table">
        <thead>
            <tr>
                <th>DESCRIPCIÓN</th>
                <th class="col-total">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->details as $detail)
            <tr>
                <td>
                    [{{ number_format($detail->quantity, 0) }}] {{ $detail->product->name }}
                    @if($detail->quantity > 1)
                        <br><span style="font-size: 8px;">(P.U: {{ number_format($detail->price, 2) }})</span>
                    @endif
                </td>
                <td class="col-total">{{ number_format($detail->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="dashed"></div>

    <table class="totals-table">
        <tr>
            <td>SUBTOTAL:</td>
            <td>{{ number_format($sale->total, 2) }}</td>
        </tr>
        <tr>
            <td>DESCUENTO:</td>
            <td>0.00</td>
        </tr>
        <tr>
            <td class="total-final">TOTAL A PAGAR:</td>
            <td class="total-final">Bs {{ number_format($sale->total, 2) }}</td>
        </tr>
        <tr><td colspan="2" style="padding-top:5px;"></td></tr>
        <tr>
            <td>A CUENTA/PAGADO:</td>
            <td>{{ number_format($sale->paid_amount, 2) }}</td>
        </tr>
        <tr>
            <td>{{ $sale->balance > 0 ? 'SALDO PENDIENTE:' : 'CAMBIO:' }}</td>
            <td style="font-weight: bold;">{{ number_format(abs($sale->total - $sale->paid_amount), 2) }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

    {{-- AVISO FISCAL --}}
    <div class="fiscal-warning">
        "NO ES VÁLIDO PARA CRÉDITO FISCAL"
    </div>

    <div class="footer">
        USUARIO: {{ strtoupper($sale->user->name) }}
        <br>
        
        {{-- QR --}}
        @if(isset($qrImage))
        <div class="qr-placeholder" style="border: none; background: transparent;">
            <img src="data:image/svg+xml;base64,{{ $qrImage }}" width="80">
        </div>
        @endif

        Gracias por su preferencia<br>
        www.optica-alfa.com
    </div>

</body>
</html>