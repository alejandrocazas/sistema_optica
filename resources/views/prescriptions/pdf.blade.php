<!DOCTYPE html>
<html>
<head>
    <title>Receta #{{ $prescription->id }}</title>
    <style>
        /* Tamaño Media Carta (aprox) para impresora pequeña */
        @page { margin: 15px; size: 140mm 216mm; }
        
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            font-size: 11px; 
            color: #333;
        }

        /* Colores Corporativos */
        .text-blue { color: #1e40af; }
        .bg-blue { background-color: #1e40af; color: white; }
        .border-blue { border: 2px solid #1e40af; }

        /* Encabezado */
        .header { text-align: center; margin-bottom: 20px; }
        .logo-text { font-size: 18px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .sub-header { font-size: 10px; color: #666; margin-top: 4px; }

        /* Cajas de Info */
        .info-box { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px; 
        }
        .info-box td { 
            padding: 5px; 
            border: 1px solid #ccc; /* Borde gris sutil */
        }
        .label { font-weight: bold; color: #1e40af; width: 15%; background: #f0f4ff; }

        /* Tabla Principal de Medidas */
        .medidas-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 15px;
            border: 2px solid #1e40af; /* Borde externo azul grueso */
        }
        .medidas-table th { 
            background-color: #1e40af; 
            color: white; 
            padding: 6px; 
            text-transform: uppercase;
            font-size: 10px;
            border: 1px solid #1e40af;
        }
        .medidas-table td { 
            border: 1px solid #1e40af; /* Rejilla azul */
            padding: 8px; 
            text-align: center; 
            font-weight: bold;
            font-size: 12px;
        }
        
        /* Ojo Derecho / Izquierdo */
        .eye-label { background-color: #eff6ff; color: #1e40af; font-weight: bold; }

        /* Sección Adición y DIP */
        .extra-info { margin-top: 10px; font-size: 11px; }
        
        /* Firma */
        .footer { 
            margin-top: 40px; 
            text-align: center; 
            font-size: 10px; 
            color: #555;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="text-blue logo-text">CENTRO OFTALMOLÓGICO ALFAA</div>
        <div class="sub-header">Especialistas en Salud Visual</div>
        <div class="sub-header" style="margin-top: 5px; font-weight: bold;">
            RECETA NRO: {{ str_pad($prescription->id, 6, '0', STR_PAD_LEFT) }}
        </div>
    </div>

    <table class="info-box">
        <tr>
            <td class="label">PACIENTE:</td>
            <td colspan="3">{{ strtoupper($prescription->patient->name) }}</td>
        </tr>
        <tr>
            <td class="label">EDAD:</td>
            <td>{{ $prescription->patient->age }} Años</td>
            <td class="label">FECHA:</td>
            <td>{{ $prescription->created_at->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">OPTÓMETRA:</td>
            <td colspan="3">{{ strtoupper($prescription->user->name) }}</td>
        </tr>
    </table>

    <h4 style="margin: 0 0 5px 0; color: #1e40af; border-bottom: 1px solid #1e40af; display: inline-block;">PRESCRIPCIÓN ÓPTICA</h4>
    
    <table class="medidas-table">
        <thead>
            <tr>
                <th width="15%"></th> <th width="10%">OJO</th>
                <th width="25%">ESFERA</th>
                <th width="25%">CILINDRO</th>
                <th width="25%">EJE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td rowspan="2" class="eye-label" style="text-align: left; padding-left: 10px;">LEJOS</td>
                <td class="eye-label">OD</td>
                <td>
                    {{ $prescription->od_esfera > 0 ? '+' : '' }}{{ number_format((float)$prescription->od_esfera, 2) }}
                </td>
                <td>
                    {{ $prescription->od_cilindro > 0 ? '+' : '' }}{{ number_format((float)$prescription->od_cilindro, 2) }}
                </td>
                <td>{{ $prescription->od_eje }}°</td>
            </tr>
            <tr>
                <td class="eye-label">OI</td>
                <td>
                    {{ $prescription->oi_esfera > 0 ? '+' : '' }}{{ number_format((float)$prescription->oi_esfera, 2) }}
                </td>
                <td>
                    {{ $prescription->oi_cilindro > 0 ? '+' : '' }}{{ number_format((float)$prescription->oi_cilindro, 2) }}
                </td>
                <td>{{ $prescription->oi_eje }}°</td>
            </tr>

            @if($prescription->add_od || $prescription->add_oi)
            <tr>
                <td rowspan="2" class="eye-label" style="text-align: left; padding-left: 10px;">ADICIÓN</td>
                <td class="eye-label">OD</td>
                <td colspan="3" style="text-align: left; padding-left: 20px;">
                    ADD: {{ $prescription->add_od > 0 ? '+' : '' }}{{ number_format((float)$prescription->add_od, 2) }}
                </td>
            </tr>
            <tr>
                <td class="eye-label">OI</td>
                <td colspan="3" style="text-align: left; padding-left: 20px;">
                    ADD: {{ $prescription->add_oi > 0 ? '+' : '' }}{{ number_format((float)$prescription->add_oi, 2) }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <div style="border: 1px solid #ccc; padding: 10px; margin-top: 5px;">
        <p style="margin: 3px 0;"><strong>D.I.P:</strong> {{ $prescription->dip }} mm</p>
        
        @if($prescription->diagnostico)
        <p style="margin: 3px 0;"><strong>DIAGNÓSTICO:</strong> {{ $prescription->diagnostico }}</p>
        @endif
        
        @if($prescription->observaciones)
        <p style="margin: 8px 0 3px 0; font-style: italic; color: #444;">
            <strong>OBSERVACIONES:</strong><br>
            {{ $prescription->observaciones }}
        </p>
        @endif
    </div>

    <div class="footer">
        <div style="border-bottom: 1px solid #000; width: 200px; margin: 0 auto 5px auto;"></div>
        Firma del Especialista / Sello
    </div>

</body>
</html>