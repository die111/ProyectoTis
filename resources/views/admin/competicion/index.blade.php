@extends('layouts.app')
@section('title', 'Competicion · Admin')

@section('content')
<!--  CABECERA / ACCIONES   -->
<div class="content-header">
  <h1 class="content-title">Competicion</h1>

  <button class="create-btn btn-pressable" type="button" id="openCreate">
    <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
      <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
    </svg>
    <span>Crear</span>
  </button>
</div>

<!--  BUSCADOR (front) -->
<form id="searchForm" class="search-panel" action="#" method="GET">
  <div class="search-input-wrapper">
    <input
      id="searchInput"
      type="text"
      name="q"
      placeholder="Encuentra la competicion"
      aria-label="Buscar competición"
    />
    <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
      <circle cx="11" cy="11" r="7"></circle>
      <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
    </svg>
  </div>
  <button id="btnSearch" class="search-btn btn-pressable" type="submit">Buscar</button>
</form>

<!--  TABLA (GRID)   -->
<div class="table-panel">
  <div id="dataGrid" class="data-grid">
    <!-- Cabeceras -->
    <div class="grid-header">ID</div>
    <div class="grid-header">Nombre de Competicion</div>
    <div class="grid-header">Fecha Inicio</div>
    <div class="grid-header">Fecha Fin</div>

    <!-- Cuerpo dinámico: inicia vacío -->
    <div id="gridBody" class="grid-body"></div>
  </div>
</div>

<!--  ACCIONES   -->
<div class="actions-panel">
  <button id="btnDelete" class="action-btn delete-btn btn-pressable" type="button">Eliminar</button>
  <button id="btnExport" class="action-btn export-btn btn-pressable" type="button">Exportar</button>
</div>

<!-- Toast -->
<div id="toast" class="toast" role="status" aria-live="polite" aria-atomic="true"></div>

