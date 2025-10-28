@extends('layouts.app')
@section('title', 'Calificar Estudiantes · Admin')

@section('content')
<div class="mx-auto max-w-7xl px-5 py-8">
  <!-- Título -->
  <header class="mb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight">Calificar Estudiantes</h1>
        <p class="text-sm text-gray-600 mt-2">{{ $fase->name }} - {{ $competicion->name }}</p>
      </div>
      <a href="{{ route('admin.evaluacion.fase.estudiantes', [$competicion->id, $fase->id]) }}" class="rounded-full bg-gray-500 px-4 py-2 text-white text-sm shadow hover:bg-gray-600">
        ← Volver a Estudiantes
      </a>
    </div>
  </header>

  <!-- Información de la competición y fase -->
  <section class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
      <div>
        <span class="font-semibold text-green-800">Competición:</span>
        <span class="text-green-700">{{ $competicion->name }}</span>
      </div>
      <div>
        <span class="font-semibold text-green-800">Fase:</span>
        <span class="text-green-700">{{ $fase->name }}</span>
      </div>
      <div>
        <span class="font-semibold text-green-800">Estado:</span>
        <span class="text-green-700">{{ ucfirst($competicion->state) }}</span>
      </div>
      <div>
        <span class="font-semibold text-green-800">Total Estudiantes:</span>
        <span class="text-green-700">{{ $estudiantes->count() }}</span>
      </div>
    </div>
  </section>

  <!-- Buscador -->
  <section class="mb-6">
    <form method="GET" action="{{ route('admin.evaluacion.calificar', [$competicion->id, $fase->id]) }}" class="flex items-center gap-3 justify-center">
      <div class="relative flex-1 max-w-md w-full flex justify-center">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-gray-400">
            <path fill-rule="evenodd" d="M10 2a8 8 0 105.293 14.293l3.707 3.707a1 1 0 001.414-1.414l-3.707-3.707A8 8 0 0010 2zm-6 8a6 6 0 1110.392 3.906.997.997 0 00-.116.116A6 6 0 014 10z" clip-rule="evenodd" />
          </svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, apellido, unidad educativa o CI..." class="w-full rounded-md border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm placeholder:text-gray-400 shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500" />
      </div>
      <button type="submit" class="rounded-md px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" style="background-color: #091C47;">
        Buscar
      </button>
    </form>
  </section>

  <!-- Mensajes de alerta -->
  @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">¡Éxito!</strong>
      <span class="block sm:inline">{{ session('success') }}</span>
    </div>
  @endif

  @if(session('error'))
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">¡Error!</strong>
      <span class="block sm:inline">{{ session('error') }}</span>
    </div>
  @endif

  @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
      <strong class="font-bold">¡Errores de validación!</strong>
      <ul class="mt-2 list-disc list-inside">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <!-- Tabla de Calificación -->
  <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <div>
        <h3 class="text-lg font-medium text-gray-900">Calificación de Estudiantes</h3>
        <p class="mt-1 text-sm text-gray-500">
          Mostrando {{ $estudiantes->count() }} estudiantes
        </p>
      </div>
      <div class="flex space-x-3">
        <button type="button" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
          Exportar Resultados
        </button>
      </div>
    </div>

    @if($estudiantes->count() > 0)
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Estudiante
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Área
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Categoría
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                CI
              </th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Puntaje (0-100)
              </th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Observaciones
              </th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                Estado Calificación
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($estudiantes as $estudiante)
              <tr class="hover:bg-gray-50">
                @php
                  $evaluacion = $estudiante->evaluations->first();
                @endphp
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="text-sm font-medium text-green-700">
                          {{ substr($estudiante->user->name ?? 'N', 0, 1) }}{{ substr($estudiante->user->last_name_father ?? 'A', 0, 1) }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ $estudiante->user->name ?? 'N/A' }} {{ $estudiante->user->last_name_father ?? '' }} {{ $estudiante->user->last_name_mother ?? '' }}
                      </div>
                      <div class="text-sm text-gray-500">
                        ID: {{ $estudiante->id }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $estudiante->area->name ?? 'No asignada' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $estudiante->categoria->nombre ?? 'No asignada' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $estudiante->user->ci ?? 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  @php
                    $puntajeExistente = $evaluacion ? $evaluacion->nota : '';
                  @endphp
                  <input type="number"
                         form="calif-{{ $estudiante->id }}"
                         name="calificaciones[{{ $estudiante->id }}][puntaje]"
                         value="{{ $puntajeExistente }}"
                         min="0"
                         max="100"
                         step="0.1"
                         placeholder="0.0"
                         class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm text-center focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  @php
                    $observacionesExistentes = $evaluacion ? $evaluacion->observaciones_evaluador : '';
                  @endphp
                  <textarea form="calif-{{ $estudiante->id }}"
                            name="calificaciones[{{ $estudiante->id }}][observaciones]"
                            rows="1"
                            placeholder="Observaciones..."
                            class="w-32 rounded-md border border-gray-300 px-2 py-1 text-sm resize-none focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">{{ $observacionesExistentes }}</textarea>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  @php
                    if ($evaluacion) {
                      switch($evaluacion->estado) {
                        case 'clasificado':
                          $estadoCalifClass = 'bg-green-100 text-green-800';
                          $estadoCalifTexto = 'Clasificado';
                          break;
                        case 'no_clasificado':
                          $estadoCalifClass = 'bg-blue-100 text-blue-800';
                          $estadoCalifTexto = 'No Clasificado';
                          break;
                        case 'desclasificado':
                          $estadoCalifClass = 'bg-red-100 text-red-800';
                          $estadoCalifTexto = 'Desclasificado';
                          break;
                        default:
                          $estadoCalifClass = 'bg-yellow-100 text-yellow-800';
                          $estadoCalifTexto = 'Sin Calificar';
                          break;
                      }
                    } else {
                      $estadoCalifClass = 'bg-yellow-100 text-yellow-800';
                      $estadoCalifTexto = 'Sin Calificar';
                    }
                  @endphp
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $estadoCalifClass }}">
                    {{ $estadoCalifTexto }}
                  </span>
                  <!-- Formulario por fila -->
                  <form id="calif-{{ $estudiante->id }}" method="POST" action="{{ route('admin.evaluacion.guardar-calificaciones', [$competicion->id, $fase->id]) }}" class="mt-2 inline-block">
                    @csrf
                    <button type="submit" class="rounded-md px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:opacity-90" style="background-color: #091C47;">
                      Calificar
                    </button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes para calificar</h3>
        <p class="mt-1 text-sm text-gray-500">No se encontraron estudiantes con los filtros aplicados.</p>
      </div>
    @endif
  </section>

  <!-- Sección de acciones adicionales -->
  <section class="mt-8 bg-gray-50 rounded-lg p-6">
    <h4 class="text-lg font-medium text-gray-900 mb-4">Acciones Adicionales</h4>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white rounded-lg p-4 border border-gray-200">
        <h5 class="font-medium text-gray-900 mb-2">Clasificar estudiantes</h5>
        <p class="text-sm text-gray-600 mb-3">Permite clasificar estudiantes manualmente</p>
        <select id="clasificacion-select" class="w-full mb-3 rounded-md border border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
          <option value="" selected disabled>Selecciona una opción</option>
          <option value="cupo">Clasificar por cupo</option>
          <option value="notas_altas">Clasificar notas altas</option>
        </select>
        
        <!-- Campo para cupo (texto) -->
        <div id="campo-cupo" class="mb-3 hidden">
          <label for="cupo-input" class="block text-sm font-medium text-gray-700 mb-1">Cupo disponible</label>
          <input type="text" id="cupo-input" placeholder="Ej: 50 estudiantes" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
        </div>
        
        <!-- Campo para notas altas (número) -->
        <div id="campo-notas" class="mb-3 hidden">
          <label for="nota-minima-input" class="block text-sm font-medium text-gray-700 mb-1">Nota mínima</label>
          <input type="number" id="nota-minima-input" placeholder="70" min="0" max="100" step="0.1" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
        </div>
        
        <button type="button" class="w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
          Clasificar
        </button>
      </div>
      
      <div class="bg-white rounded-lg p-4 border border-gray-200">
        <h5 class="font-medium text-gray-900 mb-2">Generar Reporte</h5>
        <p class="text-sm text-gray-600 mb-3">Exportar reporte de calificaciones</p>
        <button type="button" class="w-full rounded-md bg-blue-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
          Generar PDF
        </button>
      </div>
      
      <div class="bg-white rounded-lg p-4 border border-gray-200">
        <h5 class="font-medium text-gray-900 mb-2">Finalizar Fase</h5>
        <p class="text-sm text-gray-600 mb-3">Cerrar la fase y procesar resultados</p>
        <button type="button" class="w-full rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700">
          Finalizar Fase
        </button>
      </div>
    </div>
  </section>
</div>

<!-- Scripts -->
<script>
  // Validación de puntajes
  document.querySelectorAll('input[type="number"]').forEach(function(input) {
    input.addEventListener('input', function() {
      const value = parseFloat(this.value);
      if (value < 0) this.value = 0;
      if (value > 100) this.value = 100;
    });
  });

  // Mostrar/ocultar campos según selección de clasificación
  document.getElementById('clasificacion-select').addEventListener('change', function() {
    const campoCupo = document.getElementById('campo-cupo');
    const campoNotas = document.getElementById('campo-notas');
    
    // Ocultar ambos campos primero
    campoCupo.classList.add('hidden');
    campoNotas.classList.add('hidden');
    
    // Mostrar el campo correspondiente
    if (this.value === 'cupo') {
      campoCupo.classList.remove('hidden');
    } else if (this.value === 'notas_altas') {
      campoNotas.classList.remove('hidden');
    }
  });
</script>
@endsection
