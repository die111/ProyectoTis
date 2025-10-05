@extends('layouts.app')
@section('title', 'Roles · Admin')

@section('content')
<!--  CABECERA / ACCIONES   -->
<div class="content-header">
  <h1 class="content-title">Roles</h1>

  <a href="{{ route('admin.roles.create') }}" class="create-btn btn-pressable">
    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
      <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
    </svg>
    <span>Crear</span>
  </a>
</div>

<!--  BUSCADOR (filtra por ID o Nombre) -->
<form id="searchForm" class="search-panel" action="{{ route('admin.roles.index') }}" method="GET">
  <div class="search-input-wrapper">
    <input id="searchInput" name="search" type="text" value="{{ $query ?? '' }}" placeholder="Buscar por nombre o descripción..." aria-label="Buscar rol"/>
    <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
      <circle cx="11" cy="11" r="7"></circle>
      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
  </div>
  <button class="search-btn btn-pressable" type="submit">Buscar</button>
</form>

<!--  TABLA DE ROLES (HTML table)   -->
<div class="table-card overflow-x-auto">
  <table class="w-full min-w-[600px] text-center align-middle">
    <thead class="grid-headers">
      <tr>
        <th class="cell header whitespace-nowrap">ID</th>
        <th class="cell header whitespace-nowrap">Nombre de Rol</th>
        <th class="cell header whitespace-nowrap">Descripción</th>
        <th class="cell header whitespace-nowrap">Acciones</th>
      </tr>
    </thead>
    <tbody>
      @foreach($roles as $i => $role)
      <tr class="{{ $i % 2 === 0 ? '' : 'bg-[#d7dde4]' }} hover:bg-blue-50 transition-colors">
        <td class="py-3 px-2 font-semibold text-gray-800 align-middle">{{ $role->id }}</td>
        <td class="py-3 px-2 text-gray-900 align-middle">{{ $role->name }}</td>
        <td class="py-3 px-2 text-gray-700 align-middle">{{ $role->description ?? '—' }}</td>
        <td class="py-2 px-2 align-middle">
          <div class="flex flex-wrap gap-2 justify-center">
            <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Editar</a>
            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline-block;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-secondary btn-pressable px-3 py-1 text-sm" onclick="return confirm('¿Deshabilitar este rol?')">Deshabilitar</button>
            </form>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<!-- ===== Modal Crear Rol (frontend only) ===== -->
<div id="roleCreateModal" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateRoleTitle">
    <div class="modal-header">
      <h2 id="modalCreateRoleTitle">Crear Rol</h2>
      <button class="modal-close" type="button" aria-label="Cerrar">×</button>
    </div>

    <div class="modal-body">
      <form id="roleCreateForm" class="modal-form" action="#" method="POST" data-mock="1" novalidate>
        <div class="form-grid-1">
          <div class="field">
            <label for="r_codigo">ID <span class="req">*</span></label>
            <input id="r_codigo" name="codigo" type="text" required placeholder="Ej: 5">
          </div>

          <div class="field">
            <label for="r_nombre">Nombre <span class="req">*</span></label>
            <input id="r_nombre" name="nombre" type="text" required placeholder="Ej: supervisor">
          </div>

          <div class="field">
            <label for="r_desc">Descripción</label>
            <textarea id="r_desc" name="descripcion" rows="3" placeholder="Descripción del rol..."></textarea>
          </div>
        </div>
      </form>
    </div>

    <div class="modal-footer">
      <button class="btn btn-secondary" type="button" data-close>Cancelar</button>
      <button id="btnRoleSave" form="roleCreateForm" class="btn btn-primary" type="submit">Crear Rol</button>
    </div>
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
  --gray-200:#e5e7eb;
  --gray-500:#9aa0a6;
}