<!-- MODAL: CREAR COMPETICIÓN  -->
<div id="createModal" class="modal-overlay" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalCreateTitle">
    <div class="modal-header">
      <h2 id="modalCreateTitle">Crear Competicion</h2>
      <button class="modal-close" type="button" aria-label="Cerrar">×</button>
    </div>

    <div class="modal-body">
      <p class="form-note">Los campos con <strong>*</strong> son obligatorios</p>

      <!-- FRONT PURO -->
      <form id="createForm" class="modal-form" action="#" data-mock="1" novalidate>
        @csrf

        <!-- Campos principales -->
        <div class="form-grid-2">
          <!-- ID (obligatorio) -->
          <div class="field">
            <label for="c_id">ID <span class="req">*</span></label>
            <input
              id="c_id"
              name="codigo"
              type="text"
              class="code-input"
              placeholder="ID-001"
              maxlength="20"
              pattern="[A-Za-z0-9\-]{1,20}"
              title="Hasta 20 caracteres: letras, números o guiones."
              autocomplete="off"
              required
            >
          </div>

          <!-- NOMBRE (obligatorio) -->
          <div class="field">
            <label for="c_nombre">Nombre <span class="req">*</span></label>
            <input id="c_nombre" name="nombre" type="text" required placeholder="Oh SanSi">
          </div>

          <div class="field">
            <label for="c_inicio">Fecha Inicio <span class="req">*</span></label>
            <input id="c_inicio" name="fecha_inicio" type="date" required>
          </div>

          <div class="field">
            <label for="c_fin">Fecha Fin <span class="req">*</span></label>
            <input id="c_fin" name="fecha_fin" type="date" required>
          </div>

          <div class="field span-2 field-desc">
            <label for="c_desc">Descripción</label>
            <textarea id="c_desc" name="descripcion" rows="3"
              placeholder="La Olimpiada de Ciencias y Tecnología Oh SanSi es un evento académico..."></textarea>
          </div>

          <!-- Áreas (catálogo) -->
          <div class="field with-add">
            <label for="areaSelect">Áreas:</label>
            <select id="areaSelect">
              @isset($areasCatalog)
                @foreach($areasCatalog as $a)
                  <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                @endforeach
              @else
                <option value="1">Matemáticas</option>
                <option value="2">Física</option>
                <option value="3">Química</option>
              @endisset
            </select>
            <button type="button" class="pill-add" id="addAreaBtn" aria-label="Agregar área">+</button>
          </div>

          <!-- Niveles fijos -->
          <div class="field with-add">
            <label for="nivelSelect">Niveles:</label>
            <select id="nivelSelect">
              <option>1ro Secundaria</option>
              <option>2do Secundaria</option>
              <option>3ro Secundaria</option>
              <option>4to Secundaria</option>
              <option>5to Secundaria</option>
              <option>6to Secundaria</option>
            </select>
            <button type="button" class="pill-add" id="addNivelBtn" aria-label="Agregar nivel">+</button>
          </div>
        </div>

        <!-- Listas Áreas / Niveles -->
        <div class="lists-row">
          <div class="list-card">
            <div class="list-title">AREAS</div>
            <ul id="areasList" class="list-box"></ul>
          </div>
          <div class="list-card">
            <div class="list-title">NIVELES</div>
            <ul id="nivelesList" class="list-box"></ul>
          </div>
        </div>

        <!-- Fases + Cupos (catálogo) -->
        <div class="inline-add">
          <div class="mini-field">
            <label for="faseSelect">Fases:</label>
            <select id="faseSelect">
              @isset($fasesCatalog)
                @foreach($fasesCatalog as $f)
                  <option value="{{ $f->id }}" data-default-cupos="{{ $f->default_cupos ?? '' }}">{{ $f->nombre }}</option>
                @endforeach
              @else
                <option value="1" data-default-cupos="100">Fase 1</option>
                <option value="2" data-default-cupos="10">Fase 2</option>
              @endisset
            </select>
          </div>
          <div class="mini-field">
            <label for="faseCupos">Clasificados:</label>
            <input id="faseCupos" type="number" min="1" placeholder="100">
          </div>
          <button type="button" class="pill-add" id="addFaseBtn" aria-label="Agregar fase">+</button>
        </div>

        <!-- Tabla Fases -->
        <div class="list-card table-card">
          <div class="table-head">
            <span>FASES</span><span>CLASIFICADOS</span><span></span>
          </div>
          <div id="fasesTable" class="table-body"></div>
        </div>

        <!-- (opcional) ocultos por si luego pasas al backend -->
        <div id="hiddenFields"></div>
      </form>
    </div>

    <div class="modal-footer">
      <button class="btn btn-secondary" type="button" data-close>Cancelar</button>
      <button id="btnSave" form="createForm" class="btn btn-primary" type="submit" disabled>
        Guardar y Publicar
      </button>
    </div>
  </div>
</div>

