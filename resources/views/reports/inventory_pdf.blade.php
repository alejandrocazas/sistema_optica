<!DOCTYPE html>
<html>
<head>
    <title>Inventario Físico</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .center { text-align: center; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>REPORTE DE TOMA DE INVENTARIO</h2>
        <p>CATEGORÍA: {{ strtoupper($categoriaNombre) }}</p>
        <p>Fecha de Impresión: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Producto / Descripción</th>
                <th class="center">Stock Sistema</th>
                <th class="center" style="width: 100px;">Físico (Conteo)</th>
                <th class="center" style="width: 100px;">Diferencia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->code }}</td>
                <td>
                    {{ $product->name }}
                    <br><small style="color: #666;">Lote: {{ $product->batch ?? '-' }}</small>
                </td>
                <td class="center">
                    <strong>{{ $product->stock }}</strong>
                </td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>