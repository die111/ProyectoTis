<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Clasificados</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #dcfce7; }
        h2 { margin-bottom: 0; }
        .subtitle { color: #555; margin-top: 2px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Lista de Clasificados</h2>
    <div class="subtitle">
        Competición: <strong>{{ $competicion->name }}</strong><br>
        Fase actual: <strong>{{ $faseObj->name }} (Nivel {{ $numeroFaseActual }})</strong><br>
        Siguiente fase: <strong>Nivel {{ $numeroFaseSiguiente }}</strong><br>
        Total clasificados: <strong>{{ count($estudiantes) }}</strong>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                <th>Unidad Educativa</th>
                <th>Área</th>
                <th>Categoría</th>
                <th>Nota</th> <!-- Nueva columna Nota -->
            </tr>
        </thead>
        <tbody>
            @foreach($estudiantes as $i => $estudiante)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $estudiante->user->name ?? 'N/A' }} {{ $estudiante->user->last_name_father ?? '' }} {{ $estudiante->user->last_name_mother ?? '' }}</td>
                <td>{{ $estudiante->user->school ?? 'No especificada' }}</td>
                <td>{{ $estudiante->area->name ?? 'No asignada' }}</td>
                <td>{{ $estudiante->categoria->nombre ?? 'No asignada' }}</td>
                <td>
                    @php
                        // Buscar evaluación en la inscripción previa (fase actual) si la inscripción clasificada (fase siguiente) no tiene nota
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
</body>
</html>
