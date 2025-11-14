<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Inscritos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background: #e0e7ff; }
        h2 { margin-bottom: 0; }
        .subtitle { color: #555; margin-top: 2px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>Lista de Inscritos</h2>
    <div class="subtitle">
        Competición: <strong>{{ $competicion->name }}</strong><br>
        Fase: <strong>{{ $faseObj->name }} (Nivel {{ $numeroFase }})</strong><br>
        Total estudiantes: <strong>{{ count($estudiantes) }}</strong>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Estudiante</th>
                <th>Unidad Educativa</th>
                <th>Área</th>
                <th>Categoría</th>
                <th>Estado</th>
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
                <td>{{ ucfirst($estudiante->estado) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
