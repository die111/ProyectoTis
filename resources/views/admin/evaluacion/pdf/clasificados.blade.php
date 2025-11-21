<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clasificados</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; margin-bottom: 30px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #dcfce7; }
        h2 { margin-bottom: 0; }
        h3 { margin-top: 25px; margin-bottom: 5px; color: #1f2937; }
        .subtitle { color: #555; margin-top: 2px; margin-bottom: 10px; }
        .section-title { background: #e0e7ff; padding: 8px; margin-top: 20px; margin-bottom: 5px; font-weight: bold; }
        .no-data { text-align: center; padding: 20px; color: #666; font-style: italic; }
        .integrantes { font-size: 11px; color: #374151; margin-top: 4px; }
    </style>
</head>
<body>
    <h2>Lista de Clasificados</h2>
    <div class="subtitle">
        Competición: <strong>{{ $competicion->name }}</strong><br>
        Fase actual: <strong>{{ $faseObj->name }} (Nivel {{ $numeroFaseActual }})</strong><br>
        Siguiente fase: <strong>Nivel {{ $numeroFaseSiguiente }}</strong><br>
        Total clasificados: <strong>{{ count($estudiantesIndividuales) + ($gruposClasificados?->count() ?? 0) }}</strong>
        ({{ count($estudiantesIndividuales) }} individuales, {{ $gruposClasificados?->count() ?? 0 }} grupos)
    </div>

    <!-- Sección de Clasificados Individuales -->
    <div class="section-title">CLASIFICADOS INDIVIDUALES</div>
    @if(count($estudiantesIndividuales) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Estudiante</th>
                    <th>Unidad Educativa</th>
                    <th>Área</th>
                    <th>Categoría</th>
                    <th>Nota</th>
                </tr>
            </thead>
            <tbody>
                @foreach($estudiantesIndividuales as $i => $estudiante)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $estudiante->user->name ?? 'N/A' }} {{ $estudiante->user->last_name_father ?? '' }} {{ $estudiante->user->last_name_mother ?? '' }}</td>
                    <td>{{ $estudiante->user->school ?? 'No especificada' }}</td>
                    <td>{{ $estudiante->area->name ?? 'No asignada' }}</td>
                    <td>{{ $estudiante->categoria->nombre ?? 'No asignada' }}</td>
                    <td>
                        @php
                            $evaluacionActual = $estudiante->evaluations->first();
                            $notaActual = $evaluacionActual && $evaluacionActual->nota !== null ? $evaluacionActual->nota : null;
                            $inscripcionPrevia = isset($inscripcionesPreviasKeyed) ? $inscripcionesPreviasKeyed->get($estudiante->user_id) : null;
                            $evaluacionPrevia = $inscripcionPrevia ? $inscripcionPrevia->evaluations->first() : null;
                            $nota = $notaActual !== null ? $notaActual : ($evaluacionPrevia && $evaluacionPrevia->nota !== null ? $evaluacionPrevia->nota : null);
                            $notaFormateada = $nota !== null ? rtrim(rtrim(number_format($nota, 2, '.', ''), '0'), '.') : '—';
                        @endphp
                        {{ $notaFormateada }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No hay clasificados individuales</div>
    @endif

    <!-- Sección de Clasificados Grupales (agrupado por nombre de grupo) -->
    <div class="section-title">CLASIFICADOS GRUPALES (PROMEDIO POR GRUPO)</div>
    @if(($gruposClasificados?->count() ?? 0) > 0)
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Grupo</th>
                    <th>Integrantes</th>
                    <th>Área</th>
                    <th>Categoría</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gruposClasificados as $i => $grupo)
                @php
                    $primer = $grupo->integrantes->first();
                    $promedioFormateado = $grupo->promedio !== null ? rtrim(rtrim(number_format($grupo->promedio, 2, '.', ''), '0'), '.') : '—';
                @endphp
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $grupo->nombre_grupo }}</td>
                    <td>
                        <div class="integrantes">
                            @foreach($grupo->integrantes as $m)
                                • {{ $m->user->name ?? 'N/A' }} {{ $m->user->last_name_father ?? '' }}<br>
                            @endforeach
                        </div>
                    </td>
                    <td>{{ $primer->area->name ?? 'No asignada' }}</td>
                    <td>{{ $primer->categoria->nombre ?? 'No asignada' }}</td>
                    <td>{{ $promedioFormateado }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">No hay grupos clasificados</div>
    @endif
</body>
</html>