/* ===== Header ===== */
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:24px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:'Roboto',sans-serif;font-weight:400;font-size:32px;color:var(--text-dark);margin:0}
.create-btn{grid-column:3;justify-self:end;display:flex;align-items:center;gap:10px;background:var(--primary-dark-blue);color:#fff;padding:10px 18px;border-radius:15px;font-family:'Ubuntu',sans-serif;font-size:16px}
.btn-pressable{transition:transform .05s ease,filter .15s ease;box-shadow:0 1px 0 rgba(0,0,0,.12)}
.btn-pressable:hover{filter:brightness(1.05)}
.btn-pressable:active{transform:translateY(1px) scale(.99);filter:brightness(.95)}

/* ===== Buscador ===== */
.search-panel{display:flex;gap:16px;margin-bottom:16px;justify-content:center;align-items:center;flex-wrap:wrap}
.search-input-wrapper{position:relative;width:360px;max-width:92vw}
.search-input-wrapper input{width:100%;height:40px;background:#c4c4c4;border:1px solid #0b0b0b;border-radius:10px;padding:0 40px 0 12px;font-size:13px;color:var(--text-dark);font-weight:600}
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
td,th{vertical-align:middle;}
tbody tr{transition:background 0.15s;}
tbody tr:nth-child(even){background:var(--table-row-alt-bg);}
tbody tr:nth-child(odd){background:var(--white);}
@media (max-width: 800px) {
  .table-card{padding:0;}
  table{font-size:13px;}
  .grid-headers th, td{padding:10px 4px;}
}
@media (max-width: 600px) {
  .table-card{border-radius:0;}
  table{min-width:480px;}
  .content-header, .table-footer{flex-direction:column;gap:8px;}
}

/* footer tabla */
.table-footer{width:100%;max-width:92vw;margin:6px auto 0;display:flex;justify-content:flex-end;color:#445063;font-size:13px}

/* ===== Acciones ===== */
.actions-panel{display:flex;justify-content:center;gap:120px;margin:18px 0 0}
.action-btn{height:39px;border-radius:10px;background:var(--primary-dark-blue);color:#fff;font-family:'Ubuntu',sans-serif;font-size:18px;padding:0 20px;border:none}

/* ===== Modal ===== */
.modal-overlay{position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);display:none;place-items:center;opacity:0;pointer-events:none;transition:opacity .2s ease}
.modal-overlay.show{display:grid;opacity:1;pointer-events:auto}
.modal{width:min(520px,95vw);max-height:92vh;background:#fff;border-radius:16px;box-shadow:0 12px 32px rgba(0,0,0,.25);overflow:hidden;transform:translateY(8px) scale(.98);opacity:0;transition:transform .2s ease,opacity .2s ease}
.modal-overlay.show .modal{transform:translateY(0) scale(1);opacity:1}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:var(--primary-dark-blue);color:#fff}
.modal-header h2{margin:0;font-size:20px;font-weight:600}
.modal-close{width:36px;height:36px;border-radius:999px;background:transparent;color:#fff;font-size:22px;line-height:1;border:none;display:grid;place-items:center}
.modal-close:hover{background:rgba(255,255,255,.12)}
.modal-body{padding:14px;background:#f1f1f1;max-height:calc(92vh - 120px);overflow:auto}
.modal-footer{display:flex;justify-content:center;gap:10px;padding:10px 12px;background:#fff}

/* Form modal */
.modal-form .form-grid-1{display:grid;grid-template-columns:1fr;gap:10px}
.field{display:flex;flex-direction:column;gap:6px;background:#fff;border-radius:8px;padding:8px 10px;border-bottom:1px solid rgba(0,0,0,.08)}
.field label{font-size:12px;color:#111827}
.req{color:#e11d48}
.modal-form input,.modal-form textarea{background:#fff;border:1px solid #d1d5db;border-radius:8px;height:36px;padding:0 8px;font-size:14px}
.modal-form textarea{min-height:66px;padding:8px}

/* Botones modal: azul crear, gris cancelar */
.btn{height:36px;padding:0 14px;border-radius:10px;font-family:'Ubuntu',sans-serif}
.btn-primary{background:var(--primary-dark-blue);color:#fff}
.btn-secondary{background:#f1f3f4;color:#111;border:1px solid var(--gray-200)}
</style>
@endpush

@push('scripts')
<script>
// ...existing code...
</script>
@endpush