<style>
:root{
  --primary-dark-blue:#091c47; --primary-light-gray:#f5f4f4;
  --text-white:#fff; --text-dark-gray:#3a4651;
  --text-light-gray:rgba(58,70,81,.5); --table-header-bg:rgba(58,70,81,.5);
  --table-bg:rgba(234,234,234,.63);
  --font-ubuntu:'Ubuntu',sans-serif; --font-roboto:'Roboto',sans-serif;
}
*{box-sizing:border-box}
body{margin:0;font-family:var(--font-roboto);color:var(--text-dark-gray);background:#fff}
button{cursor:pointer;border:none;font-family:inherit}

/* Header */
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:30px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-weight:400;font-size:36px;color:var(--text-dark-gray);margin:0}
.create-btn{grid-column:3;justify-self:end;display:flex;align-items:center;gap:11px;background:var(--primary-dark-blue);color:#fff;padding:11px 19px;border-radius:15px;font-family:var(--font-ubuntu);font-size:16px}

/* Buscador */
.search-panel{display:flex;gap:16px;margin-bottom:30px;justify-content:center;align-items:center}
.search-input-wrapper{position:relative;width:360px}
.search-input-wrapper input{width:100%;height:40px;background:#c4c4c4;border:1px solid #0b0b0b;border-radius:10px;padding:0 40px 0 12px;font-size:13px}
.search-input-wrapper input::placeholder{color:var(--text-light-gray)}
.search-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:var(--text-light-gray);stroke:currentColor;stroke-width:2;fill:none}
.search-btn{height:40px;padding:0 14px;border-radius:30px;background:var(--primary-dark-blue);color:#fff;font-weight:500;font-size:13px;letter-spacing:1.25px}

/* Tabla */
.table-panel{background:var(--table-bg);padding:20px;border-radius:10px}
.data-grid{display:grid;grid-template-columns:87px 337px 190px 1fr;gap:1px;background:#ccc;border:1px solid #ccc;overflow:hidden}
.grid-header,.grid-cell{padding:17px 8px;text-align:center}
.grid-header{background:var(--table-header-bg);color:#fff;font-weight:700;font-size:14px}
.grid-cell{background:#fff;min-height:31px;user-select:none;cursor:default}
.grid-body{display:contents}
.grid-cell.row-selected{background:#eef3ff; outline:2px solid rgba(9,28,71,.18);}

/* Acciones */
.actions-panel{display:flex;justify-content:center;gap:200px;margin-top:40px}
.action-btn{height:39px;border-radius:10px;background:var(--primary-dark-blue);color:#fff;font-family:var(--font-ubuntu);font-size:18px}
.delete-btn,.export-btn{width:140px}

/* Toast */
.toast{position:fixed;z-index:50;right:16px;bottom:16px;min-width:220px;max-width:90vw;background:#111827;color:#fff;padding:10px 14px;border-radius:10px;font-size:14px;opacity:0;pointer-events:none;transform:translateY(8px);transition:opacity .2s ease,transform .2s ease}
.toast.show{opacity:1;transform:translateY(0)}

/* Modal */
.modal-overlay{position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);display:none;place-items:center;opacity:0;pointer-events:none;transition:opacity .2s ease}
.modal-overlay.show{display:grid;opacity:1;pointer-events:auto}
.modal{width:min(820px,95vw);max-height:92vh;background:#fff;border-radius:16px;box-shadow:0 12px 32px rgba(0,0,0,.25);overflow:hidden;transform:translateY(8px) scale(.98);opacity:0;transition:transform .2s ease,opacity .2s ease}
.modal-overlay.show .modal{transform:translateY(0) scale(1);opacity:1}

/* Encabezado azul */
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:var(--primary-dark-blue);color:#fff;border-bottom:1px solid rgba(255,255,255,.08)}
.modal-header h2{margin:0;font-size:20px;color:#fff;font-weight:600}
.modal-close{width:36px;height:36px;border-radius:999px;background:transparent;color:#fff;font-size:22px;display:grid;place-items:center}
.modal-close:hover{background:rgba(255,255,255,.12)}

/* Cuerpo modal */
.modal-body{padding:12px;background:#d9d9d9;max-height:calc(92vh - 120px);overflow:auto}
.modal-footer{display:flex;justify-content:center;gap:10px;padding:10px 12px;background:#fff}

/* === Botones del modal (colores) === */
.btn{
  height:36px;
  padding:0 16px;
  border-radius:10px;
  font-family:var(--font-ubuntu);
  font-weight:600;
  border:0;
  transition:filter .15s ease, transform .05s ease;
}
.btn:active{ transform:translateY(1px) scale(.99); }

/* Guardar y Publicar = AZUL */
.btn-primary{
  background: var(--primary-dark-blue); /* #091c47 */
  color:#fff;
}
.btn-primary:hover{ filter:brightness(1.07); }
.btn-primary:disabled{ opacity:.6; cursor:not-allowed; }

/* Cancelar = PLOMO (gris) */
.btn-secondary{
  background:#6b7280; /* plomo */
  color:#fff;
}
.btn-secondary:hover{ background:#4b5563; }


/* Form modal */
.form-note{font-weight:700;margin-bottom:8px;color:#111;font-size:13px}
.modal-form .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:10px 12px}
.modal-form .span-2{grid-column:span 2}
.modal-form .field{display:flex;flex-direction:column;gap:6px;background:#fff;border-radius:8px;padding:6px 10px;border-bottom:1px solid rgba(0,0,0,.12);position:relative}
.modal-form .field label{font-size:11px}
.modal-form input,.modal-form textarea,.modal-form select{background:#fff;border:1px solid #0b0b0b;border-radius:10px;height:34px;padding:0 8px;font-size:12px}
.modal-form textarea{min-height:60px;padding:8px 10px;resize:vertical;border-radius:10px}
.req{color:#dc2626;font-weight:700}

/* Código (ID) */
.code-input{text-transform:uppercase;letter-spacing:.4px}

/* + con select */
.field.with-add .pill-add{
  position:absolute;right:6px;top:calc(50% + 6px);transform:translateY(-50%);
  width:24px;height:24px;border-radius:50%;background:#fff;border:1px solid #111;color:#111;font-weight:700
}

/* Listas Áreas/Niveles */
.lists-row{display:flex;gap:12px;margin-top:12px}
.list-card{flex:1;background:#E9E9E9;border-radius:14px;padding:10px}
.list-title{font-weight:700;font-size:11px;text-align:center;padding-bottom:6px;border-bottom:1px solid rgba(0,0,0,.2)}
.list-box{list-style:none;margin:8px 0 0;padding:0 6px;max-height:100px;overflow:auto}
.list-box li{display:flex;justify-content:space-between;align-items:center;padding:5px 6px;border-bottom:1px solid rgba(0,0,0,.15);font-size:13px}
.list-remove{width:18px;height:18px;border-radius:6px;border:1px solid #aaa;background:#fff;line-height:1}

/* Fases */
.inline-add{display:flex;align-items:flex-end;gap:8px;margin:12px auto 8px;width:min(520px,100%);background:#fff;border-radius:10px;padding:6px 8px;border-bottom:1px solid rgba(0,0,0,.12)}
.mini-field{display:flex;flex-direction:column;gap:6px;flex:1}
.mini-field input, .mini-field select{height:34px}
.table-card{width:min(520px,100%);margin:8px auto;background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 0 rgba(0,0,0,.02)}
.table-head{display:grid;grid-template-columns:1fr 110px 32px;align-items:center;gap:8px;padding:10px 12px;background:#f3f4f6;color:#334155;font-weight:700;font-size:12px;border-bottom:1px solid #e5e7eb}
.table-body{max-height:120px;overflow:auto}
.table-row{display:grid;grid-template-columns:1fr 110px 32px;align-items:center;gap:8px;padding:10px 12px;border-top:1px solid #eef2f7}
.table-row:nth-child(odd){background:#fbfbfb}
.table-row span:nth-child(2){justify-self:end;min-width:64px;text-align:center;padding:3px 10px;font-size:12px;font-weight:600;color:#111827;background:#eef2ff;border:1px solid #c7d2fe;border-radius:999px}
.row-remove{justify-self:end;width:20px;height:20px;display:grid;place-items:center;border:1px solid #cbd5e1;border-radius:6px;background:#fff;color:#475569;cursor:pointer}
.row-remove:hover{background:#f8fafc}

/* Responsive */
@media (max-width:1200px){.data-grid{grid-template-columns:.5fr 1.5fr 1fr 1fr}}
@media (max-width:992px){.table-panel{overflow-x:auto}.data-grid{width:800px}}
@media (max-width:768px){
  .content-header{grid-template-columns:1fr;gap:12px}
  .create-btn{justify-self:start}
  .search-panel{flex-direction:column}
  .search-input-wrapper{width:100%}
  .actions-panel{flex-direction:column;gap:20px;align-items:center}
  .modal-form .form-grid-2{grid-template-columns:1fr}
  .lists-row{flex-direction:column}
}
</style>

<script>
(function(){
  const $ = (s, c=document)=>c.querySelector(s);
  const $$ = (s, c=document)=>Array.from(c.querySelectorAll(s));
  const toast = (m)=>{const t=$('#toast'); if(!t) return; t.textContent=m; t.classList.add('show'); setTimeout(()=>t.classList.remove('show'),2000);};
  const fmtDateDMY = (iso)=>{if(!iso) return ''; const d=new Date(iso+'T00:00:00'); if(isNaN(d)) return iso; const dd=String(d.getDate()).padStart(2,'0'); const mm=String(d.getMonth()+1).padStart(2,'0'); return `${dd}/${mm}/${d.getFullYear()}`;};

  /* ===== Estado ===== */
  const store = [];            // [{id, nombre, fecha_inicio, fecha_fin}]
  const selected = new Set();  // ids seleccionados (string)
  const gridBody = $('#gridBody');

  /* ===== Render filas tabla ===== */
  function addRowToDOM(row){
    const idStr = String(row.id);
    [idStr, row.nombre||'', fmtDateDMY(row.fecha_inicio), fmtDateDMY(row.fecha_fin)].forEach(text=>{
      const div=document.createElement('div');
      div.className='grid-cell';
      div.textContent=text;
      div.dataset.id=idStr;
      if(selected.has(idStr)) div.classList.add('row-selected');
      div.addEventListener('click',()=>toggleSelect(idStr));
      gridBody.appendChild(div);
    });
  }
  function clearBody(){ while(gridBody.firstChild) gridBody.removeChild(gridBody.firstChild); }
  function rerender(){ clearBody(); store.forEach(addRowToDOM); }

  /* ===== Selección ===== */
  function toggleSelect(id){
    const on = !selected.has(id);
    if(on) selected.add(id); else selected.delete(id);
    $$('.grid-cell[data-id="'+id+'"]').forEach(c=>c.classList.toggle('row-selected', on));
  }

  /* ===== Buscar (front) ===== */
  const searchForm=$('#searchForm'), searchInput=$('#searchInput');
  searchForm.addEventListener('submit',(e)=>{
    e.preventDefault();
    const term=(searchInput.value||'').trim().toLowerCase();
    if(!term){ $$('#gridBody .grid-cell').forEach(c=>c.style.display=''); return; }
    const ids=[...new Set($$('#gridBody .grid-cell').map(c=>c.dataset.id))];
    ids.forEach(id=>{
      const cells=$$('#gridBody .grid-cell[data-id="'+id+'"]');
      const visible=cells.some(c=>c.textContent.toLowerCase().includes(term));
      cells.forEach(c=>c.style.display=visible?'':'none');
    });
  });

  /* ===== Exportar ===== */
  $('#btnExport').addEventListener('click', ()=>{
    if(!store.length){ toast('No hay competencias para exportar.'); return; }
    const head=['ID','Nombre de Competicion','Fecha Inicio','Fecha Fin'];
    const rows=store.map(r=>[r.id,r.nombre,fmtDateDMY(r.fecha_inicio),fmtDateDMY(r.fecha_fin)]);
    const esc=v=>`"${String(v).replace(/"/g,'""')}"`;
    const csv=[head,...rows].map(r=>r.map(esc).join(',')).join('\n');
    const blob=new Blob([csv],{type:'text/csv;charset=utf-8;'});
    const url=URL.createObjectURL(blob);
    const a=document.createElement('a'); const ts=new Date().toISOString().slice(0,19).replace(/[:T]/g,'-');
    a.href=url; a.download=`competiciones-${ts}.csv`; document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
    toast('Exportado a CSV.');
  });

  /* ===== Eliminar ===== */
  $('#btnDelete').addEventListener('click', ()=>{
    if(!selected.size){ toast('Selecciona una o más filas.'); return; }
    if(!confirm('¿Eliminar las competencias seleccionadas?')) return;
    for(const id of [...selected]){
      const idx=store.findIndex(r=>String(r.id)===String(id));
      if(idx>-1) store.splice(idx,1);
      $$('.grid-cell[data-id="'+id+'"]').forEach(n=>n.remove());
      selected.delete(id);
    }
    toast('Competencia(s) eliminada(s).');
  });

  /* ===== Modal ===== */
  const modal=$('#createModal'), body=document.body;
  function openModal(){ modal.classList.add('show'); modal.setAttribute('aria-hidden','false'); body.style.overflow='hidden'; initDateGuards(); $('#c_id')?.focus(); seedDefaultCupos(); }
  function closeModal(){ modal.classList.remove('show'); modal.setAttribute('aria-hidden','true'); body.style.overflow=''; }
  $('#openCreate').addEventListener('click', e=>{ e.preventDefault(); openModal(); });
  modal.addEventListener('click', e=>{ if(e.target===modal || e.target.closest('.modal-close') || e.target.closest('[data-close]')) closeModal(); });
  document.addEventListener('keydown', e=>{ if(modal.classList.contains('show') && e.key==='Escape') closeModal(); });

  /* ===== Datos del modal (Áreas, Niveles, Fases) ===== */
  const areas=[], niveles=[], fases=[];
  const hidden = $('#hiddenFields');
  const form=$('#createForm'), btnSave=$('#btnSave');

  function syncHidden(){
    hidden.innerHTML='';
    areas.forEach(a=>{ const i=document.createElement('input'); i.type='hidden'; i.name='areas_ids[]'; i.value=a.id; hidden.appendChild(i); });
    niveles.forEach(v=>{ const i=document.createElement('input'); i.type='hidden'; i.name='niveles[]'; i.value=v; hidden.appendChild(i); });
    fases.forEach(f=>{ const i1=document.createElement('input'); i1.type='hidden'; i1.name='fases_ids[]'; i1.value=f.id; hidden.appendChild(i1);
                       const i2=document.createElement('input'); i2.type='hidden'; i2.name='fases_cupos[]'; i2.value=f.cupos; hidden.appendChild(i2); });
  }

  function renderList(arr, ul, textMap){
    ul.innerHTML='';
    arr.forEach((val, idx)=>{
      const li=document.createElement('li');
      li.innerHTML=`<span>${textMap(val)}</span>`;
      const rm=document.createElement('button'); rm.type='button'; rm.className='list-remove'; rm.textContent='×'; rm.title='Quitar';
      rm.addEventListener('click',()=>{ arr.splice(idx,1); renderAll(); });
      li.appendChild(rm); ul.appendChild(li);
    });
  }

  function renderFases(){
    const tbody = $('#fasesTable'); tbody.innerHTML='';
    fases.forEach((f, idx)=>{
      const row=document.createElement('div'); row.className='table-row';
      row.innerHTML=`<span>${f.nombre}</span><span>${f.cupos}</span>`;
      const rm=document.createElement('button'); rm.type='button'; rm.className='row-remove'; rm.textContent='×'; rm.title='Quitar';
      rm.addEventListener('click',()=>{ fases.splice(idx,1); renderAll(); });
      row.appendChild(rm); tbody.appendChild(row);
    });
  }

  function renderAll(){
    renderList(areas,   $('#areasList'),  a=>a.nombre);
    renderList(niveles, $('#nivelesList'), v=>v);
    renderFases();
    syncHidden();
    checkValidity();
  }

  /* ---- ÁREAS ---- */
  $('#addAreaBtn')?.addEventListener('click', ()=>{
    const sel = $('#areaSelect'); if(!sel || !sel.value) return;
    const id = sel.value, nombre = sel.options[sel.selectedIndex].text;
    if(areas.some(a=>String(a.id)===String(id))){ toast('El área ya está agregada.'); return; }
    areas.push({id, nombre}); renderAll();
  });

  /* ---- NIVELES (fijos) ---- */
  $('#addNivelBtn')?.addEventListener('click', ()=>{
    const sel = $('#nivelSelect'); if(!sel || !sel.value) return;
    const v = sel.value;
    if(niveles.includes(v)){ toast('Ese nivel ya está agregado.'); return; }
    niveles.push(v); renderAll();
  });

  /* ---- FASES ---- */
  function seedDefaultCupos(){
    const fs=$('#faseSelect'), cup=$('#faseCupos');
    if(fs && cup){
      const cupos = fs.selectedOptions[0]?.dataset.defaultCupos || '';
      if(!cup.value) cup.value = cupos;
    }
  }
  $('#faseSelect')?.addEventListener('change', seedDefaultCupos);

  $('#addFaseBtn')?.addEventListener('click', ()=>{
    const fs=$('#faseSelect'), cup=$('#faseCupos'); if(!fs || !fs.value) return;
    const id=fs.value, nombre=fs.options[fs.selectedIndex].text;
    const cupos = Number(cup.value || fs.selectedOptions[0]?.dataset.defaultCupos || 0);
    if(!cupos || cupos<1){ toast('Ingresa un número válido de clasificados.'); return; }
    if(fases.some(f=>String(f.id)===String(id))){ toast('Esa fase ya está agregada.'); return; }
    fases.push({id, nombre, cupos}); cup.value=''; renderAll();
  });

  /* ===== Validación / Envío (front) ===== */
  const checkValidity=()=>{ btnSave.disabled=!form.checkValidity(); };
  form.addEventListener('input', checkValidity);
  form.addEventListener('change', checkValidity);
  checkValidity();

  form.addEventListener('submit',(e)=>{
    e.preventDefault(); // FRONT PURO
    if(!form.checkValidity()) return;

    const id = ($('#c_id').value||'').trim();
    const nombre = ($('#c_nombre').value||'').trim();
    const fi = $('#c_inicio').value;
    const ff = $('#c_fin').value;

    // Evitar ID duplicado
    if (store.some(r=>String(r.id)===id) || document.querySelector('.grid-cell[data-id="'+id+'"]')) {
      toast('Ese ID ya existe en la tabla.');
      return;
    }

    const row = { id, nombre, fecha_inicio: fi, fecha_fin: ff };
    store.push(row);
    addRowToDOM(row);

    toast('Competición creada.');
    form.reset();
    // limpiar listas solo si quieres resetear: (comenta si prefieres mantener)
    // areas.length=0; niveles.length=0; fases.length=0; renderAll();

    initDateGuards();
    checkValidity();
    closeModal();
  });

  /* ===== Fechas (no pasado, fin >= inicio) ===== */
  function ymdToday(){ const d=new Date(); d.setHours(0,0,0,0); const m=String(d.getMonth()+1).padStart(2,'0'), day=String(d.getDate()).padStart(2,'0'); return `${d.getFullYear()}-${m}-${day}`; }
  function initDateGuards(){
    const start=$('#c_inicio'), end=$('#c_fin'); if(!start||!end) return;
    const today=ymdToday(); start.min=today; end.min=start.value || today;
    start.onchange=()=>{ if(start.value && start.value<today){ start.value=today; toast('La fecha de inicio no puede ser pasada.'); }
      end.min=start.value||today; if(end.value && end.value<end.min){ end.value=end.min; toast('La fecha fin fue ajustada.'); } checkValidity(); };
    end.onchange=()=>{ const minEnd=start.value||today; if(end.value && end.value<minEnd){ end.value=minEnd; toast('La fecha fin no puede ser anterior a la de inicio.'); } checkValidity(); };
  }
  initDateGuards();
  seedDefaultCupos();
})();
</script>
@endsection
