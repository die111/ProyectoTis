@extends('layouts.app')

@section('title', 'Etapas · Admin')

@section('content')
<!--  CABECERA / ACCIONES   -->
<div class="content-header">
    <h1 class="content-title">Fases</h1>
    <a href="{{ route('admin.phases.create') }}" class="create-btn btn-pressable">
        <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
        </svg>
        <span>Crear</span>
    </a>
</div>

<!--  BUSCADOR (filtra por nombre o descripción) -->
<form id="searchForm" class="search-panel" action="{{ route('admin.phases.index') }}" method="GET">
    <div class="search-input-wrapper">
        <input id="searchInput" name="search" type="text" value="{{ $query ?? '' }}" placeholder="Buscar por nombre o descripción..." aria-label="Buscar etapa"/>
        <svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </div>
    <button class="search-btn btn-pressable" type="submit">Buscar</button>
</form>

<!--  TABLA DE ETAPAS (HTML table)   -->
<div class="table-card overflow-x-auto py-8 px-32"> <!-- Aumentado margen lateral de la tabla -->
    <table class="w-full min-w-[600px] text-center align-middle">
        <thead class="grid-headers">
            <tr>
                <!-- <th class="cell header whitespace-nowrap">ID</th> -->
                <th class="cell header whitespace-nowrap">Nombre de Etapa</th>
                <th class="cell header whitespace-nowrap">Descripción</th>
                <th class="cell header whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($phases as $i => $phase)
            <tr class="{{ $i % 2 === 0 ? '' : 'bg-[#d7dde4]' }} hover:bg-blue-50 transition-colors">
                <!-- <td class="py-3 px-2 font-semibold text-gray-800 align-middle">{{ $phase->id }}</td> -->
                <td class="py-3 px-2 text-gray-900 align-middle text-left">{{ $phase->name }}</td>
                <td class="py-3 px-2 text-gray-700 align-middle text-left">{{ $phase->description ?? '—' }}</td>
                <td class="py-2 px-2 align-middle">
                    <div class="flex flex-wrap gap-2 justify-center">
                        <a href="{{ route('admin.phases.edit', $phase->id) }}" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Editar</a>
                        @if($phase->is_active)
                            <form action="{{ route('admin.phases.destroy', $phase->id) }}" method="POST" style="display:inline-block;" class="swal-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="btn btn-secondary btn-pressable px-3 py-1 text-sm {{ $phase->in_use ? 'opacity-50 cursor-not-allowed' : '' }}" 
                                    {{ $phase->in_use ? 'disabled' : '' }}
                                    @if($phase->in_use) title="No se puede desactivar porque está en uso en una competición" @endif>
                                    Desactivar
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.phases.habilitar', $phase->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Habilitar</button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No hay etapas registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-footer">
        {{ $phases->links() }}
    </div>
</div>

@endsection

@push('styles')
<style>
/* ===== Header ===== */
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:24px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:'Roboto',sans-serif;font-weight:400;font-size:32px;color:#3a4651;margin:0}
.create-btn{grid-column:3;justify-self:end;display:flex;align-items:center;gap:10px;background:#091c47;color:#fff;padding:10px 18px;border-radius:15px;font-family:'Ubuntu',sans-serif;font-size:16px}
.btn-pressable{transition:transform .05s ease,filter .15s ease;box-shadow:0 1px 0 rgba(0,0,0,.12)}
.btn-pressable:hover{filter:brightness(1.05)}
.btn-pressable:active{transform:translateY(1px) scale(.99);filter:brightness(.95)}

/* ===== Botones ===== */
.btn{height:36px;padding:0 14px;border-radius:10px;font-family:'Ubuntu',sans-serif}
.btn-primary{background:#091c47;color:#fff}
.btn-secondary{background:#f1f3f4;color:#111;border:1px solid #e5e7eb}
.btn:disabled{cursor:not-allowed;opacity:0.5;filter:grayscale(50%)}
.btn:disabled:hover{filter:grayscale(50%) brightness(1)}
.btn:disabled:active{transform:none}

/* ===== Buscador ===== */
.search-panel{display:flex;gap:16px;margin-bottom:16px;justify-content:center;align-items:center;flex-wrap:wrap}
.search-input-wrapper{position:relative;width:360px;max-width:92vw}
.search-input-wrapper input{width:100%;height:40px;background:#c4c4c4;border:1px solid #0b0b0b;border-radius:10px;padding:0 40px 0 12px;font-size:13px;color:#3a4651;font-weight:600}
.search-input-wrapper input::placeholder{color:rgba(58,70,81,.5);font-weight:400}
.search-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:20px;height:20px;max-width:20px;max-height:20px;color:rgba(58,70,81,.5);stroke:currentColor;stroke-width:2;fill:none;pointer-events:none}
.search-btn {
    background: #091C47 !important;
    color: #fff !important;
    border-radius: 10px !important;
    height: 32px !important;
    min-width: 80px !important;
    font-size: 16x !important;
    padding: 0 32px !important;
}

/* ===== Encabezados de tabla ===== */
.grid-headers th {
    background: #949BA2 !important;
    color: #fff;
    height: 56px;
    font-size: 17px;
    vertical-align: middle;
}
</style>
@endpush

@push('scripts')
<script>
</script>
@endpush
