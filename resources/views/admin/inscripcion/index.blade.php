@extends('layouts.app')
@section('title', 'Inscripción · Admin')

@section('content')
  <div class="w-full px-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-2 text-center">Registrar Estudiante</h2>
    <p class="text-sm text-gray-500 mb-6">Datos con * son importantes</p>

    <!-- Upload CSV (muestra nombre del archivo) -->
    <div class="upload-inline mb-6">
      <label for="csvUpload" class="upload-label">Subir CSV*:</label>

      <div id="fakeFile" class="fake-file" title="Seleccionar archivo CSV">
        <span id="fileName" class="file-name">Ningún archivo seleccionado</span>
        <!-- icono upload -->
        <svg class="ico ico-18 upload-ico" viewBox="0 0 24 24" aria-hidden="true">
          <path d="M12 16V4M12 4l-4 4m4-4 4 4M4 20h16" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>

      <input id="csvUpload" type="file" accept=".csv" class="hidden-file"/>

      <button id="btnUpload"
              class="px-4 py-2 rounded-md bg-[#091c47] text-white text-sm font-medium hover:bg-[#0c3e92]"
              disabled>
        Subir
      </button>
    </div>

    <!-- BUSCADOR -->
    <div class="flex items-center justify-center gap-3 mb-4">
      <div class="search-pill">
        <input id="txtSearch" type="text" placeholder="Estudiante" aria-label="Buscar estudiante">
        <button id="btnSearchIcon" class="search-icon" type="button" aria-label="Buscar">
          <svg class="ico ico-18" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"
                  stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
      </div>
      <button id="btnSearch" class="px-4 py-2 rounded-full bg-[#091c47] text-white text-sm font-medium hover:bg-[#0c3e92]">Buscar</button>
    </div>

    <!-- Combo box de competiciones -->
    <div class="mb-2 text-center">
      <span class="text-gray-800 text-base font-medium">Seleccione la competicon para la inscripcion</span>
    </div>
    <div class="flex flex-col items-center mb-4 gap-2">
      <div class="flex items-start gap-4">
        <select id="cmbCompeticiones" class="px-4 py-2 rounded-md border border-gray-300 bg-white text-sm" style="min-width:280px">
          <option value="" selected disabled>Selecciona una competición</option>
          @isset($competiciones)
            @foreach($competiciones as $c)
              <option value="{{ $c->id }}">{{ $c->name }}</option>
            @endforeach
          @endisset
        </select>
        <div id="competitionMeta" class="text-left min-w-[260px] p-3 rounded-md border border-gray-200 bg-gray-50 text-xs leading-5">
          <p class="font-semibold text-gray-700 mb-1">Categorías y Áreas</p>
          <p class="text-gray-500" data-empty>Seleccione una competición para ver sus categorías y áreas.</p>
          <!-- Contenedor horizontal -->
          <div id="catAreaGrid" class="flex flex-nowrap gap-3 overflow-x-auto pb-1 custom-scroll"></div>
        </div>
      </div>
    </div>

    <!-- TABLA (solo lectura) -->
    <div class="table-shell">
      <div class="table-inner">
        <div id="tblWrap" class="tbl-wrap">
          <table class="min-w-full bg-white insc-table">
            <thead class="thead-sticky">
              <tr>
                <th class="px-3 py-3 text-left">Nombre</th>
                <th class="px-3 py-3 text-left">Apellido Paterno</th>
                <th class="px-3 py-3 text-left">Apellido Materno</th>
                <th class="px-3 py-3 text-left">C.I</th>
                <th class="px-3 py-3 text-left">Email</th>
                <th class="px-3 py-3 text-left">Área</th>
                <th class="px-3 py-3 text-left">Categoría</th>
                <th class="px-3 py-3 text-left">Nombre grupo</th>
                <th class="px-3 py-3 text-left">Código de usuario</th>
                <th class="px-3 py-3 text-left">Contraseña</th>
              </tr>
            </thead>
            <tbody id="tbodyEstudiantes" class="text-sm text-gray-700"></tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Acciones -->
    <div class="flex items-center gap-3 justify-center mt-6">
      <button id="btnAdd" class="btn-pill" title="Añadir estudiante">
        <svg class="ico ico-18" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>Añadir</span>
      </button>

      <button id="btnDelete" class="btn-pill">
        <svg class="ico ico-18" viewBox="0 0 24 24"><path d="M9 6h6M10 6v12m4-12v12M5 6h14l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6z" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>Eliminar</span>
      </button>

      <button id="btnExport" class="btn-pill bg-gray-400 text-gray-200 cursor-not-allowed" disabled>
        <svg class="ico ico-18" viewBox="0 0 24 24"><path d="M12 3v12m0 0l-4-4m4 4 4-4M4 21h16" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <span>Guardar y Exportar</span>
      </button>
    </div>
  </div>

  {{-- MODAL AÑADIR (centrado, header sticky, scroll interno) --}}
  <div id="modalAdd" class="md-modal" aria-hidden="true">
    <div class="md-backdrop" data-close></div>

    <div class="md-panel">
      <section id="registration-form" class="registration-section">
        <div class="registration-modal">
          <header class="modal-header">
            <div class="modal-title-wrapper">
              <h1>Registrar Estudiante</h1>
            </div>
            <button class="close-button" data-close aria-label="Cerrar modal">
              <svg width="24" height="24" viewBox="0 0 24 24" class="text-white" style="stroke:#fff;fill:none;stroke-width:2">
                <path d="M6 6l12 12M18 6l-12 12" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </button>
          </header>

          <main class="modal-body">
            <div class="notice-section">
              <p>Los campos con * son obligatorios</p>
              <hr class="divider">
            </div>

            <form id="frmAdd" class="registration-form" onsubmit="return false;">
              <div class="form-grid">
                <div class="form-column">
                  <div class="input-group">
                    <label for="m_nombre">Nombre*:</label>
                    <input type="text" id="m_nombre" name="m_nombre" placeholder="Nombre" required data-autofocus>
                  </div>
                  <div class="input-group">
                    <label for="m_paterno">Apellido Paterno*:</label>
                    <input type="text" id="m_paterno" name="m_paterno" placeholder="Apellido paterno" required>
                  </div>
                  <div class="input-group">
                    <label for="m_materno">Apellido Materno*:</label>
                    <input type="text" id="m_materno" name="m_materno" placeholder="Apellido materno" required>
                  </div>
                  <div class="input-group">
                    <label for="m_ci">Cédula de identidad (C.I)*:</label>
                    <input type="text" id="m_ci" name="m_ci" placeholder="1234567" required>
                  </div>
                  <div class="input-group">
                    <label for="m_email">Email*:</label>
                    <input type="email" id="m_email" name="m_email" placeholder="correo@dominio.com" required>
                  </div>
                </div>

                <div class="form-column">
                  <div class="input-group">
                    <label for="m_area">Área*:</label>
                    <select id="m_area" name="m_area" required>
                      <option value="" selected disabled>Cargando áreas...</option>
                    </select>
                  </div>
                  <div class="input-group">
                    <label for="m_categoria">Categoría*:</label>
                    <select id="m_categoria" name="m_categoria" required>
                      <option value="" selected disabled>Cargando categorías...</option>
                    </select>
                  </div>
                  <div class="input-group">
                    <label for="m_nombre_grupo">Nombre grupo:</label>
                    <input type="text" id="m_nombre_grupo" name="m_nombre_grupo" placeholder="Ej: Grupo Alfa">
                  </div>
                  <div class="input-group">
                    <label for="m_codigo">Código de usuario*:</label>
                    <input type="text" id="m_codigo" name="m_codigo" placeholder="USR001" required>
                  </div>
                  <div class="input-group">
                    <label for="m_password">Contraseña*:</label>
                    <input type="password" id="m_password" name="m_password" placeholder="********" required>
                  </div>
                </div>
              </div>

              <div class="form-actions">
                <button type="button" class="cancel-btn" data-close>Cancelar</button>
                <button type="submit" class="submit-btn">Registrar</button>
              </div>
            </form>
          </main>
        </div>
      </section>
    </div>
  </div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/inscripcion.css') }}">
