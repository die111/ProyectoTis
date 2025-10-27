@extends('layouts.app')
@section('title', 'Dashboard Administrador')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
<style>
/* ===== Variables de color ===== */
:root{
  --primary-dark-blue:#091c47;
  --table-header-bg:rgba(58,70,81,.5);
  --table-row-alt-bg:#d7dde4;
  --table-bg:#eef0f3;
  --text-dark:#3a4651;
  --white:#fff;
  --gray-200:#e5e7eb;
  --gray-500:#9aa0a6;
}

/* ===== Layout general ===== */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #f8fafc;
}

/* ===== Tarjetas ===== */
.card {
    background: var(--white);
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
}

/* ===== Buscador ===== */
.search-panel{
    display:flex;
    gap:16px;
    flex-wrap:wrap;
    justify-content:center;
    margin-bottom:16px;
}
.search-panel input, .search-panel select{
    width: 200px;
    background: rgba(226, 232, 240, 0.7);
    border: none;
    border-radius:10px;
    padding:8px 12px;
    font-size:13px;
    color: var(--text-dark);
    font-weight:600;
}
.search-panel input::placeholder{color:rgba(58,70,81,.5);font-weight:400}
.search-btn{
    background: var(--primary-dark-blue);
    color: #fff;
    border:none;
    border-radius:30px;
    padding:8px 16px;
    font-size:13px;
    font-weight:500;
}
.search-btn:hover{filter:brightness(1.05)}
.search-btn:active{transform:translateY(1px) scale(.99);filter:brightness(.95)}

/* ===== Tabla card ===== */
.table-card{
    width:100%;
    max-width:100vw;
    margin:0 auto 16px auto;
    background: var(--table-bg);
    border-radius:10px;
    overflow-x:auto;
    border:1px solid #cfd6df;
}
table{
    border-collapse:separate;
    border-spacing:0;
    width:100%;
    background:var(--table-bg);
}
.grid-headers th{
    background: var(--table-header-bg);
    color:#fff;
    padding:14px 8px;
    text-align:center;
    font-family:'Quicksand',sans-serif;
    font-weight:700;
    font-size:14px;
    white-space:nowrap;
}
td {
    text-align: left;
    padding:10px 12px;
    color: var(--text-dark);
    vertical-align: middle;
}
tbody tr{
    transition:background 0.15s;
}
tbody tr:nth-child(even){background: var(--table-row-alt-bg);}
tbody tr:nth-child(odd){background: var(--white);}
tbody tr:hover{background:#dbeafe;} /* Azul muy claro hover */

/* ===== Estado (badge) ===== */
.status-badge{
    display:inline-block;
    padding:4px 10px;
    border-radius:12px;
    font-weight:600;
    font-size:12px;
}
.status-active{
    background: rgba(34,197,94,0.2);
    color:#15803d;
}
.status-inactive{
    background: rgba(239,68,68,0.2);
    color:#b91c1c;
}

/* ===== Botones tabla ===== */
.btn-pressable{
    transition:transform .05s ease,filter .15s ease;
    box-shadow:0 1px 0 rgba(0,0,0,.12);
}
.btn-pressable:hover{filter:brightness(1.05)}
.btn-pressable:active{transform:translateY(1px) scale(.99);filter:brightness(.95)}

.btn-primary{background:var(--primary-dark-blue);color:#fff}
.btn-secondary{background:#f1f3f4;color:#111;border:1px solid var(--gray-200)}
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto py-4">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
        <h3 class="text-xl font-semibold">Usuarios</h3>
        <a href="{{ route('admin.formulario-usuario') }}" class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95" style="background:var(--primary-dark-blue)">
            <i class="bi bi-person-plus"></i> Crear Usuario
        </a>
    </div>

    {{-- Cards --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-8">
        <a href="?role=activos" class="block group">
            <div class="card p-4 flex flex-col justify-between h-32 relative" id="card-activo">
                <div class="flex items-center justify-center gap-3 h-full">
                    <i class="bi bi-person-lines-fill text-3xl"></i>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold">Usuarios Activos</span>
                        <span class="text-lg">{{ $usuarios_activos_count ?? 0 }}</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-2 bg-cyan-500 rounded-b-lg"></div>
            </div>
        </a>
        <a href="?role=inactivos" class="block group">
            <div class="card p-4 flex flex-col justify-between h-32 relative" id="card-inactivo">
                <div class="flex items-center justify-center gap-3 h-full">
                    <i class="bi bi-person-dash-fill text-3xl"></i>
                    <div class="flex flex-col items-center">
                        <span class="font-semibold">Usuarios Inactivos</span>
                        <span class="text-lg">{{ $usuarios_inactivos_count ?? 0 }}</span>
                    </div>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-2 bg-gray-500 rounded-b-lg"></div>
            </div>
        </a>
    </div>

    {{-- Tabla Usuarios --}}
    <section class="table-card overflow-x-auto">
        <form id="searchForm" action="{{ route('admin.usuarios.index') }}" method="GET" class="search-panel p-4">
            <input type="text" name="q" placeholder="Buscar Usuario" value="{{ request('q') }}">
            <select id="role_id" name="role_id">
                <option value="">Todos los Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                        {{ $role->name == 'olimpista' ? 'Estudiante' : ucfirst(str_replace('_', ' ', $role->name)) }}
                    </option>
                @endforeach
            </select>
            <select id="area" name="area">
                <option value="">Todas las Áreas</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="search-btn btn-pressable">Buscar</button>
        </form>

        <table class="w-full min-w-[600px] text-center align-middle">
            <thead class="grid-headers">
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Rol</th>
                    <th>Nivel</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $i => $user)
                <tr class="{{ $i % 2 === 0 ? '' : 'bg-[#d7dde4]' }} hover:bg-blue-50 transition-colors">
                    <td class="py-3 px-2 font-semibold text-gray-800 align-middle">{{ $user->name }}</td>
                    <td class="py-3 px-2 text-gray-900 align-middle">{{ $user->last_name_father }}</td>
                    <td class="py-3 px-2 text-gray-700 align-middle">{{ $user->last_name_mother }}</td>
                    <td class="py-3 px-2 text-gray-800 align-middle">{{ $user->role ? $user->role->name : '-' }}</td>
                    <td class="py-3 px-2 text-gray-800 align-middle">{{ $user->level ?? '-' }}</td>
                    <td class="py-3 px-2 align-middle">
                        @if($user->is_active)
                            <span class="status-badge status-active">Activo</span>
                        @else
                            <span class="status-badge status-inactive">Inactivo</span>
                        @endif
                    </td>
                    <td class="py-2 px-2 align-middle">
                        <div class="flex flex-wrap gap-2 justify-center">
                            <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Editar</a>
                            @if($user->is_active)
                                <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary btn-pressable px-3 py-1 text-sm">Desactivar</button>
                                </form>
                            @else
                                <form action="{{ route('admin.usuarios.activate', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Activar</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="table-footer">
            {{ $users->links() }}
        </div>
    </section>
</div>
@endsection