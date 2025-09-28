@extends('layouts.app')
@section('title', 'Roles · Admin')

@section('content')
<!--  CABECERA / ACCIONES   -->
<div class="content-header">
  <h1 class="content-title">Roles</h1>

  <button class="create-btn btn-pressable" type="button" id="openCreate">
    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
      <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
    </svg>
    <span>Crear</span>
  </button>
</div>

<!--  BUSCADOR (filtra por ID o Nombre) -->
<form id="searchForm" class="search-panel" action="#" onsubmit="return false;">
  <div class="search-input-wrapper">
    <input id="searchInput" type="text" placeholder="Buscar por ID o nombre..." aria-label="Buscar rol"/>
    <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
      <circle cx="11" cy="11" r="7"></circle>
      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
  </div>
  <button id="btnSearch" class="search-btn btn-pressable" type="button">Buscar</button>
</form>

<!--  TARJETA TABLA (encabezados fijos + cuerpo con scroll)   -->
<div class="table-card">
  <!-- Encabezados -->
  <div class="grid grid-headers">
    <div class="cell header">ID</div>
    <div class="cell header">Nombre de Rol</div>
    <div class="cell header">Descripción</div>
  </div>

  <!-- Cuerpo scrollable (inicia vacío) -->
  <div id="rolesBody" class="roles-body"></div>
</div>

<!--  FOOTER DE TABLA: contador de selección -->
<div class="table-footer">
  <span id="selCount">Seleccionados: 0</span>
</div>

