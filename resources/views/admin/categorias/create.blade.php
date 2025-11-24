@extends('layouts.app')
@section('title', 'Crear Categoría')
@section('content')
<div class="min-h-[80vh] bg-gray-100 px-6 py-10">
  <h1 class="text-3xl font-semibold text-center text-slate-700 mb-8">Crear Categoría</h1>
  
  <!-- Mensajes de éxito o error -->
  @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 w-full">
      {{ session('success') }}
    </div>
  @endif
  
  @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 w-full">
      {{ session('error') }}
    </div>
  @endif
  
  <!-- Errores de validación -->
  @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 w-full">
      <ul class="list-disc list-inside">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  
  <div class="bg-[#F1F1F1] rounded-2xl shadow-lg p-10 w-full">
    <form action="{{ route('admin.categorias.store') }}" method="POST" class="space-y-6">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre:</label>
        <input type="text" name="nombre" id="nombre-input" value="{{ old('nombre') }}" maxlength="30" class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-blue-900 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Nombre de Categoría" required>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción:</label>
        <textarea name="descripcion" rows="2" class="w-full rounded-md border border-gray-300 px-4 py-2 focus:border-blue-900 focus:ring-2 focus:ring-blue-100 outline-none" placeholder="Descripción de la categoría" required>{{ old('descripcion') }}</textarea>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nivel:</label>
        <div class="flex items-center gap-2">
          <select id="nivel-select" name="nivel_temp" class="flex-1 rounded-md border border-gray-300 px-4 py-2 focus:border-blue-900 focus:ring-2 focus:ring-blue-100 outline-none">
            <option value="">Seleccione un nivel</option>
            <option value="primero">Primero de Secundaria</option>
            <option value="segundo">Segundo de Secundaria</option>
            <option value="tercero">Tercero de Secundaria</option>
            <option value="cuarto">Cuarto de Secundaria</option>
            <option value="quinto">Quinto de Secundaria</option>
            <option value="sexto">Sexto de Secundaria</option>
          </select>
          <button type="button" id="agregar-nivel" class="rounded-full px-4 py-2 bg-[#091C47] text-white font-semibold hover:brightness-110 disabled:bg-gray-400 disabled:text-gray-200" disabled>Agregar</button>
        </div>
      </div>
      <input type="hidden" name="niveles" id="niveles-hidden">
      <div class="overflow-x-auto mt-6">
        <table class="w-full text-sm text-left border-separate border-spacing-0" id="tabla-niveles">
          <thead>
            <tr class="bg-gray-400 text-white">
              <th class="px-4 py-2">N</th>
              <th class="px-4 py-2">Nivel</th>
              <th class="px-4 py-2 text-center">Quitar</th>
            </tr>
          </thead>
          <tbody>
            <!-- Filas agregadas dinámicamente -->
          </tbody>
        </table>
      </div>
      <div class="flex justify-center gap-8 mt-8">
        <a href="{{ route('admin.categorias.index') }}" class="bg-red-700 hover:bg-red-800 text-white font-semibold rounded-full px-8 py-3">Cancelar</a>
        <button type="submit" id="submit-btn" class="bg-[#0B2049] hover:brightness-110 text-white font-semibold rounded-full px-8 py-3">Crear</button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const levels = [
    { id: 'primero', nombre: 'Primero de Secundario' },
    { id: 'segundo', nombre: 'Segundo de Secundario' },
    { id: 'tercero', nombre: 'Tercero de Secundario' },
    { id: 'cuarto', nombre: 'Cuarto de Secundario' },
    { id: 'quinto', nombre: 'Quinto de Secundario' },
    { id: 'sexto', nombre: 'Sexto de Secundario' }
  ];
  let nivelesSeleccionados = [];

  document.addEventListener('DOMContentLoaded', function() {
    // Validación del campo nombre
    const nombreInput = document.getElementById('nombre-input');
    if (nombreInput) {
      nombreInput.addEventListener('input', function(e) {
        // Evitar espacios en blanco seguidos
        this.value = this.value.replace(/\s{2,}/g, ' ');
        
        // Evitar más de 2 letras iguales seguidas
        this.value = this.value.replace(/(.)\1{2,}/gi, '$1$1');
      });
    }

    const agregarBtn = document.getElementById('agregar-nivel');
    const select = document.getElementById('nivel-select');
    const form = document.querySelector('form');
    
    if (agregarBtn && select) {
      agregarBtn.disabled = true;
      select.addEventListener('change', function() {
        agregarBtn.disabled = !select.value;
      });
      agregarBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const nivelId = select.value;
        if (!nivelId) {
          alert('Por favor seleccione un nivel');
          return;
        }
        if (nivelesSeleccionados.includes(nivelId)) {
          alert('Este nivel ya está agregado');
          return;
        }
        nivelesSeleccionados.push(nivelId);
        // Quitar opción del combo box
        const optionToRemove = select.querySelector(`option[value='${nivelId}']`);
        if (optionToRemove) optionToRemove.remove();
        actualizarTabla();
        actualizarInput();
        select.selectedIndex = 0;
        agregarBtn.disabled = true;
      });
    }
    
    // Validar formulario antes de enviar
    if (form) {
      form.addEventListener('submit', function(e) {
        // Validar campo nombre
        const nombreValue = nombreInput ? nombreInput.value.trim() : '';
        if (nombreValue.length === 0) {
          e.preventDefault();
          alert('El nombre de la categoría no puede estar vacío');
          return false;
        }
        
        if (nivelesSeleccionados.length === 0) {
          e.preventDefault();
          alert('Debe seleccionar al menos un nivel para la categoría');
          return false;
        }
      });
    }
  });

  function quitarNivel(idx) {
    const select = document.getElementById('nivel-select');
    const id = nivelesSeleccionados[idx];
    // Volver a agregar la opción al combo box
    const nivel = levels.find(l => l.id == id);
    if (nivel) {
      const option = document.createElement('option');
      option.value = nivel.id;
      option.text = nivel.nombre;
      select.appendChild(option);
      // Ordenar opciones por el orden definido en levels
      const options = Array.from(select.options).slice(1).sort((a, b) => {
        return levels.findIndex(l => l.id === a.value) - levels.findIndex(l => l.id === b.value);
      });
      options.forEach(opt => select.appendChild(opt));
    }
    nivelesSeleccionados.splice(idx, 1);
    actualizarTabla();
    actualizarInput();
  }

  function actualizarTabla() {
    const tbody = document.querySelector('#tabla-niveles tbody');
    tbody.innerHTML = '';
    nivelesSeleccionados.forEach((id, idx) => {
      const nivel = levels.find(l => l.id == id);
      const nombre = nivel ? nivel.nombre : 'Nivel no encontrado';
      const row = document.createElement('tr');
      row.className = idx % 2 === 0 ? 'bg-white' : 'bg-gray-100';
      row.innerHTML = `
        <td class='px-4 py-2'>${idx+1}</td>
        <td class='px-4 py-2'>${nombre}</td>
        <td class='px-4 py-2 text-center'>
          <button type='button' onclick='quitarNivel(${idx})' class='text-red-600 hover:text-red-900 font-bold text-lg p-1 rounded' title='Quitar'>
            <i class="fas fa-trash"></i>
          </button>
        </td>
      `;
      tbody.appendChild(row);
    });
  }

  function actualizarInput() {
    const hiddenInput = document.getElementById('niveles-hidden');
    if (hiddenInput) {
      hiddenInput.value = nivelesSeleccionados.join(',');
    }
  }
</script>
@endpush
