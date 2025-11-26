<table>
    <thead>
        <tr>
            <th>Posición</th>
            <th>Nombre</th>
            <th>Unidad Educativa</th>
            <th>Promedio</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $row)
            <tr>
                <td class="posicion">{{ $row['posicion'] }}</td>
                <td>
                    @if($esGrupal)
                        <strong style="color: #7c3aed;">{{ $row['nombre_grupo'] ?? 'Sin nombre' }}</strong>
                        @if(isset($row['integrantes']) && is_array($row['integrantes']))
                            <br>
                            <span style="font-size: 9px; color: #666;">Integrantes:</span>
                            @foreach($row['integrantes'] as $integrante)
                                <br><span style="font-size: 9px; color: #333; margin-left: 10px;">{{ $integrante }}</span>
                            @endforeach
                        @endif
                    @else
                        {{ $row['nombre_completo'] ?? 'N/A' }}
                    @endif
                </td>
                <td>{{ $row['unidad_educativa'] ?? 'No especificada' }}</td>
                <td style="text-align: center; font-weight: bold;">
                    {{ number_format($row['promedio'] ?? $row['nota'] ?? 0, 2) }}
                </td>
                <td style="text-align: center;">
                    <span class="badge {{ $badgeClass }}">
                        {{ $label }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>