<!--  BOTONES SECUNDARIOS (simulados)  -->
<div class="actions-panel">
  <button id="btnActivate" class="action-btn btn-pressable" type="button">Activar</button>
  <button id="btnDeactivate" class="action-btn btn-pressable" type="button">Desactivar</button>
  <button id="btnEdit" class="action-btn btn-pressable" type="button">Editar</button>
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
            <input id="r_codigo" name="codigo" type="text" placeholder="R01" maxlength="40" autocomplete="off" required>
          </div>

          <div class="field">
            <label for="r_nombre">Nombre <span class="req">*</span></label>
            <input id="r_nombre" name="nombre" type="text" required placeholder="Responsable de Área">
          </div>

          <div class="field">
            <label for="r_desc">Descripción</label>
            <textarea id="r_desc" name="descripcion" rows="3" placeholder="Se encarga de administrar un área en específico"></textarea>
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
.search-panel{display:flex;gap:16px;margin-bottom:16px;justify-content:center;align-items:center}
.search-input-wrapper{position:relative;width:360px;max-width:92vw}
.search-input-wrapper input{width:100%;height:40px;background:#c4c4c4;border:1px solid #0b0b0b;border-radius:10px;padding:0 40px 0 12px;font-size:13px;color:var(--text-dark);font-weight:600}
.search-input-wrapper input::placeholder{color:rgba(58,70,81,.5);font-weight:400}
.search-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:rgba(58,70,81,.5);stroke:currentColor;stroke-width:2;fill:none;pointer-events:none}
.search-btn{height:40px;padding:0 14px;border-radius:30px;background:var(--primary-dark-blue);color:#fff;font-family:'Roboto',sans-serif;font-weight:500;font-size:13px;letter-spacing:1.25px}

/* ===== Tabla card ===== */
.table-card{
  width:720px;max-width:92vw;margin:0 auto;background:var(--table-bg);
  border-radius:10px;overflow:hidden;border:1px solid #cfd6df;
}
.grid{display:grid;grid-template-columns:120px 1fr 1fr}
.grid-headers{background:var(--table-header-bg);color:#fff}
.cell.header{
  padding:14px 8px;text-align:center;font-family:'Quicksand',sans-serif;font-weight:700;font-size:14px;
}
.roles-body{
  max-height:300px; /* <- scroll vertical al crecer */
  overflow-y:auto;background:var(--white);
}
.row{display:grid;grid-template-columns:120px 1fr 1fr;cursor:pointer;user-select:none}
.row .cell{
  padding:12px 10px;min-height:36px;display:flex;align-items:center;justify-content:center;text-align:center;
}
.roles-body .row:nth-child(odd){ background: var(--white); }
.roles-body .row:nth-child(even){ background: var(--table-row-alt-bg); }

/* selección */
.row.selected{ outline:2px solid #2563eb; background:#e8f0ff !important; }

/* footer tabla */
.table-footer{width:720px;max-width:92vw;margin:6px auto 0;display:flex;justify-content:flex-end;color:#445063;font-size:13px}

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
(function(){
  const $ = (s, c=document)=>c.querySelector(s);
  const $$ = (s, c=document)=>Array.from(c.querySelectorAll(s));

  const rolesBody = $('#rolesBody');
  const selCount  = $('#selCount');
  let roles = [];              // almacén en memoria (arranca vacío)
  let selectedIds = new Set(); // ids seleccionados
  let query = '';              // filtro de búsqueda

  // ---------- helpers ----------
  const norm = s => String(s ?? '').toLowerCase().trim();

  function visibleRoles(){
    if(!query) return roles;
    const q = norm(query);
    return roles.filter(r => norm(r.id).includes(q) || norm(r.name).includes(q));
  }

  function renderRoles(){
    const data = visibleRoles();
    rolesBody.innerHTML = data.map(r => {
      const isSel = selectedIds.has(String(r.id));
      return `
        <div class="row ${isSel ? 'selected' : ''}" data-id="${String(r.id)}" title="Click para seleccionar">
          <div class="cell">${r.id}</div>
          <div class="cell">${r.name}</div>
          <div class="cell">${r.description || '—'}</div>
        </div>`;
    }).join('');
    // bind selección por fila
    $$('.row', rolesBody).forEach(row => {
      row.addEventListener('click', () => {
        const id = row.getAttribute('data-id');
        if(selectedIds.has(id)) { selectedIds.delete(id); row.classList.remove('selected'); }
        else { selectedIds.add(id); row.classList.add('selected'); }
        updateSelCount();
      });
    });
  }

  function updateSelCount(){
    selCount.textContent = `Seleccionados: ${selectedIds.size}`;
  }

  // ---------- búsqueda ----------
  $('#btnSearch')?.addEventListener('click', () => {
    query = $('#searchInput')?.value || '';
    renderRoles();
    // al buscar, no tocamos selección previa
  });
  $('#searchInput')?.addEventListener('keydown', (e)=>{
    if(e.key === 'Enter'){ e.preventDefault(); $('#btnSearch').click(); }
  });

  // ---------- modal crear ----------
  const modal = $('#roleCreateModal');
  const form  = $('#roleCreateForm');

  function openModal(){
    modal.classList.add('show');
    modal.setAttribute('aria-hidden','false');
    document.body.style.overflow='hidden';
    $('#r_codigo')?.focus();
  }
  function closeModal(){
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden','true');
    document.body.style.overflow='';
  }

  $('#openCreate')?.addEventListener('click', e=>{ e.preventDefault(); openModal(); });
  modal?.addEventListener('click', e=>{
    if(e.target===modal || e.target.closest('.modal-close') || e.target.closest('[data-close]')) closeModal();
  });
  document.addEventListener('keydown', e=>{
    if(modal.classList.contains('show') && e.key==='Escape') closeModal();
  });

  form?.addEventListener('submit', (e)=>{
    e.preventDefault();
    const id   = ($('#r_codigo')?.value || '').trim();
    const name = ($('#r_nombre')?.value || '').trim();
    const desc = ($('#r_desc')?.value || '').trim();

    if(!id){ alert('El ID es obligatorio.'); $('#r_codigo')?.focus(); return; }
    if(!name){ alert('El nombre es obligatorio.'); $('#r_nombre')?.focus(); return; }

    // evita duplicados por ID (simulado)
    if(roles.some(r => String(r.id) === String(id))){
      alert('Ese ID ya existe. Usa otro ID.');
      $('#r_codigo')?.focus();
      return;
    }

    roles.push({ id, name, description: desc });
    // si hay filtro activo, mantenlo
    renderRoles();
    form.reset();
    closeModal();
  });

  // ---------- acciones simuladas (sobre selección) ----------
  function requireSelection(){
    if(selectedIds.size === 0){
      alert('Selecciona al menos un rol.');
      return false;
    }
    return true;
  }

  $('#btnActivate')?.addEventListener('click', ()=>{
    if(!requireSelection()) return;
    alert(`Activar (simulación): ${Array.from(selectedIds).join(', ')}`);
    console.log('MOCK — Activar IDs:', Array.from(selectedIds));
  });

  $('#btnDeactivate')?.addEventListener('click', ()=>{
    if(!requireSelection()) return;
    alert(`Desactivar (simulación): ${Array.from(selectedIds).join(', ')}`);
    console.log('MOCK — Desactivar IDs:', Array.from(selectedIds));
  });

  $('#btnEdit')?.addEventListener('click', ()=>{
    if(!requireSelection()) return;
    if(selectedIds.size > 1){
      alert('Selecciona solo un rol para editar.');
      return;
    }
    const id = Array.from(selectedIds)[0];
    const r = roles.find(x => String(x.id) === String(id));
    if(!r){ alert('Rol no encontrado.'); return; }
    // Simulación: precargar modal con datos para “editar”
    openModal();
    $('#modalCreateRoleTitle').textContent = 'Editar Rol (simulación)';
    $('#btnRoleSave').textContent = 'Guardar';
    $('#r_codigo').value  = r.id;    // permitimos editar ID en simulación
    $('#r_nombre').value  = r.name;
    $('#r_desc').value    = r.description || '';
    // al guardar, sobreescribimos el existente (simulado)
    form.onsubmit = (e) => {
      e.preventDefault();
      const nid   = ($('#r_codigo')?.value || '').trim();
      const nname = ($('#r_nombre')?.value || '').trim();
      const ndesc = ($('#r_desc')?.value || '').trim();
      if(!nid){ alert('El ID es obligatorio.'); $('#r_codigo')?.focus(); return; }
      if(!nname){ alert('El nombre es obligatorio.'); $('#r_nombre')?.focus(); return; }
      // si cambió ID, validar duplicado
      if(nid !== r.id && roles.some(x => String(x.id) === String(nid))){
        alert('Ese ID ya existe. Usa otro ID.');
        $('#r_codigo')?.focus(); return;
      }
      r.id = nid; r.name = nname; r.description = ndesc;
      // actualizar selección al nuevo ID
      selectedIds = new Set([String(nid)]);
      $('#modalCreateRoleTitle').textContent = 'Crear Rol';
      $('#btnRoleSave').textContent = 'Crear Rol';
      form.reset();
      // restaurar submit de crear
      form.onsubmit = null;
      closeModal();
      renderRoles();
      updateSelCount();
    };
  });

  // render inicial (sin filas)
  renderRoles();
  updateSelCount();
})();
</script>
@endpush
