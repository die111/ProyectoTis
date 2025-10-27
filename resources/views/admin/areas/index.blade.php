@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
<section x-data="{ openCrear: false, openEditar: false, area: { id: '', name: '', description: '' } }">

    <!-- Barra de título -->
    <div class="barra-titulo">
        <h1>Áreas</h1>
        <button @click="openCrear = true">Crear Área</button>

        {{-- Modales --}}
        @include('components.modals.crear-area', ['state' => 'openCrear'])
        @include('components.modals.editar-area', ['state' => 'openEditar'])
    </div>

    <!-- Buscador -->
    <form method="GET" action="{{ route('admin.areas.index') }}" class="search-panel">
       <div class="search-input-wrapper">
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar Área">
            <svg class="search-icon" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
           </svg>
       </div>
      <button type="submit" class="search-btn">Buscar</button>
    </form>

    <!-- Tabla de Áreas -->
    <div class="tabla-contenedor">
        <table>
            <thead>
                <tr>
                    <th>Nombre de Área</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $area)
                <tr class="area-row" data-id="{{ $area->id }}" data-name="{{ $area->name }}" data-description="{{ $area->description }}" data-active="{{ $area->is_active }}" onclick="selectRow(this)">
                    <td>{{ $area->name }}</td>
                    <td>{{ Str::limit($area->description, 90) }}</td>
                    <td class="{{ $area->is_active ? 'activo' : 'inactivo' }}">{{ $area->is_active ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <div class="acciones">
                            @if($area->is_active)
                            <form method="POST" action="{{ route('admin.areas.bulk-deactivate') }}">
                                @csrf
                                <input type="hidden" name="ids" value="{{ $area->id }}">
                                <button type="submit" class="btn-rojo">Desactivar</button>
                            </form>
                            @else
                            <form method="POST" action="{{ route('admin.areas.bulk-activate') }}">
                                @csrf
                                <input type="hidden" name="ids" value="{{ $area->id }}">
                                <button type="submit" class="btn-verde">Activar</button>
                            </form>
                            @endif
                            <button type="button" @click="area.id='{{ $area->id }}'; area.name='{{ $area->name }}'; area.description='{{ $area->description }}'; openEditar=true;" class="btn-azul">Editar</button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="sin-datos">No hay áreas registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Paginación -->
        <div class="paginacion">
            {{ $areas->onEachSide(1)->links() }}
        </div>
    </div>

</section>

<style>
/* Barra de título */
.barra-titulo {display: flex;justify-content: space-between;align-items: center;margin: 0 5%;margin-bottom: 24px;padding: 12px 16px;background: rgba(226,232,240,0.7);border-radius: 8px;}

.barra-titulo h1 {font-size: 24px;font-weight: 600;color: #334155;}
.barra-titulo button {padding: 8px 16px;background-color: #0C204A;color: white;border: none;border-radius: 9999px;font-weight: 600;cursor: pointer;transition: filter 0.2s;}

.barra-titulo button:hover {filter: brightness(1.1);}

/* Buscador */
.search-panel {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}

.search-input-wrapper {
    position: relative;
    width: 360px;
    max-width: 92vw;
}

.search-input-wrapper input {
    width: 100%;
    height: 40px;
    background: rgba(226, 232, 240, 0.7);
    border: none;
    border-radius: 10px;
    padding: 0 40px 0 12px;
    font-size: 13px;
    color: var(--text-dark, #3a4651);
    font-weight: 600;
    box-shadow: 0 0 0 1px rgba(148, 163, 184, 0.4);
}

.search-input-wrapper input::placeholder {
    color: rgba(58,70,81,.5);
    font-weight: 400;
}

.search-icon {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    color: rgba(58,70,81,.5);
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
    pointer-events: none;
}

.search-btn {
    height: 40px;
    padding: 0 14px;
    border-radius: 30px;
    background: var(--primary-dark-blue, #0C204A);
    color: #fff;
    font-family: 'Roboto', sans-serif;
    font-weight: 500;
    font-size: 13px;
    letter-spacing: 1.25px;
    cursor: pointer;
}

/* Tabla */
.tabla-contenedor {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
    background-color: #f1f5f9;
    border-radius: 8px;
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
}

thead tr {
    background-color: #334155;
    color: white;
    text-align: left;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
}

tbody tr {
    color: #1e293b;
    cursor: pointer;
    transition: background 0.2s;
}

tbody tr:hover {
    background-color: #f8fafc;
}

td, th {
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
}

/* Estados */
.activo {
    display: inline-block;
    padding: 2px 8px;
    background-color: #dcfce7;
    color: #166534;
    font-size: 12px;
    font-weight: 600;
    border-radius: 9999px;
    text-align: center;
}

.inactivo {
    display: inline-block;
    padding: 2px 8px;
    background-color: #fee2e2;
    color: #991b1b;
    font-size: 12px;
    font-weight: 600;
    border-radius: 9999px;
    text-align: center;
}

/* Botones de acción */
.acciones {
    display: flex;
    gap: 6px;
    justify-content: flex-end;
}

.btn-rojo {
    background-color: #dc2626;
    color: white;
    padding: 4px 12px;
    font-size: 12px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-verde {
    background-color: #15803d;
    color: white;
    padding: 4px 12px;
    font-size: 12px;
    border-radius: 6px;
    cursor: pointer;
}

.btn-azul {
    background-color: #091C47;
    color: white;
    padding: 4px 12px;
    font-size: 12px;
    border-radius: 6px;
    cursor: pointer;
}

/* Selección de fila */
.area-row.selected {
    background-color: #bfdbfe;
    border-left: 4px solid #3b82f6;
}

/* Sin datos */
.sin-datos {
    text-align: center;
    color: #94a3b8;
    font-size: 16px;
    padding: 24px;
}

/* Paginación */
.paginacion {
    display: flex;
    justify-content: flex-end;
    margin-top: 12px;
    gap: 4px;
}
</style>

<script>
function selectRow(row) {
    const rows = document.querySelectorAll('.area-row');
    rows.forEach(r => r.classList.remove('selected'));
    row.classList.add('selected');
}
</script>

@endsection