<style>
  /* Scrollbar fino opcional */
  #catAreaGrid::-webkit-scrollbar { height:6px; }
  #catAreaGrid::-webkit-scrollbar-track { background:#f1f1f1; border-radius:4px; }
  #catAreaGrid::-webkit-scrollbar-thumb { background:#c5c5c5; border-radius:4px; }
  #catAreaGrid::-webkit-scrollbar-thumb:hover { background:#a3a3a3; }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
<script>
(function(){
  const cmb = document.getElementById('cmbCompeticiones');
  const emptyMsg = document.querySelector('#competitionMeta [data-empty]');
  const grid = document.getElementById('catAreaGrid');
  const selCat = document.getElementById('m_categoria');
  const selArea = document.getElementById('m_area');
  let categoriasCompData = []; // categorías por competición

  function loadAreas(){
    if(!selArea) return;
    selArea.innerHTML = '<option value="" selected disabled>Cargando áreas...</option>';
    fetch('/dashboard/admin/inscripcion/get-areas')
      .then(r=>r.json())
      .then(data=>{
        selArea.innerHTML = '<option value="" disabled selected>Selecciona área</option>';
        if(!data.success) return;
        (data.areas||[]).forEach(a=>{
          const opt = document.createElement('option');
          opt.value = a.id;
          opt.textContent = a.name;
          selArea.appendChild(opt);
        });
      })
      .catch(()=>{
        selArea.innerHTML = '<option value="" disabled selected>Error cargando áreas</option>';
      });
  }

  function loadCategoriasGlobal(){
    if(!selCat) return;
    selCat.innerHTML = '<option value="" selected disabled>Cargando categorías...</option>';
    fetch('/dashboard/admin/inscripcion/get-categorias')
      .then(r=>r.json())
      .then(data=>{
        selCat.innerHTML = '<option value="" disabled selected>Selecciona categoría</option>';
        if(!data.success) return;
        (data.categorias||[]).forEach(c=>{
          const opt = document.createElement('option');
            opt.value = c.id;
            opt.textContent = c.nombre;
            selCat.appendChild(opt);
        });
      })
      .catch(()=>{
        selCat.innerHTML = '<option value="" disabled selected>Error cargando categorías</option>';
      });
  }

  cmb?.addEventListener('change', ()=>{
    const id = cmb.value;
    grid.innerHTML='';
    emptyMsg.classList.remove('hidden');
    categoriasCompData = [];
    if(!id) return;
    fetch(`/dashboard/admin/inscripcion/competition/${id}/areas-categorias`)
      .then(r=>r.json())
      .then(data=>{
        if(!data.success) return;
        categoriasCompData = data.categorias || [];
        if(!categoriasCompData.length) return;
        emptyMsg.classList.add('hidden');
        categoriasCompData.forEach(cat=>{
          const card = document.createElement('div');
          card.className='min-w-[140px] flex-shrink-0 border rounded-md bg-white shadow-sm p-2';
          const title = document.createElement('h4');
          title.className='font-semibold text-gray-700 mb-1 text-center';
          title.textContent = cat.nombre;
          card.appendChild(title);
          if(cat.areas && cat.areas.length){
            const ul = document.createElement('ul');
            ul.className='list-disc list-inside space-y-0.5';
            cat.areas.forEach(a=>{
              const li = document.createElement('li');
              li.textContent = a.name;
              ul.appendChild(li);
            });
            card.appendChild(ul);
          } else {
            const p = document.createElement('p');
            p.className='text-gray-400 text-center';
            p.textContent='(Sin áreas)';
            card.appendChild(p);
          }
          grid.appendChild(card);
        });
      })
      .catch(err=>console.error('Error cargando categorías/áreas', err));
  });

  // Inicial: cargar listas globales
  loadAreas();
  loadCategoriasGlobal();
})();
</script>
<script src="{{ asset('js/inscripcion.js') }}"></script>
@endpush
