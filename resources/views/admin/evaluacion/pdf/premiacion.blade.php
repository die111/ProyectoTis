<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premiación - {{ $competicion->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #091C47;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #091C47;
            font-size: 18px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            color: #2563eb;
            font-size: 14px;
            font-weight: normal;
        }
        .header p {
            margin: 3px 0;
            color: #666;
            font-size: 10px;
        }
        .info-section {
            background-color: #f0f7ff;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border-left: 4px solid #2563eb;
        }
        .info-section p {
            margin: 3px 0;
        }
        .info-section strong {
            color: #091C47;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        thead {
            background-color: #091C47;
            color: white;
        }
        th {
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            padding: 8px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        tbody tr:hover {
            background-color: #f3f4f6;
        }
        .posicion {
            font-weight: bold;
            font-size: 12px;
            color: #091C47;
            text-align: center;
            width: 60px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
        }
        .badge-oro {
            background-color: #fef3c7;
            color: #92400e;
            border: 2px solid #d97706;
        }
        .badge-plata {
            background-color: #f3f4f6;
            color: #374151;
            border: 2px solid #6b7280;
        }
        .badge-bronce {
            background-color: #fde68a;
            color: #78350f;
            border: 2px solid #b45309;
        }
        .badge-mencion {
            background-color: #dbeafe;
            color: #1e40af;
            border: 2px solid #3b82f6;
        }
        .medalla-icon {
            display: inline-block;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            text-align: center;
            line-height: 18px;
            margin-right: 4px;
            font-weight: bold;
            font-size: 11px;
            vertical-align: middle;
        }
        .medalla-oro {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
            color: #92400e;
            border: 2px solid #d97706;
        }
        .medalla-plata {
            background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 50%, #c0c0c0 100%);
            color: #374151;
            border: 2px solid #6b7280;
        }
        .medalla-bronce {
            background: linear-gradient(135deg, #cd7f32 0%, #e6a85c 50%, #cd7f32 100%);
            color: #78350f;
            border: 2px solid #b45309;
        }
        .medalla-mencion {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 50%, #3b82f6 100%);
            color: white;
            border: 2px solid #1e40af;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
        .total {
            font-weight: bold;
            background-color: #f0f7ff;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PREMIACIÓN - {{ strtoupper($competicion->name) }}</h1>
        <h2>{{ $grupo }}</h2>
        <p>Listado de estudiantes clasificados y premiados</p>
        <p>Fecha de generación: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="info-section">
        <p><strong>Competición:</strong> {{ $competicion->name }}</p>
        <p><strong>Área:</strong> {{ $area }}</p>
        <p><strong>Nivel/Categoría:</strong> {{ $nivel }}</p>
        <p><strong>Total de Premiados:</strong> {{ $premiados->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="posicion">Pos.</th>
                <th>Estudiante</th>
                <th>Unidad Educativa</th>
                <th style="text-align: center;">Nota</th>
                <th style="text-align: center;">Premio</th>
            </tr>
        </thead>
        <tbody>
            @forelse($premiados as $row)
                @php
                    $badgeClass = match($row['premio']) {
                        'oro' => 'badge-oro',
                        'plata' => 'badge-plata',
                        'bronce' => 'badge-bronce',
                        'mencion_honor' => 'badge-mencion',
                        default => 'badge-mencion'
                    };
                    $medallaClass = match($row['premio']) {
                        'oro' => 'medalla-oro',
                        'plata' => 'medalla-plata',
                        'bronce' => 'medalla-bronce',
                        'mencion_honor' => 'medalla-mencion',
                        default => 'medalla-mencion'
                    };
                    $medallaNum = match($row['premio']) {
                        'oro' => '1',
                        'plata' => '2',
                        'bronce' => '3',
                        'mencion_honor' => 'M',
                        default => '—'
                    };
                    $label = match($row['premio']) {
                        'oro' => 'ORO',
                        'plata' => 'PLATA',
                        'bronce' => 'BRONCE',
                        'mencion_honor' => 'MENCIÓN',
                        default => strtoupper($row['premio'] ?? '—')
                    };
                @endphp
                <tr>
                    <td class="posicion">{{ $row['posicion'] }}</td>
                    <td>{{ $row['nombre_completo'] }}</td>
                    <td>{{ $row['unidad_educativa'] }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ number_format($row['nota'] ?? 0, 2) }}</td>
                    <td style="text-align: center;">
                        <span class="badge {{ $badgeClass }}">
                            <span class="medalla-icon {{ $medallaClass }}">{{ $medallaNum }}</span>
                            {{ $label }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 20px; color: #999;">
                        No hay estudiantes clasificados en esta categoría
                    </td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" style="text-align: right; padding: 10px;">
                    <strong>Total de Premiados:</strong>
                </td>
                <td style="text-align: center; padding: 10px;">
                    <strong>{{ $premiados->count() }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Documento generado automáticamente por el Sistema de Gestión de Competiciones</p>
        <p>{{ $competicion->name }} - {{ now()->format('d/m/Y') }}</p>
    </div>
</body>
</html>
