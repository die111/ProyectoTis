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


<!--  BUSCADOR -->
<form id="searchForm" class="search-panel" method="GET" action="{{ route('admin.competicion.index') }}">
  <div class="search-input-wrapper">
    <input
      id="searchInput"
      type="text"
      name="q"
      value="{{ request('q') }}"
      placeholder="Encuentra la competicion"
      aria-label="Buscar competición"
    />
    <!-- Ícono minimalista -->
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
    <!-- Encabezados -->
    <div class="grid-header">ID</div>
    <div class="grid-header">Nombre de Competicion</div>
    <div class="grid-header">Fecha Inicio</div>
    <div class="grid-header">Fecha Fin</div>


    {{-- 
       BACKEND: Cuando ya tengas datos, pasa $competiciones
       desde tu controlador y se renderiza el loop real.
       Si NO hay backend, verás las filas “placeholder”.
     --}}
    @isset($competiciones)
      @forelse($competiciones as $c)
        <div class="grid-cell">{{ $c->id }}</div>
        <div class="grid-cell">{{ $c->nombre }}</div>
        <div class="grid-cell">
          {{ \Carbon\Carbon::parse($c->fecha_inicio)->format('d/m/Y') }}
        </div>
        <div class="grid-cell">
          {{ \Carbon\Carbon::parse($c->fecha_fin)->format('d/m/Y') }}
        </div>
      @empty
        <div class="grid-cell" style="grid-column:1 / -1; padding:12px; text-align:center;">
          Sin registros
        </div>
      @endforelse
    @else
      {{-- PLACEHOLDER: quita este bloque cuando uses $competiciones --}}
      @for ($i = 0; $i < 5; $i++)
        <div class="grid-cell">{{ $i+1 }}</div>
        <div class="grid-cell">—</div>
        <div class="grid-cell">—</div>
        <div class="grid-cell">—</div>
      @endfor
    @endisset
  </div>

  {{-- BACKEND (opcional): si usas paginación, muestra los links aquí --}}
  {{-- @isset($competiciones) <div class="mt-3">{{ $competiciones->links() }}</div> @endisset --}}
</div>



<!--  BOTONES SECUNDARIOS   -->
<div class="actions-panel">
  <button id="btnDelete" class="action-btn delete-btn btn-pressable" type="button">Eliminar</button>
  <button id="btnExport" class="action-btn export-btn btn-pressable" type="button">Exportar</button>
</div>

<!-- Toast de feedback -->
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

      {{-- ====================================================
         BACKEND: Cuando tu ruta POST exista, esto ya envía
         directo. Mientras, el front valida todo y el botón
         muestra “Guardando...”.
      ===================================================== --}}
      @php($backendReady = Route::has('admin.competicion.store'))
      <form id="createForm"
            class="modal-form"
            method="POST"
            @if($backendReady)
              action="{{ route('admin.competicion.store') }}"
            @else
              action="#" data-mock="1"
            @endif
            novalidate>
        @csrf

        <!-- Campos principales -->
        <div class="form-grid-2">
          <div class="field span-2">
            <label for="c_nombre">Nombre <span class="req">*</span></label>
            <input id="c_nombre" name="nombre" type="text" required placeholder="Oh SanSi" value="{{ old('nombre') }}">
            @error('nombre') <small class="field-error">{{ $message }}</small> @enderror
          </div>

          <div class="field">
            <label for="c_inicio">Fecha Inicio <span class="req">*</span></label>
            <input id="c_inicio" name="fecha_inicio" type="date" required
                   min="{{ now()->toDateString() }}" value="{{ old('fecha_inicio') }}">
            @error('fecha_inicio') <small class="field-error">{{ $message }}</small> @enderror
          </div>

          <div class="field">
            <label for="c_fin">Fecha Fin <span class="req">*</span></label>
            <input id="c_fin" name="fecha_fin" type="date" required value="{{ old('fecha_fin') }}">
            @error('fecha_fin') <small class="field-error">{{ $message }}</small> @enderror
          </div>

          <div class="field span-2 field-desc">
            <label for="c_desc">Descripción</label>
            <textarea id="c_desc" name="descripcion" rows="3"
              placeholder="La Olimpiada de Ciencias y Tecnología Oh SanSi es un evento académico...">{{ old('descripcion') }}</textarea>
            @error('descripcion') <small class="field-error">{{ $message }}</small> @enderror
          </div>

          <!-- Áreas / Niveles (add con +) -->
          <div class="field with-add">
            <label for="areaInput">Áreas:</label>
            <input id="areaInput" type="text" placeholder="Matemáticas">
            <button type="button" class="pill-add" id="addAreaBtn" aria-label="Agregar área">+</button>
          </div>

          <div class="field with-add">
            <label for="nivelInput">Niveles:</label>
            <input id="nivelInput" type="text" placeholder="1ro Secundaria">
            <button type="button" class="pill-add" id="addNivelBtn" aria-label="Agregar nivel">+</button>
          </div>
        </div>

        <!-- Listados laterales -->
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

        <!-- Fases + Cupos -->
        <div class="inline-add">
          <div class="mini-field">
            <label for="faseNombre">Fases:</label>
            <input id="faseNombre" type="text" placeholder="Fase 1">
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
            <span>FASES</span>
            <span>CLASIFICADOS</span>
            <span></span>
          </div>
          <div id="fasesTable" class="table-body"></div>
        </div>

        <!-- Aquí se sincronizan arrays (áreas, niveles, fases) para enviar al backend -->
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


