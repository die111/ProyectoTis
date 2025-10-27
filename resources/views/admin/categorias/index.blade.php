@extends('layouts.app')

@section('title', 'Categorías · Admin')

@section('content')
<!-- CABECERA / ACCIONES -->
<div class="content-header">
    <h1 class="content-title">Categorías</h1>
    <a href="{{ route('admin.categorias.create') }}" class="create-btn btn-pressable">
        <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
        </svg>
        <span>Crear</span>
    </a>
</div>

<!-- BUSCADOR -->
<form id="searchForm" class="search-panel" action="{{ route('admin.categorias.index') }}" method="GET">
    <div class="search-input-wrapper">
        <input id="searchInput" name="search" type="text" value="{{ $q ?? '' }}" placeholder="Buscar por nombre o descripción..." aria-label="Buscar categoría"/>
        <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </div>
    <button class="search-btn btn-pressable" type="submit">Buscar</button>
</form>

<!-- TABLA DE CATEGORÍAS -->
<div class="table-card overflow-x-auto">
    <table class="w-full min-w-[600px] text-center align-middle">
        <thead class="grid-headers">
            <tr>
                <th class="cell header whitespace-nowrap">Nombre</th>
                <th class="cell header whitespace-nowrap">Descripción</th>
                <th class="cell header whitespace-nowrap">Estado</th>
                <th class="cell header whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $i => $c)
            <tr class="{{ $i % 2 === 0 ? '' : 'bg-[#d7dde4]' }} hover:bg-blue-50 transition-colors">
                <td class="py-3 px-2 text-gray-900 align-middle">{{ $c->nombre ?? '—' }}</td>
                <td class="py-3 px-2 text-gray-700 align-middle">{{ $c->descripcion ?? '—' }}</td>
                <td class="py-3 px-2 align-middle">
                    @if($c->is_active ?? true)
                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Activo</span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Inactivo</span>
                    @endif
                </td>
                <td class="py-2 px-2 align-middle">
                    <div class="flex flex-wrap gap-2 justify-center">
                        <a href="{{ route('admin.categorias.edit', $c->id) }}" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Editar</a>
                        @if($c->is_active ?? true)
                        <form action="{{ route('admin.categorias.deactivate', $c->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-secondary btn-pressable px-3 py-1 text-sm">Desactivar</button>
                        </form>
                        @else
                        <form action="{{ route('admin.categorias.activate', $c->id) }}" method="POST" style="display:inline-block;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Activar</button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay categorías registradas.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="table-footer">
        {{ $categories->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
:root{
  --primary-dark-blue:#091c47;
  --table-header-bg:rgba(58,70,81,.5);
  --table-row-alt-bg:#d7dde4;
  --table-bg:#eef0f3;
  --text-dark:#3a4651;
  --white:#fff;
}

/* ===== Header ===== */
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:24px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:'Roboto',sans-serif;font-weight:400;font-size:32px;color:var(--text-dark);margin:0}
.create-btn{grid-column:3;justify-self:end;display:flex;align-items:center;gap:10px;background:var(--primary-dark-blue);color:#fff;padding:10px 18px;border-radius:15px;font-family:'Ubuntu',sans-serif;font-size:16px}
.btn-pressable{transition:transform .05s ease,filter .15s ease;box-shadow:0 1px 0 rgba(0,0,0,.12)}
.btn-pressable:hover{filter:brightness(1.05)}
.btn-pressable:active{transform:translateY(1px) scale(.99);filter:brightness(.95)}

/* ===== Botones ===== */
.btn{height:36px;padding:0 14px;border-radius:10px;font-family:'Ubuntu',sans-serif}
.btn-primary{background:var(--primary-dark-blue);color:#fff}
.btn-secondary{background:#f1f3f4;color:#111;border:1px solid #e5e7eb}

/* ===== Buscador ===== */
.search-panel{display:flex;gap:16px;margin-bottom:16px;justify-content:center;align-items:center;flex-wrap:wrap}
.search-input-wrapper{position:relative;width:360px;max-width:92vw}
.search-input-wrapper input{width:100%;height:40px;background:rgba(226,232,240,0.7);border:none;border-radius:10px;padding:0 40px 0 12px;font-size:13px;color:var(--text-dark);font-weight:600;box-shadow:0 0 0 1px rgba(148,163,184,0.4)}
.search-input-wrapper input::placeholder{color:rgba(58,70,81,.5);font-weight:400}
.search-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:rgba(58,70,81,.5);stroke:currentColor;stroke-width:2;fill:none;pointer-events:none}
.search-btn{height:40px;padding:0 14px;border-radius:30px;background:var(--primary-dark-blue);color:#fff;font-family:'Roboto',sans-serif;font-weight:500;font-size:13px;letter-spacing:1.25px}

/* ===== Tabla card ===== */
.table-card{
  width:100%;
  max-width:100vw;
  margin:0 auto 16px auto;
  background:var(--table-bg);
  border-radius:10px;
  overflow-x:auto;
  border:1px solid #cfd6df;
}
table{border-collapse:separate;border-spacing:0;width:100%;background:var(--table-bg);}
.grid-headers th{background:var(--table-header-bg);color:#fff;padding:14px 8px;text-align:center;font-family:'Quicksand',sans-serif;font-weight:700;font-size:14px;white-space:nowrap;}
td, th{vertical-align:middle;padding:8px 6px;}
tbody tr{transition:background 0.15s;}
tbody tr:nth-child(even){background:var(--table-row-alt-bg);}
tbody tr:nth-child(odd){background:var(--white);}
tbody tr:hover{background-color:#f0f8ff;}

/* footer tabla */
.table-footer{width:100%;max-width:92vw;margin:6px auto 0;display:flex;justify-content:flex-end;color:#445063;font-size:13px}
</style>
@endpush

@push('scripts')
<script>
</script>
@endpush