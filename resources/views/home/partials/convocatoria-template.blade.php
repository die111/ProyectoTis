<html>
<head>
    <meta charset="utf-8" />
    <title>Convocatoria - {{ $compet->name }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #222; }
        .header { text-align:center; margin-bottom:20px; }
        .section { margin-bottom:12px; }
        .bold { font-weight:700; }
        table { width:100%; border-collapse: collapse; }
        td, th { padding:6px; border:1px solid #ddd; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Convocatoria: {{ $compet->name }}</h1>
        <p>{{ $compet->description }}</p>
    </div>

    <div class="section">
        <p><span class="bold">Periodo de la competencia:</span> {{ \Carbon\Carbon::parse($compet->fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($compet->fechaFin)->format('d/m/Y') }}</p>
    </div>

    @if($compet->phases->isNotEmpty())
        <div class="section">
            <h3>Fases</h3>
            <table>
                <thead>
                    <tr><th>Fase</th><th>Inicio</th><th>Fin</th><th>Tipo clasificación</th></tr>
                </thead>
                <tbody>
                    @foreach($compet->phases as $phase)
                        <tr>
                            <td>{{ $phase->name }}</td>
                            <td>{{ $phase->pivot->start_date ? \Carbon\Carbon::parse($phase->pivot->start_date)->format('d/m/Y') : '' }}</td>
                            <td>{{ $phase->pivot->end_date ? \Carbon\Carbon::parse($phase->pivot->end_date)->format('d/m/Y') : '' }}</td>
                            <td>
                                @if($phase->pivot->classification_type === 'cupo')
                                    Por cupo ({{ $phase->pivot->classification_cupo ?? 'N/A' }})
                                @elseif($phase->pivot->classification_type === 'nota')
                                    Por nota mínima ({{ $phase->pivot->classification_nota_minima ?? 'N/A' }})
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if($compet->categoryAreas->isNotEmpty())
        <div class="section">
            <h3>Áreas y categorías habilitadas</h3>
            <table>
                <thead>
                    <tr><th>Categoría</th><th>Área</th></tr>
                </thead>
                <tbody>
                    @foreach($compet->categoryAreas as $ca)
                    <tr>
                        <td>{{ $ca->categoria->nombre ?? 'N/A' }}</td>
                        <td>{{ $ca->area->name ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="section">
        <p>Generado: {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>