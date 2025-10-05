@extends('layouts.app')
@section('title', 'Inscripción · Admin')

@section('content')
  <div class="max-w-6xl mx-auto">
    <h2 class="text-2xl font-semibold text-gray-800 mb-2 text-center">Registrar Estudiante</h2>
    <p class="text-sm text-gray-500 mb-6">Datos con * son importantes</p>

    @include('components.inscripcion.upload-csv')

    @include('components.inscripcion.search-bar')

    @include('components.inscripcion.students-table')

    @include('components.inscripcion.action-buttons')
  </div>

  @include('components.inscripcion.add-modal')
                  </div>
                  <div class="input-group">
                    <label for="m_materno">Apellido Materno*:</label>
                    <input type="text" id="m_materno" name="m_materno" placeholder="Apellido materno" required>
                  </div>
                  <div class="input-group">
                    <label for="m_area">Área*:</label>
                    <input type="text" id="m_area" name="m_area" placeholder="Física, Química..." required>
                  </div>
                  <div class="input-group">
                    <label for="m_tutor">Tutor*:</label>
                    <input type="text" id="m_tutor" name="m_tutor" placeholder="Nombre del tutor">
                  </div>
                  <div class="input-group">
                    <label for="m_colegio">Colegio*:</label>
                    <input type="text" id="m_colegio" name="m_colegio" placeholder="Colegio">
                  </div>
                  <div class="input-group">
                    <label for="m_fnac">Fecha de nacimiento:</label>
                    <input type="text" id="m_fnac" name="m_fnac" placeholder="dd/mm/aaaa">
                  </div>
                  <div class="input-group">
                    <label for="m_direccion">Dirección:</label>
                    <input type="text" id="m_direccion" name="m_direccion" placeholder="Dirección">
                  </div>
                  <div class="input-group">
                    <label for="m_email">Correo*:</label>
                    <input type="email" id="m_email" name="m_email" placeholder="correo@dominio.com">
                  </div>
                </div>

                <div class="form-column">
                  <!-- SUBIR FOTO -->
                  <div class="profile-card">
                    <div id="photoDrop" class="profile-drop" tabindex="0" aria-label="Zona para subir foto de perfil">
                      <svg class="avatar-ico" viewBox="0 0 24 24" aria-hidden="true">
                        <circle cx="12" cy="8" r="3.5"></circle>
                        <path d="M4 19a8 8 0 0 1 16 0" />
                      </svg>
                      <img id="photoPreview" alt="Previsualización" hidden>
                      <p class="drop-text">
                        Arrastra una imagen aquí o
                        <button type="button" id="btnPickPhoto" class="link-btn">sube una</button>
                      </p>
                      <input id="m_foto" type="file" accept="image/*" class="hidden-file">
                    </div>
                    <div class="profile-caption">
                      <span class="caption-text">Foto de perfil</span>
                      <div class="profile-actions">
                        <button type="button" class="upload-button" id="btnPickPhoto2" aria-label="Subir foto">
                          <svg width="21" height="21" viewBox="0 0 24 24" style="stroke:#111;fill:none;stroke-width:2">
                            <path d="M12 16V4M12 4l-4 4m4-4 4 4M4 20h16" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        </button>
                        <button type="button" class="remove-button" id="btnRemovePhoto" aria-label="Quitar foto" title="Quitar foto">
                          <svg width="20" height="20" viewBox="0 0 24 24" style="stroke:#991b1b;fill:none;stroke-width:2">
                            <path d="M3 6h18M7 6v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V6M9 6V4h6v2" stroke-linecap="round" stroke-linejoin="round"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>

                  <div class="input-group">
                    <label for="m_nivel">Nivel*:</label>
                    <input type="text" id="m_nivel" name="m_nivel" placeholder="Tercero">
                  </div>
                  <div class="input-group">
                    <label for="m_categoria">Categoría:</label>
                    <input type="text" id="m_categoria" name="m_categoria" placeholder="Individual">
                  </div>
                  <div class="input-group">
                    <label for="m_tipo_col">Tipo de Colegio:</label>
                    <input type="text" id="m_tipo_col" name="m_tipo_col" placeholder="Fiscal">
                  </div>
                  <div class="input-group">
                    <label for="m_ci">Cédula de identidad*:</label>
                    <input type="text" id="m_ci" name="m_ci" placeholder="1234567">
                  </div>
                  <div class="input-group">
                    <label for="m_depmun">Departamento - Municipio*:</label>
                    <input type="text" id="m_depmun" name="m_depmun" placeholder="Cochabamba - Cercado">
                  </div>
                  <div class="input-group">
                    <label for="m_tel">Teléfono - Celular*:</label>
                    <input type="tel" id="m_tel" name="m_tel" placeholder="7xxxxxxx">
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
<style>
  /* ===== Iconos ===== */
  .ico{width:1em;height:1em;stroke:currentColor;fill:none;stroke-width:2;display:block}
  .ico-18{width:18px;height:18px}

  /* ===== Upload CSV ===== */
  .upload-inline{display:flex;align-items:center;gap:16px}
  .upload-label{font-weight:600;color:#111827}
  .hidden-file{position:absolute;left:-9999px;width:1px;height:1px;opacity:0}
  .fake-file{position:relative;width:420px;height:28px;cursor:pointer;border-bottom:2px solid #0c3e92}
  .upload-ico{position:absolute;right:-2px;top:50%;transform:translateY(-50%);color:#0c3e92}
  .file-name{position:absolute;left:0;top:50%;transform:translateY(-50%);width:95%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;font-size:14px;color:#6b7280;pointer-events:none}
  .fake-file.has-file{border-bottom-color:#091c47}
  .fake-file.has-file .file-name{color:#111827}

  /* ===== Buscador ===== */
  .search-pill{position:relative;width:min(540px,90%)}
  .search-pill input{width:100%;background:#d1d5db;border:none;border-radius:9999px;padding:10px 38px 10px 16px;font-size:14px;color:#1F2937}
  .search-pill .search-icon{position:absolute;right:10px;top:50%;transform:translateY(-50%);border:0;background:transparent;cursor:pointer;display:flex;align-items:center;color:#4b5563}

  /* ===== Tabla ===== */
  .table-shell{background:#ececec;border-radius:20px;padding:18px;margin-top:12px}
  .table-inner{display:block}
  .tbl-wrap{max-height:360px;overflow:auto;background:#fff;border:1px solid #e5e7eb;border-radius:8px}
  .insc-table{width:100%;border-collapse:collapse}
  .insc-table thead tr th{font-size:12px;letter-spacing:.03em;text-transform:uppercase;color:#4b5563;background:#f8fafc;padding:10px 12px}
  .thead-sticky th{position:sticky;top:0;z-index:1}
  .insc-table tbody td{padding:10px 12px;border-top:1px solid #f1f5f9}
  .tbl-wrap::-webkit-scrollbar{width:10px;height:10px}
  .tbl-wrap::-webkit-scrollbar-thumb{background:#c7cdd6;border-radius:8px}
  .tbl-wrap::-webkit-scrollbar-track{background:#f3f4f6;border-radius:8px}
  tr[data-selectable="1"].selected{background-color:#eef2ff}

  /* ===== Botones píldora ===== */
  .btn-pill{
    display:inline-flex;align-items:center;gap:8px;
    padding:8px 16px;border:none;border-radius:9999px;cursor:pointer;
    background:#091c47;color:#fff;font-weight:600;font-size:14px
  }
  .btn-pill:hover{background:#0c3e92}
  .btn-pill .ico{color:#fff}

  /* ====== MODAL ====== */
  .md-modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;z-index:9999;
            padding:clamp(12px,4vh,32px) clamp(12px,3vw,24px)}
  .md-modal.is-open{display:flex}
  .md-backdrop{position:absolute;inset:0;background:rgba(0,0,0,.45);} /* ← sin blur */
  .md-panel{position:relative;z-index:1;width:min(1109px,96vw);max-height:min(92vh,860px);overflow:auto;border-radius:24px;
            box-shadow:0 10px 35px rgba(0,0,0,.25);background:transparent}
  body.modal-open{overflow:hidden}

  /* ===== Prototipo ===== */
  :root{
    --primary-dark-blue:#091c47; --background-grey:#c4c4c4; --text-white:#ffffff; --text-dark:#000000;
    --text-label:#182a19; --text-input:#3a4651; --text-caption:rgba(0,0,0,.87); --border-light:rgba(255,255,255,.5); --border-dark:rgba(0,0,0,.5);
  }
  .registration-section{padding:1.25rem 1rem}
  .registration-modal{max-width:1109px;margin:0 auto;background-color:var(--background-grey);border-radius:27px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,.1)}
  .modal-header{position:sticky;top:0;z-index:5;display:flex;justify-content:space-between;align-items:center;padding:15px 20px;background:var(--primary-dark-blue);color:var(--text-white);box-shadow:0 4px 4px rgba(0,0,0,.25)}
  .modal-title-wrapper{display:flex;align-items:center;gap:8px}
  .modal-title-wrapper::before{content:'';display:block;width:1px;height:30px;background-color:var(--border-light)}
  .modal-header h1{margin:0;font-family:'Quicksand',sans-serif;font-weight:700;font-size:24px;line-height:30px}
  .close-button{background:transparent;border:none;cursor:pointer;padding:0;display:flex}

  .modal-body{padding:20px}
  .notice-section{margin-bottom:24px}
  .notice-section p{margin:0;font-family:'Quicksand',sans-serif;font-weight:700;font-size:18px;line-height:22.5px;color:var(--text-dark)}
  .divider{border:none;height:1px;background-color:var(--border-dark);margin-top:16px}

  .form-grid{display:flex;gap:46px}
  .form-column{flex:1;display:flex;flex-direction:column;gap:16px;min-width:0}

  .input-group{background:#fff;border-radius:10px;padding:8px 16px 12px;border-bottom:1px solid #ddd}
  .input-group label{display:block;color:var(--text-label);font-family:'Quicksand',sans-serif;font-weight:500;font-size:12px;line-height:16px;letter-spacing:.4px;margin-bottom:2px}
  .input-group input{width:100%;border:none;outline:none;background:transparent;color:var(--text-input);font-family:'Quicksand',sans-serif;font-weight:400;font-size:14px;line-height:20px}

  /* Foto (upload) */
  .profile-card{background:#fff;border-radius:8px;box-shadow:0 1px 5px rgba(0,0,0,.12),0 2px 2px rgba(0,0,0,.14),0 1px 1px rgba(0,0,0,.2);overflow:hidden}
  .profile-drop{padding:14px;background:#fff;border:2px dashed #cbd5e1;border-radius:8px;min-height:260px;display:grid;place-items:center;text-align:center;color:#64748b;cursor:pointer;transition:background .15s,border-color .15s}
  .profile-drop:hover{background:#f8fafc;border-color:#94a3b8}
  .avatar-ico{width:72px;height:72px;stroke:#64748b;fill:none;stroke-width:2}
  .drop-text{font-size:14px;margin-top:10px}
  .link-btn{background:none;border:0;color:#0c3e92;text-decoration:underline;cursor:pointer;padding:0;font-size:14px}
  #photoPreview{width:100%;height:auto;max-height:420px;object-fit:cover;border-radius:6px}
  .profile-drop.has-image{border-style:solid}
  .profile-drop.has-image .avatar-ico,.profile-drop.has-image .drop-text{display:none}

  .profile-caption{display:flex;justify-content:space-between;align-items:center;gap:8px;padding:12px 16px}
  .caption-text{color:var(--text-caption);font-family:'Roboto',sans-serif;font-weight:500;font-size:18px;line-height:20px}
  .upload-button{background:transparent;border:none;cursor:pointer;padding:0;display:flex}
  .remove-button{background:transparent;border:none;cursor:pointer;padding:0;margin-left:6px}

  .form-actions{display:flex;justify-content:flex-end;gap:12px;margin-top:28px}
  .submit-btn{background:var(--primary-dark-blue);color:#fff;border:none;border-radius:15px;padding:8px 30px;font-family:'Ubuntu',sans-serif;font-weight:400;font-size:18px;line-height:24px;cursor:pointer;transition:background-color .3s}
  .submit-btn:hover{background:#102a63}
  .cancel-btn{background:#e5e7eb;color:#111;border:none;border-radius:15px;padding:8px 22px;font-size:16px;cursor:pointer}
  .cancel-btn:hover{background:#d1d5db}

  @media (max-width: 992px){
    .form-grid{flex-direction:column;gap:0}
    .form-column:first-child{order:2}
    .form-column:last-child{order:1;margin-bottom:24px}
    .profile-card{max-width:280px;margin:0 auto}
    .form-actions{justify-content:center}
  }
</style>
@endpush

@push('scripts')
<script>
  // ====== Referencias tabla/lista ======
  const tbody         = document.getElementById('tbodyEstudiantes');
  const btnDelete     = document.getElementById('btnDelete');
  const btnExport     = document.getElementById('btnExport');

  const inputFile     = document.getElementById('csvUpload');
  const btnUpload     = document.getElementById('btnUpload');
  const fakeFile      = document.getElementById('fakeFile');
  const fileNameEl    = document.getElementById('fileName');

  const txtSearch     = document.getElementById('txtSearch');
  const btnSearch     = document.getElementById('btnSearch');
  const btnSearchIcon = document.getElementById('btnSearchIcon');

  // ====== Upload UI CSV ======
  fakeFile.addEventListener('click', () => inputFile.click());
  inputFile.addEventListener('change', () => {
    if (inputFile.files && inputFile.files.length) {
      const f = inputFile.files[0];
      fileNameEl.textContent = f.name;
      fakeFile.classList.add('has-file');
      btnUpload.disabled = false;
    } else {
      resetUploadUI();
    }
  });

  // ====== Crear fila ======
  function nuevaFila(data = {}) {
    const tr = document.createElement('tr');
    tr.setAttribute('data-selectable', '1');
    tr.className = 'border-b last:border-b-0';

    const tdId = document.createElement('td');
    tdId.className = 'px-3 py-2';
    if (data.id && data.id.trim() !== '') {
      tdId.textContent = data.id;
      tdId.dataset.autoindex = '0';
    } else {
      tdId.textContent = String(tbody.children.length + 1);
      tdId.dataset.autoindex = '1';
    }
    tr.appendChild(tdId);

    ['nombre','ap_paterno','ap_materno','area'].forEach(k=>{
      const td = document.createElement('td');
      td.className = 'px-3 py-2';
      td.textContent = data[k] || '';
      tr.appendChild(td);
    });

    tr.addEventListener('click', () => tr.classList.toggle('selected'));
    tbody.appendChild(tr);
    reindex();
  }

  function reindex(){
    [...tbody.querySelectorAll('tr')].forEach((tr,i)=>{
      const tdId = tr.querySelector('td');
      if (tdId && tdId.dataset.autoindex === '1') tdId.textContent = String(i+1);
    });
  }

  // Eliminar
  btnDelete.addEventListener('click', () => {
    [...tbody.querySelectorAll('tr.selected')].forEach(tr => tr.remove());
    reindex(); aplicarFiltroActual();
  });

  // Subir CSV
  btnUpload.addEventListener('click', () => {
    const file = inputFile.files?.[0];
    if (!file) { alert('Selecciona un archivo CSV.'); return; }
    const reader = new FileReader();
    reader.onload = () => {
      const text = reader.result.toString();
      cargarCSV(text);
      aplicarFiltroActual();
    };
    reader.readAsText(file, 'UTF-8');
  });

  function parseLine(line){
    const looksSemicolon = line.includes(';') && !line.includes(',');
    return looksSemicolon ? line.split(';') : line.split(',');
  }

  function cargarCSV(csvText){
    tbody.innerHTML='';
    const filas = csvText.split(/\r?\n/).filter(l=>l.trim().length>0);

    let startIndex = 0;
    if (filas.length) {
      const headerGuess = parseLine(filas[0]).join(' ').toLowerCase();
      if (/c[oó]digo|codigo|id|nombre|paterno|materno|área|area/.test(headerGuess)) startIndex = 1;
    }

    for (let i=startIndex;i<filas.length;i++){
      const cols = parseLine(filas[i]).map(c => (c || '').trim());
      const data = (cols.length >= 5)
        ? { id: cols[0], nombre: cols[1], ap_paterno: cols[2], ap_materno: cols[3], area: cols[4] }
        : { id: '',      nombre: cols[0] || '', ap_paterno: cols[1] || '', ap_materno: cols[2] || '', area: cols[3] || '' };

      if ([data.id, data.nombre, data.ap_paterno, data.ap_materno, data.area].every(v => (v||'') === '')) continue;
      nuevaFila(data);
    }
  }

  // Exportar
  btnExport.addEventListener('click', () => {
    const filas = [...tbody.querySelectorAll('tr')]
      .filter(tr => tr.style.display !== 'none')
      .map(tr => {
        const tds = tr.querySelectorAll('td');
        return [
          tds[0]?.textContent?.trim() || '',
          tds[1]?.textContent?.trim() || '',
          tds[2]?.textContent?.trim() || '',
          tds[3]?.textContent?.trim() || '',
          tds[4]?.textContent?.trim() || '',
        ].join(',');
      });

    if (filas.length === 0) { alert('No hay datos para exportar.'); return; }

    const encabezado = 'ID,NOMBRE,APELLIDO PATERNO,APELLIDO MATERNO,AREA';
    const csv = [encabezado, ...filas].join('\n');
    const blob = new Blob([csv], { type:'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a'); a.href = url; a.download = 'inscripciones.csv'; a.click();
    URL.revokeObjectURL(url);

    resetTable(); resetUploadUI(); resetSearch();
  });

  function resetTable(){ tbody.innerHTML = ''; }
  function resetUploadUI(){
    fileNameEl.textContent = 'Ningún archivo seleccionado';
    fakeFile.classList.remove('has-file');
    inputFile.value = '';
    btnUpload.disabled = true;
  }
  function resetSearch(){ txtSearch.value = ''; aplicarFiltroActual(); }

  // Filtro
  function filtrarTabla(q){
    const query = q.toLowerCase();
    [...tbody.querySelectorAll('tr')].forEach(tr=>{
      const tds = tr.querySelectorAll('td');
      const texto = [
        tds[0]?.textContent||'',
        tds[1]?.textContent||'',
        tds[2]?.textContent||'',
        tds[3]?.textContent||'',
        tds[4]?.textContent||'',
      ].join(' ').toLowerCase();
      tr.style.display = (query==='' || texto.includes(query)) ? '' : 'none';
    });
  }
  function aplicarFiltroActual(){ filtrarTabla(txtSearch.value.trim()); }
  btnSearch.addEventListener('click', aplicarFiltroActual);
  btnSearchIcon.addEventListener('click', aplicarFiltroActual);
  txtSearch.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ e.preventDefault(); aplicarFiltroActual(); }});


  // ==========================
  // MODAL abrir/cerrar
  // ==========================
  const modalAdd = document.getElementById('modalAdd');
  const mdPanel  = modalAdd.querySelector('.md-panel');
  const btnAdd   = document.getElementById('btnAdd');
  const btnClose = modalAdd.querySelector('.close-button'); // ← X del modal

  function openModal(){
    modalAdd.classList.add('is-open');
    modalAdd.removeAttribute('aria-hidden');
    document.body.classList.add('modal-open');
    mdPanel.scrollTop = 0;
    setTimeout(()=> modalAdd.querySelector('[data-autofocus]')?.focus(), 60);
  }
  function closeModal(){
    modalAdd.classList.remove('is-open');
    modalAdd.setAttribute('aria-hidden','true');
    document.body.classList.remove('modal-open');
  }

  btnAdd.addEventListener('click', openModal);
  btnClose.addEventListener('click', closeModal); // ← ahora sí cierra con la X
  modalAdd.addEventListener('click', (e)=>{
    if (e.target.hasAttribute('data-close')) closeModal();
  });
  document.addEventListener('keydown', (e)=>{
    if (e.key === 'Escape' && modalAdd.classList.contains('is-open')) closeModal();
  });

  // ==========================
  // SUBIR FOTO (preview + drag&drop)
  // ==========================
  const filePhoto      = document.getElementById('m_foto');
  const photoDrop      = document.getElementById('photoDrop');
  const photoPreview   = document.getElementById('photoPreview');
  const btnPickPhoto   = document.getElementById('btnPickPhoto');
  const btnPickPhoto2  = document.getElementById('btnPickPhoto2');
  const btnRemovePhoto = document.getElementById('btnRemovePhoto');

  let photoDataUrl = null;

  function pickPhoto(){ filePhoto.click(); }
  btnPickPhoto.addEventListener('click', pickPhoto);
  btnPickPhoto2.addEventListener('click', pickPhoto);
  photoDrop.addEventListener('click', (e)=> {
    if (e.target === photoDrop || e.target.classList.contains('avatar-ico') || e.target.classList.contains('drop-text')) {
      pickPhoto();
    }
  });

  function clearPhoto(){
    photoDataUrl = null;
    filePhoto.value = '';
    photoPreview.src = '';
    photoPreview.hidden = true;
    photoDrop.classList.remove('has-image');
  }
  btnRemovePhoto.addEventListener('click', clearPhoto);

  function handleFiles(files){
    if (!files || !files.length) return;
    const f = files[0];
    if (!f.type.startsWith('image/')) { alert('El archivo debe ser una imagen.'); return; }
    if (f.size > 3 * 1024 * 1024) { alert('La imagen no debe superar 3 MB.'); return; }
    const reader = new FileReader();
    reader.onload = () => {
      photoDataUrl = reader.result;
      photoPreview.src = photoDataUrl;
      photoPreview.hidden = false;
      photoDrop.classList.add('has-image');
    };
    reader.readAsDataURL(f);
  }

  filePhoto.addEventListener('change', ()=> handleFiles(filePhoto.files));

  ['dragenter','dragover','dragleave','drop'].forEach(ev=>{
    photoDrop.addEventListener(ev, e=>{ e.preventDefault(); e.stopPropagation(); });
  });
  ['dragenter','dragover'].forEach(()=>{
    photoDrop.classList.add('dragging');
  });
  ['dragleave','drop'].forEach(()=>{
    photoDrop.classList.remove('dragging');
  });
  photoDrop.addEventListener('drop', e=> handleFiles(e.dataTransfer.files));

  // Submit modal -> agrega fila a la tabla y cierra
  document.getElementById('frmAdd').addEventListener('submit', (e)=>{
    e.preventDefault();
    const id         = document.getElementById('m_id').value.trim();
    const nombre     = document.getElementById('m_nombre').value.trim();
    const ap_paterno = document.getElementById('m_paterno').value.trim();
    const ap_materno = document.getElementById('m_materno').value.trim();
    const area       = document.getElementById('m_area').value.trim();

    if (!nombre || !ap_paterno || !ap_materno || !area){
      alert('Completa los campos obligatorios.');
      return;
    }
    nuevaFila({ id, nombre, ap_paterno, ap_materno, area });
    closeModal();
    ['m_id','m_nombre','m_paterno','m_materno','m_area','m_tutor','m_colegio','m_fnac','m_direccion','m_email','m_nivel','m_categoria','m_tipo_col','m_ci','m_depmun','m_tel']
      .forEach(id=>{ const el=document.getElementById(id); if(el) el.value=''; });
    clearPhoto();
  });

</script>
@endpush