<!-- CSS  -->
<style>
:root{
  --primary-dark-blue:#091c47; --primary-light-gray:#f5f4f4;
  --text-white:#fff; --text-black:#000; --text-dark-gray:#3a4651;
  --text-light-gray:rgba(58,70,81,.5); --table-header-bg:rgba(58,70,81,.5);
  --table-row-alt-bg:#d7dde4; --table-bg:rgba(234,234,234,.63);
  --font-poppins:'Poppins',sans-serif; --font-ubuntu:'Ubuntu',sans-serif;
  --font-roboto:'Roboto',sans-serif; --font-quicksand:'Quicksand',sans-serif;
}
*{box-sizing:border-box}
body{margin:0;font-family:var(--font-roboto);color:var(--text-dark-gray);background:#fff}
button{cursor:pointer;border:none;font-family:inherit}

/* Header */
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:30px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:var(--font-roboto);font-weight:400;font-size:36px;color:var(--text-dark-gray);margin:0}
.create-btn{grid-column:3;justify-self:end;display:flex;align-items:center;gap:11px;background:var(--primary-dark-blue);color:#fff;padding:11px 19px;border-radius:15px;font-family:var(--font-ubuntu);font-size:16px}

/* Buscador */
.search-panel{display:flex;gap:16px;margin-bottom:30px;justify-content:center;align-items:center}
.search-input-wrapper{position:relative;flex-grow:0;width:360px;max-width:360px}
.search-input-wrapper input{width:100%;height:40px;background:#c4c4c4;border:1px solid #0b0b0b;border-radius:10px;padding:0 40px 0 12px;font-size:13px;color:var(--text-dark-gray);font-weight:600;caret-color:var(--text-dark-gray)}
.search-input-wrapper input::placeholder{color:var(--text-light-gray);font-weight:400}
.search-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:var(--text-light-gray);stroke:currentColor;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;fill:none;pointer-events:none}
.search-btn{height:40px;padding:0 14px;border-radius:30px;background:var(--primary-dark-blue);color:#fff;font-family:var(--font-roboto);font-weight:500;font-size:13px;letter-spacing:1.25px}

/* Tabla */
.table-panel{background:var(--table-bg);padding:20px;border-radius:10px}
.data-grid{display:grid;grid-template-columns:87px 337px 190px 1fr;gap:1px;background:#ccc;border:1px solid #ccc;overflow:hidden}
.grid-header,.grid-cell{padding:17px 8px;text-align:center}
.grid-header{background:var(--table-header-bg);color:#fff;font-family:var(--font-quicksand);font-weight:700;font-size:14px}
.grid-cell{background:#fff;min-height:31px}
.data-grid>.grid-cell:nth-child(4n+5),.data-grid>.grid-cell:nth-child(4n+6),.data-grid>.grid-cell:nth-child(4n+7),.data-grid>.grid-cell:nth-child(4n+8){background:var(--table-row-alt-bg)}
.data-grid>.grid-cell:nth-child(8n+1),.data-grid>.grid-cell:nth-child(8n+2),.data-grid>.grid-cell:nth-child(8n+3),.data-grid>.grid-cell:nth-child(8n+4){background:#fff}

/* Acciones */
.actions-panel{display:flex;justify-content:center;gap:200px;margin-top:40px}
.action-btn{height:39px;border-radius:10px;background:var(--primary-dark-blue);color:#fff;font-family:var(--font-ubuntu);font-size:18px}
.delete-btn,.export-btn{width:140px}

/* Botones presionables */
.btn-pressable{transition:transform .05s ease,filter .15s ease,box-shadow .15s ease;box-shadow:0 1px 0 rgba(0,0,0,.15)}
.btn-pressable:hover{filter:brightness(1.05)}
.btn-pressable:active{transform:translateY(1px) scale(.99);filter:brightness(.95);box-shadow:0 0 0 rgba(0,0,0,0)}

/* Toast */
.toast{position:fixed;z-index:50;right:16px;bottom:16px;min-width:220px;max-width:90vw;background:#111827;color:#fff;padding:10px 14px;border-radius:10px;font-size:14px;opacity:0;pointer-events:none;transform:translateY(8px);transition:opacity .2s ease,transform .2s ease}
.toast.show{opacity:1;transform:translateY(0);pointer-events:auto}

/* ===== Modal base ===== */
.modal-overlay{position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);display:none;place-items:center;opacity:0;pointer-events:none;transition:opacity .2s ease}
.modal-overlay.show{display:grid;opacity:1;pointer-events:auto}
.modal{width:min(820px,95vw);max-height:92vh;background:#fff;border-radius:16px;box-shadow:0 12px 32px rgba(0,0,0,.25);overflow:hidden;transform:translateY(8px) scale(.98);opacity:0;transition:transform .2s ease,opacity .2s ease}
.modal-overlay.show .modal{transform:translateY(0) scale(1);opacity:1}
.modal-header{display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:var(--primary-light-gray)}
.modal-header h2{margin:0;font-size:20px;color:var(--text-dark-gray)}
.modal-close{width:36px;height:36px;border-radius:8px;background:transparent;font-size:22px;line-height:1;color:#111}

/* cuerpo modal con SCROLL propio */
.modal-body{padding:12px;background:#d9d9d9;max-height:calc(92vh - 120px);overflow:auto}
.modal-body::-webkit-scrollbar{width:10px}
.modal-body::-webkit-scrollbar-track{background:#e5e7eb;border-radius:8px}
.modal-body::-webkit-scrollbar-thumb{background:#b0b8c4;border-radius:8px}
.modal-body:hover::-webkit-scrollbar-thumb{background:#8b95a5}
.modal-body{scrollbar-width:thin;scrollbar-color:#b0b8c4 #e5e7eb}

.modal-footer{display:flex;justify-content:center;gap:10px;padding:10px 12px;background:#fff}

/* Form modal */
.form-note{font-weight:700;margin-bottom:8px;color:#111;font-size:13px}
.modal-form .form-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:10px 12px}
.modal-form .span-2{grid-column:span 2}
.modal-form .field{display:flex;flex-direction:column;gap:6px;background:#fff;border-radius:8px;padding:6px 10px;border-bottom:1px solid rgba(0,0,0,.12);position:relative}
.modal-form .field label{font-size:11px;color:#111827}
.modal-form input,.modal-form textarea{background:#fff;border:none;border-radius:8px;height:34px;padding:0 6px;font-size:12px;color:var(--text-dark-gray)}
.modal-form textarea{min-height:60px;padding:8px 10px;resize:vertical}
.field-desc{z-index:2}

/* + solo en with-add */
.field.with-add .pill-add{position:absolute;right:6px;top:calc(50% + 6px);transform:translateY(-50%);width:24px;height:24px;border-radius:50%;background:#fff;border:1px solid #111;color:#111;font-weight:700}
.field.with-add{margin-top:4px}

/* Listas Áreas/Niveles */
.lists-row{display:flex;gap:12px;margin-top:12px}
.list-card{flex:1;background:#E9E9E9;border-radius:14px;padding:10px}
.list-title{font-weight:700;font-size:11px;text-align:center;padding-bottom:6px;border-bottom:1px solid rgba(0,0,0,.2)}
.list-box{list-style:none;margin:8px 0 0;padding:0 6px;max-height:100px;overflow:auto}
.list-box li{display:flex;justify-content:space-between;align-items:center;padding:5px 6px;border-bottom:1px solid rgba(0,0,0,.15);font-size:13px}
.list-remove{width:18px;height:18px;border-radius:6px;border:1px solid #aaa;background:#fff;line-height:1}

/* Fases: inputs de añadido */
.inline-add{display:flex;align-items:flex-end;gap:8px;margin:12px auto 8px;width:min(520px,100%);background:#fff;border-radius:10px;padding:6px 8px;border-bottom:1px solid rgba(0,0,0,.12)}
.mini-field{display:flex;flex-direction:column;gap:6px;flex:1}
.mini-field input{height:34px;border:1px solid #0b0b0b;border-radius:10px;background:#fff;padding:0 8px;font-size:12px}

/* Tabla fases (simétrica) */
.table-card{width:min(520px,100%);margin:8px auto;background:#fff;border:1px solid #e5e7eb;border-radius:16px;overflow:hidden;box-shadow:0 1px 0 rgba(0,0,0,.02)}
.table-head{display:grid;grid-template-columns:1fr 110px 32px;align-items:center;gap:8px;padding:10px 12px;background:#f3f4f6;color:#334155;font-weight:700;font-size:12px;letter-spacing:.2px;border-bottom:1px solid #e5e7eb}
.table-head span:nth-child(2){justify-self:end;padding-right:4px}
.table-body{max-height:120px;overflow:auto}
.table-body::-webkit-scrollbar{height:10px;width:10px}
.table-body::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:8px}
.table-body::-webkit-scrollbar-track{background:#f1f5f9;border-radius:8px}
.table-row{display:grid;grid-template-columns:1fr 110px 32px;align-items:center;gap:8px;padding:10px 12px;border-top:1px solid #eef2f7}
.table-row:nth-child(odd){background:#fbfbfb}
.table-row span:nth-child(2){justify-self:end;min-width:64px;text-align:center;padding:3px 10px;font-size:12px;font-weight:600;color:#111827;background:#eef2ff;border:1px solid #c7d2fe;border-radius:999px}
.row-remove{justify-self:end;align-self:center;width:20px;height:20px;display:grid;place-items:center;border:1px solid #cbd5e1;border-radius:6px;background:#fff;color:#475569;line-height:1;cursor:pointer}
.row-remove:hover{background:#f8fafc}

/* Botones modal */
.btn{height:36px;padding:0 14px;border-radius:10px;font-family:var(--font-ubuntu)}
.btn-primary{background:var(--primary-dark-blue);color:#fff}
.btn-secondary{background:#fff;color:#111;border:1px solid #d1d5db}

/* Responsive */
@media (max-width:1200px){.data-grid{grid-template-columns:.5fr 1.5fr 1fr 1fr}}
@media (max-width:992px){.table-panel{overflow-x:auto}.data-grid{width:800px}}
@media (max-width:768px){
  .content-header{grid-template-columns:1fr;gap:12px}
  .create-btn{justify-self:start}
  .search-panel{flex-direction:column}
  .search-input-wrapper{max-width:100%;width:100%}
  .actions-panel{flex-direction:column;gap:20px;align-items:center}
  .modal-form .form-grid-2{grid-template-columns:1fr}
  .lists-row{flex-direction:column}
}
</style>


<!-- JS  -->
<script>
(function(){
  const $ = (s, c=document)=>c.querySelector(s);
  const $$ = (s, c=document)=>Array.from(c.querySelectorAll(s));

  /* ---------- Toast helper ---------- */
  const toast = (msg) => {
    const el = $('#toast'); if(!el) return;
    el.textContent = msg; el.classList.add('show');
    setTimeout(()=>el.classList.remove('show'), 2200);
  };

  /* ---------- Buscar: valida input ---------- */
  const searchForm = $('#searchForm'), searchInput = $('#searchInput');
  searchForm.addEventListener('submit', (e)=>{
    if(!searchInput.value.trim()){ e.preventDefault(); toast('Escribe algo para buscar.'); searchInput.focus(); }
  });

  /* ---------- Exportar CSV ---------- */
  $('#btnExport').addEventListener('click', ()=>{
    const grid = $('#dataGrid');
    const cells = $$('.grid-header, .grid-cell', grid).map(n=>n.textContent.trim());
    if(!cells.length){ toast('No hay datos para exportar.'); return; }
    const cols=4, rows=[]; for(let i=0;i<cells.length;i+=cols){ rows.push(cells.slice(i,i+cols)); }
    const esc = v => `"${String(v).replace(/"/g,'""')}"`;
    const csv = rows.map(r=>r.map(esc).join(',')).join('\n');
    const blob = new Blob([csv],{type:'text/csv;charset=utf-8;'});
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); const ts = new Date().toISOString().slice(0,19).replace(/[:T]/g,'-');
    a.href = url; a.download = `competiciones-${ts}.csv`; document.body.appendChild(a); a.click(); document.body.removeChild(a); URL.revokeObjectURL(url);
    toast('Exportado a CSV.');
  });

  /* ---------- Eliminar (demo) ---------- */
  $('#btnDelete').addEventListener('click', async ()=>{
    const ok = confirm('¿Eliminar los elementos seleccionados?'); if(!ok) return;
    await new Promise(r=>setTimeout(r,400)); toast('Eliminación realizada (demo).');
  });

  /* ---------- Modal open/close ---------- */
  const modal = $('#createModal'), body=document.body;
  function openModal(){
    modal.classList.add('show'); modal.setAttribute('aria-hidden','false');
    body.style.overflow='hidden';
    modal.querySelector('.modal-close')?.focus();
    initDateGuards();
  }
  function closeModal(){
    modal.classList.remove('show'); modal.setAttribute('aria-hidden','true');
    body.style.overflow='';
  }
  $('#openCreate')?.addEventListener('click', e=>{ e.preventDefault(); openModal(); });
  modal?.addEventListener('click', e=>{
    if(e.target===modal || e.target.closest('.modal-close') || e.target.closest('[data-close]')) closeModal();
  });
  document.addEventListener('keydown', e=>{
    if(modal.classList.contains('show') && e.key==='Escape') closeModal();
  });

  /* ---------- Data del modal (Áreas, Niveles, Fases) ---------- */
  const areas=[], niveles=[], fases=[];
  const form = $('#createForm'), hidden = $('#hiddenFields'), btnSave = $('#btnSave');

  const syncHidden = ()=>{
    hidden.innerHTML='';
    areas.forEach(v=>{ const i=document.createElement('input'); i.type='hidden'; i.name='areas[]'; i.value=v; hidden.appendChild(i); });
    niveles.forEach(v=>{ const i=document.createElement('input'); i.type='hidden'; i.name='niveles[]'; i.value=v; hidden.appendChild(i); });
    fases.forEach(f=>{
      const n=document.createElement('input'); n.type='hidden'; n.name='fases_nombres[]'; n.value=f.nombre; hidden.appendChild(n);
      const c=document.createElement('input'); c.type='hidden'; c.name='fases_cupos[]'; c.value=f.cupos; hidden.appendChild(c);
    });
  };

  const renderList = (arr, ul)=>{
    ul.innerHTML='';
    arr.forEach((val, idx)=>{
      const li=document.createElement('li');
      li.innerHTML=`<span>${val}</span>`;
      const rm=document.createElement('button'); rm.type='button'; rm.className='list-remove'; rm.title='Quitar'; rm.textContent='×';
      rm.addEventListener('click',()=>{ arr.splice(idx,1); renderAll(); });
      li.appendChild(rm); ul.appendChild(li);
    });
  };

  const renderFases = ()=>{
    const tbody = $('#fasesTable'); tbody.innerHTML='';
    fases.forEach((f, idx)=>{
      const row=document.createElement('div'); row.className='table-row';
      row.innerHTML=`<span>${f.nombre}</span><span>${f.cupos}</span>`;
      const rm=document.createElement('button'); rm.type='button'; rm.className='list-remove row-remove'; rm.textContent='×'; rm.title='Quitar';
      rm.addEventListener('click',()=>{ fases.splice(idx,1); renderAll(); });
      row.appendChild(rm); tbody.appendChild(row);
    });
  };

  const renderAll = ()=>{
    renderList(areas, $('#areasList'));
    renderList(niveles, $('#nivelesList'));
    renderFases();
    syncHidden();
    checkValidity();
  };

  $('#addAreaBtn').addEventListener('click', ()=>{
    const v=$('#areaInput').value.trim(); if(!v) return;
    areas.push(v); $('#areaInput').value=''; renderAll();
  });
  $('#addNivelBtn').addEventListener('click', ()=>{
    const v=$('#nivelInput').value.trim(); if(!v) return;
    niveles.push(v); $('#nivelInput').value=''; renderAll();
  });
  $('#addFaseBtn').addEventListener('click', ()=>{
    const n=$('#faseNombre').value.trim(); const c=$('#faseCupos').value.trim();
    if(!n || !c) return;
    fases.push({nombre:n, cupos:c}); $('#faseNombre').value=''; $('#faseCupos').value=''; renderAll();
  });

  /* ---------- Validación y estado del botón guardar ---------- */
  const checkValidity = ()=>{ btnSave.disabled = !form.checkValidity(); };
  form.addEventListener('input', checkValidity);
  form.addEventListener('change', checkValidity);
  checkValidity();

  /* ---------- Envío (mock si no hay backend) ---------- */
  form.addEventListener('submit', (e)=>{
    btnSave.disabled = true; btnSave.textContent='Guardando...';

    const mock = form.dataset.mock === '1';
    if(mock){
      e.preventDefault();
      const payload = {
        nombre: $('#c_nombre')?.value.trim(),
        fecha_inicio: $('#c_inicio')?.value,
        fecha_fin: $('#c_fin')?.value,
        descripcion: $('#c_desc')?.value.trim(),
        areas: Array.from(areas),
        niveles: Array.from(niveles),
        fases: fases.map(f=>({nombre:f.nombre, cupos:Number(f.cupos)})),
      };
      console.log('MOCK -> admin.competicion.store', payload);
      toast('Simulación: datos listos para enviar (ver consola)');
      btnSave.disabled=false; btnSave.textContent='Guardar y Publicar';
      closeModal();
    }
  });

  /* ---------- Guardas de fechas (no permitir pasado / fin >= inicio) ---------- */
  function ymdToday(){
    const d=new Date(); d.setHours(0,0,0,0);
    const m=String(d.getMonth()+1).padStart(2,'0'), day=String(d.getDate()).padStart(2,'0');
    return `${d.getFullYear()}-${m}-${day}`;
  }
  function initDateGuards(){
    const start=$('#c_inicio'), end=$('#c_fin'); if(!start||!end) return;
    const today=ymdToday(); start.min=today; end.min=start.value || today;

    start.addEventListener('change',()=>{
      if(start.value && start.value < today){ start.value=today; toast('La fecha de inicio no puede ser pasada.'); }
      end.min = start.value || today;
      if(end.value && end.value < end.min){ end.value=end.min; toast('La fecha fin fue ajustada para ser posterior a la de inicio.'); }
      checkValidity();
    });
    end.addEventListener('change',()=>{
      const minEnd = start.value || today;
      if(end.value && end.value < minEnd){ end.value=minEnd; toast('La fecha fin no puede ser anterior a la de inicio.'); }
      checkValidity();
    });
  }

  // Render inicial
  renderAll();
})();
</script>
@endsection

