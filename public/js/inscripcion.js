// JS extraído de index.blade.php
// ...todo el código JS entre @push('scripts') y @endpush...

// ====== Referencias tabla/lista ======
const tbody = document.getElementById('tbodyEstudiantes');
const btnDelete = document.getElementById('btnDelete');
const btnExport = document.getElementById('btnExport');
const inputFile = document.getElementById('csvUpload');
const btnUpload = document.getElementById('btnUpload');
const fakeFile = document.getElementById('fakeFile');
const fileNameEl = document.getElementById('fileName');
const txtSearch = document.getElementById('txtSearch');
const btnSearch = document.getElementById('btnSearch');
const btnSearchIcon = document.getElementById('btnSearchIcon');
const cmbCompeticiones = document.getElementById('cmbCompeticiones');

// Token CSRF (opcional para GET)
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

// ====== Mapa de áreas (nombre -> ID) ======
let areasMap = {};

// Cargar las áreas al iniciar
(function cargarAreas() {
  const headers = { 'Content-Type': 'application/json' };
  if (csrfToken) headers['X-CSRF-TOKEN'] = csrfToken;
  fetch('/dashboard/admin/inscripcion/get-areas', { method: 'GET', headers })
    .then(res => res.json())
    .then(data => {
      if (data.success && data.areas) {
        // Crear mapa de nombre -> ID (case insensitive)
        data.areas.forEach(area => {
          areasMap[area.name.toLowerCase().trim()] = area.id;
        });
        console.log('Áreas cargadas:', areasMap);
      }
    })
    .catch(error => { console.error('Error al cargar áreas:', error); });
})();

// Cargar las competiciones activas al iniciar
(function cargarCompeticiones() {
  if (!cmbCompeticiones) return;
  const headers = { 'Content-Type': 'application/json' };
  if (csrfToken) headers['X-CSRF-TOKEN'] = csrfToken;
  fetch('/dashboard/admin/inscripcion/get-competiciones', { method: 'GET', headers })
    .then(res => res.json())
    .then(data => {
      console.log('Respuesta competiciones:', data);
      if (data.success && Array.isArray(data.competiciones)) {
        const activas = data.competiciones.filter(c => (c.state || '').toLowerCase() === 'activa');

        // Si ya hay opciones precargadas desde el servidor (más de 1 incluyendo placeholder), evitar duplicados
        const existingValues = new Set(Array.from(cmbCompeticiones.options).map(o => String(o.value)));

        if (activas.length === 0) {
          // Solo mostrar mensaje si no hay nada precargado (aparte del placeholder)
          if (cmbCompeticiones.options.length <= 1) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.disabled = true;
            opt.textContent = 'No hay competiciones activas';
            cmbCompeticiones.appendChild(opt);
          }
          return;
        }

        activas.forEach(comp => {
          const idStr = String(comp.id);
          if (existingValues.has(idStr)) return; // evitar duplicado
          const option = document.createElement('option');
          option.value = comp.id;
          option.textContent = comp.name;
          if (comp.description) option.title = comp.description;
          cmbCompeticiones.appendChild(option);
        });
        console.log('Competiciones activas cargadas (sin duplicar):', activas.length);
      }
    })
    .catch(error => { console.error('Error al cargar competiciones:', error); });
})();

// Función para obtener el ID de área por nombre
function getAreaId(areaName) {
  if (!areaName || areaName.trim() === '') return null;
  const normalizedName = areaName.toLowerCase().trim();
  return areasMap[normalizedName] || null;
}

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

  // Orden en tabla/CSV: nombre, ap_paterno, ap_materno, ci, email, area, categoria, codigo_usuario, password
  [
    'nombre',
    'ap_paterno',
    'ap_materno',
    'ci',
    'email',
    'area',
    'categoria',
    'codigo_usuario',
    'password'
  ].forEach(k => {
    const td = document.createElement('td');
    td.className = 'px-3 py-2';
    td.textContent = data[k] || '';
    tr.appendChild(td);
  });

  tr.addEventListener('click', () => tr.classList.toggle('selected'));
  tbody.appendChild(tr);
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
    if (/nombre|paterno|materno|ci|email|contraseña|password|rol|area|codigo|categor/i.test(headerGuess)) startIndex = 1;
  }

  for (let i=startIndex;i<filas.length;i++){
    const cols = parseLine(filas[i]).map(c => (c || '').trim());
    // Orden esperado del CSV: nombre(0), ap_paterno(1), ap_materno(2), ci(3), email(4), area(5), categoria(6), codigo_usuario(7), password(8)
    const data = {
      nombre: cols[0] || '',
      ap_paterno: cols[1] || '',
      ap_materno: cols[2] || '',
      ci: cols[3] || '',
      email: cols[4] || '',
      area: cols[5] || '',
      categoria: cols[6] || '',
      codigo_usuario: cols[7] || '',
      password: cols[8] || ''
    };

    if (Object.values(data).every(v => v === '')) continue;
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
        tds[0]?.textContent?.trim() || '', // nombre
        tds[1]?.textContent?.trim() || '', // ap_paterno
        tds[2]?.textContent?.trim() || '', // ap_materno
        tds[3]?.textContent?.trim() || '', // ci
        tds[4]?.textContent?.trim() || '', // email
        tds[5]?.textContent?.trim() || '', // area
        tds[6]?.textContent?.trim() || '', // categoria
        tds[7]?.textContent?.trim() || '', // codigo_usuario
        tds[8]?.textContent?.trim() || ''  // password
      ].join(',');
    });

  // Guardar en base de datos
  const estudiantes = [...tbody.querySelectorAll('tr')]
    .filter(tr => tr.style.display !== 'none')
    .map(tr => {
      const tds = tr.querySelectorAll('td');
      const areaNombre = tds[5]?.textContent?.trim() || ''; // area ahora índice 5
      const areaId = getAreaId(areaNombre);
      const categoriaNombre = tds[6]?.textContent?.trim() || '';

      if (areaNombre && !areaId) {
        console.warn(`Área "${areaNombre}" no encontrada en el sistema`);
      }

      return {
        name: tds[0]?.textContent?.trim() || '',
        last_name_father: tds[1]?.textContent?.trim() || '',
        last_name_moothe: tds[2]?.textContent?.trim() || '',
        ci: tds[3]?.textContent?.trim() || '',
        email: tds[4]?.textContent?.trim() || '',
        password: tds[8]?.textContent?.trim() || '', // password índice 8
        role: 'Estudiante',
        area_id: (areaId ?? areaNombre),
        user_code: tds[7]?.textContent?.trim() || '', // codigo_usuario índice 7
        is_active: true,
        categoria: categoriaNombre
      };
    });

  const competitionId = cmbCompeticiones?.value || '';

  if (!competitionId) {
    alert('Seleccione una competición antes de guardar.');
    return;
  }

  if (estudiantes.length > 0) {
    fetch('/dashboard/admin/inscripcion/guardar-estudiantes', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({ estudiantes, competition_id: Number(competitionId) })
    })
    .then(res => res.json())
    .then((data) => {
      if (data.success) {
        Swal.fire({
          title: '¡Éxito!',
          text: data.message || 'Estudiantes e inscripciones guardados correctamente.',
          icon: 'success',
          confirmButtonText: 'Aceptar'
        });
      } else {
        Swal.fire({
          title: 'Error',
          text: 'Error al guardar: ' + (data.error || 'Error desconocido'),
          icon: 'error',
          confirmButtonText: 'Aceptar'
        });
        console.error('Error del servidor:', data);
      }
    })
    .catch(error => {
      console.error('Error de conexión:', error);
      Swal.fire({
        title: 'Error de Conexión',
        text: 'No se pudo conectar con el servidor. Revisa tu conexión a internet.',
        icon: 'error',
        confirmButtonText: 'Aceptar'
      });
    });
  }

  if (filas.length === 0) { alert('No hay datos para exportar.'); return; }

  const encabezado = 'NOMBRE,APELLIDO PATERNO,APELLIDO MATERNO,EMAIL,AREA,CATEGORIA,CODIGO USUARIO,CONTRASEÑA';
  const csv = [encabezado, ...filas].join('\n');
  const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a'); a.href = url; a.download = 'inscripciones.csv'; a.click();
  URL.revokeObjectURL(url);

  tbody.innerHTML = '';
  fileNameEl.textContent = 'Ningún archivo seleccionado';
  fakeFile.classList.remove('has-file');
  inputFile.value = '';
  btnUpload.disabled = true;
  txtSearch.value = '';
});

function filtrarTabla(q) {
  const query = q.toLowerCase();
  [...tbody.querySelectorAll('tr')].forEach(tr => {
    const tds = tr.querySelectorAll('td');
    const texto = Array.from(tds).map(td => td.textContent || '').join(' ').toLowerCase();
    tr.style.display = (query === '' || texto.includes(query)) ? '' : 'none';
  });
}
function aplicarFiltroActual() { filtrarTabla(txtSearch.value.trim()); }
btnSearch.addEventListener('click', aplicarFiltroActual);
btnSearchIcon.addEventListener('click', aplicarFiltroActual);
txtSearch.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); aplicarFiltroActual(); }});

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

// Deshabilitar el botón Guardar y Exportar hasta que se seleccione una competición
function actualizarEstadoBtnExport() {
  if (!cmbCompeticiones || !btnExport) return;
  if (!cmbCompeticiones.value) {
    btnExport.disabled = true;
    btnExport.classList.add('bg-gray-400', 'text-gray-200', 'cursor-not-allowed');
    btnExport.classList.remove('bg-[#091c47]', 'text-white', 'hover:bg-[#0c3e92]');
  } else {
    btnExport.disabled = false;
    btnExport.classList.remove('bg-gray-400', 'text-gray-200', 'cursor-not-allowed');
    btnExport.classList.add('bg-[#091c47]', 'text-white', 'hover:bg-[#0c3e92]');
  }
}

// Inicializar estado al cargar
actualizarEstadoBtnExport();

// Actualizar al cambiar selección
cmbCompeticiones.addEventListener('change', actualizarEstadoBtnExport);
