@extends('layouts.app')
@section('title', 'Calificar Estudiantes · Admin')

@section('content')
<div class="mx-auto max-w-full px-8 py-8">
  <!-- Título -->
  <header class="mb-6">
    <div class="flex flex-col items-center justify-center">
      <div class="text-center">
        <h1 class="text-3xl font-semibold tracking-tight">Calificar Estudiantes</h1>
        <p class="text-sm text-gray-600 mt-2">{{ $fase->name }} - {{ $competicion->name }}</p>
      </div>
      <a href="{{ route('admin.evaluacion.fase.estudiantes', ['competicion' => $competicion->id, 'fase' => $fase->id, 'fase_n' => $numeroFase]) }}" class="rounded-full bg-gray-500 px-4 py-2 text-white text-sm shadow hover:bg-gray-600 mt-4">
        ← Volver a Estudiantes
      </a>
    </div>
  </header>

  <!-- Información de la competición y fase -->
  <section class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-4 border border-green-200">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm">
      <div>
        <span class="font-semibold text-green-800">Competición:</span>
        <span class="text-green-700">{{ $competicion->name }}</span>
      </div>
      <div>
        <span class="font-semibold text-green-800">Fase:</span>
        <span class="text-green-700">{{ $fase->name }} (Nivel {{ $numeroFase }})</span>
      </div>
      <div>
        <span class="font-semibold text-green-800">Estado:</span>
        <span class="text-green-700">{{ ucfirst($competicion->state) }}</span>
      </div>
      <div>
        <span class="font-semibold text-green-800">Total Estudiantes:</span>
        <span class="text-green-700">{{ $estudiantes->count() }}</span>
      </div>
      <div>
        <span class="font-semibold text-green-800">Nivel de Fase:</span>
        <span class="text-green-700">{{ $numeroFase }}</span>
      </div>
    </div>
  </section>

  <!-- Buscador -->
  <section class="mb-6">
    <form method="GET" action="{{ route('admin.evaluacion.calificar', ['competicion' => $competicion->id, 'fase' => $fase->id]) }}" class="flex items-center gap-3 justify-center">
      <input type="hidden" name="fase_n" value="{{ $numeroFase }}">
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

  <!-- Mensajes de alerta (ocultos, se mostrarán como notificaciones flotantes) -->
  @if(session('success'))
    <div id="session-success" data-message="{{ e(session('success')) }}" style="display: none;"></div>
  @endif

  @if(session('error'))
    <div id="session-error" data-message="{{ e(session('error')) }}" style="display: none;"></div>
  @endif

  @if($errors->any())
    <div id="session-errors" data-message="{{ e(implode(' | ', $errors->all())) }}" style="display: none;"></div>
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
                <a href="{{ route('admin.evaluacion.calificar', array_merge(['competicion' => $competicion->id, 'fase' => $fase->id], request()->except(['fase']), ['sort_by' => 'nombre', 'sort_order' => (request('sort_by') === 'nombre' && request('sort_order') === 'asc') ? 'desc' : 'asc', 'fase_n' => $numeroFase])) }}" class="flex items-center hover:text-gray-700">
                  Estudiante
                  @if(request('sort_by') === 'nombre')
                    @if(request('sort_order') === 'asc')
                      <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/>
                      </svg>
                    @else
                      <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"/>
                      </svg>
                    @endif
                  @else
                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                    </svg>
                  @endif
                </a>
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
                <a href="{{ route('admin.evaluacion.calificar', array_merge(['competicion' => $competicion->id, 'fase' => $fase->id], request()->except(['fase']), ['sort_by' => 'nota', 'sort_order' => (request('sort_by') === 'nota' && request('sort_order') === 'desc') ? 'asc' : 'desc', 'fase_n' => $numeroFase])) }}" class="flex items-center justify-center hover:text-gray-700">
                  Puntaje (0-100)
                  @if(request('sort_by') === 'nota')
                    @if(request('sort_order') === 'desc')
                      <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z"/>
                      </svg>
                    @else
                      <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z"/>
                      </svg>
                    @endif
                  @else
                    <svg class="ml-1 h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M5 12a1 1 0 102 0V6.414l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L5 6.414V12zM15 8a1 1 0 10-2 0v5.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L15 13.586V8z"/>
                    </svg>
                  @endif
                </a>
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
              @php
                $evaluacion = $estudiante->evaluations->first();
                $estaCalificado = $evaluacion && $evaluacion->nota !== null;
              @endphp
              
              <tr class="hover:bg-gray-50 {{ $estaCalificado ? 'bg-gray-50/50' : '' }}">
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
                    // Determinar si está calificado basándose en si tiene una nota
                    if ($estaCalificado) {
                      $estadoCalifClass = 'bg-green-100 text-green-800';
                      $estadoCalifTexto = 'Calificado';
                    } else {
                      $estadoCalifClass = 'bg-red-100 text-red-800';
                      $estadoCalifTexto = 'No calificado';
                    }
                  @endphp
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $estadoCalifClass }}">
                    {{ $estadoCalifTexto }}
                  </span>
                  <!-- Formulario por fila - solo mostrar si no está calificado -->
                  @if(!$estaCalificado)
                    <form id="calif-{{ $estudiante->id }}" method="POST" action="{{ route('admin.evaluacion.guardar-calificaciones', ['competicion' => $competicion->id, 'fase' => $fase->id]) }}" class="mt-2 inline-block">
                      @csrf
                      <button type="submit" class="rounded-md px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:opacity-90" style="background-color: #091C47;">
                        Calificar
                      </button>
                    </form>
                  @endif
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
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="bg-white rounded-lg p-4 border border-gray-200">
        <h5 class="font-medium text-gray-900 mb-2">Finalizar Fase</h5>
        <p class="text-sm text-gray-600 mb-1">Cerrar la fase y procesar resultados</p>
        @php
          $notaMinima = $fase->pivot->classification_nota_minima ?? null;
          $cupo = $fase->pivot->classification_cupo ?? null;
        @endphp
        @if($notaMinima !== null)
          <p class="text-xs text-emerald-700 mb-3">Criterio: Nota mínima >= {{ rtrim(rtrim(number_format($notaMinima, 2, '.', ''), '0'), '.') }}</p>
        @elseif($cupo !== null)
          <p class="text-xs text-emerald-700 mb-3">Criterio: Top {{ $cupo }} mejores con nota >= 51 (incluye empates)</p>
        @else
          <p class="text-xs text-amber-700 mb-3">Criterio: No configurado en esta fase</p>
        @endif
        <form id="finalizarFaseForm" method="POST" action="{{ route('admin.evaluacion.finalizar-fase', ['competicion' => $competicion->id, 'fase' => $fase->id]) }}?fase_n={{ $numeroFase }}">
          @csrf
          <input type="hidden" name="fase_n" value="{{ $numeroFase }}">
          @foreach($estudiantes as $estudiante)
            <input type="hidden" name="estudiantes_listados[]" value="{{ $estudiante->id }}">
          @endforeach
          <button type="button" onclick="mostrarModalConfirmacion()" class="w-full rounded-md bg-red-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700">
            Finalizar Fase
          </button>
        </form>
      </div>
    </div>
  </section>
</div>

<!-- Modal de Confirmación -->
<div id="modalConfirmacion" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
  <div class="relative mx-auto p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white transform transition-all">
    <!-- Icono de advertencia -->
    <div class="flex items-center justify-center mb-4">
      <div class="rounded-full bg-red-100 p-3">
        <svg class="h-12 w-12 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
    </div>
    
    <!-- Título -->
    <h3 class="text-xl font-bold text-gray-900 text-center mb-2">¿Finalizar Fase?</h3>
    
    <!-- Mensaje -->
    <p class="text-sm text-gray-600 text-center mb-6">
      Esta acción clasificará a la siguiente fase <span class="font-semibold text-blue-600">solo los {{ $estudiantes->count() }} estudiantes listados actualmente</span> que cumplan el criterio configurado. 
      <span class="font-semibold text-red-600">Esta acción no se puede deshacer.</span>
    </p>
    
    <!-- Información del criterio -->
    @php
      $notaMinima = $fase->pivot->classification_nota_minima ?? null;
      $cupo = $fase->pivot->classification_cupo ?? null;
    @endphp
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6">
      <p class="text-sm text-blue-800 text-center">
        <span class="font-semibold">Criterio de clasificación:</span><br>
        @if($notaMinima !== null)
          Nota mínima >= {{ rtrim(rtrim(number_format($notaMinima, 2, '.', ''), '0'), '.') }}
        @elseif($cupo !== null)
          Top {{ $cupo }} mejores con nota >= 51 (incluye empates)
        @else
          No configurado
        @endif
      </p>
    </div>
    
    <!-- Botones -->
    <div class="flex gap-3">
      <button type="button" onclick="cerrarModalConfirmacion()" class="flex-1 rounded-lg bg-gray-200 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400 transition-colors">
        Cancelar
      </button>
      <button type="button" onclick="confirmarFinalizarFase()" class="flex-1 rounded-lg bg-red-600 px-4 py-3 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
        Sí, Finalizar
      </button>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
  // Función para mostrar notificaciones flotantes
  function mostrarNotificacion(mensaje, tipo = 'success') {
    console.log('⚡ Creando notificación:', tipo, mensaje);
    
    // Crear el contenedor de la notificación
    const notificacion = document.createElement('div');
    
    // Definir colores según el tipo
    const colores = {
      success: {
        bg: '#f0fdf4',
        border: '#86efac',
        iconColor: '#16a34a',
        textColor: '#166534',
        iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />'
      },
      error: {
        bg: '#fef2f2',
        border: '#fca5a5',
        iconColor: '#dc2626',
        textColor: '#991b1b',
        iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />'
      },
      warning: {
        bg: '#fefce8',
        border: '#fde047',
        iconColor: '#ca8a04',
        textColor: '#854d0e',
        iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />'
      },
      info: {
        bg: '#eff6ff',
        border: '#93c5fd',
        iconColor: '#2563eb',
        textColor: '#1e40af',
        iconSvg: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />'
      }
    };
    
    const color = colores[tipo] || colores.info;
    
    // Aplicar estilos inline
    notificacion.style.cssText = `
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 9999;
      max-width: 28rem;
      width: 100%;
      transform: translateX(100%);
      opacity: 0;
      transition: all 0.5s ease-out;
    `;
    
    notificacion.innerHTML = `
      <div style="
        background-color: ${color.bg};
        border: 2px solid ${color.border};
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
      ">
        <div style="display: flex; align-items: flex-start;">
          <div style="flex-shrink: 0;">
            <svg style="width: 1.5rem; height: 1.5rem; color: ${color.iconColor};" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              ${color.iconSvg}
            </svg>
          </div>
          <div style="margin-left: 0.75rem; flex: 1;">
            <p style="font-size: 0.875rem; font-weight: 500; color: ${color.textColor}; margin: 0;">
              ${mensaje}
            </p>
          </div>
          <div style="margin-left: 1rem; flex-shrink: 0;">
            <button onclick="this.closest('div[style*=\\'position: fixed\\']').remove()" style="
              display: inline-flex;
              color: ${color.textColor};
              background: none;
              border: none;
              cursor: pointer;
              padding: 0;
            ">
              <svg style="width: 1.25rem; height: 1.25rem;" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    `;
    
    document.body.appendChild(notificacion);
    console.log('✓ Notificación agregada al DOM');
    
    // Animar entrada
    setTimeout(() => {
      notificacion.style.transform = 'translateX(0)';
      notificacion.style.opacity = '1';
      console.log('✓ Animación de entrada iniciada');
    }, 10);
    
    // Auto-cerrar después de 5 segundos
    setTimeout(() => {
      notificacion.style.transform = 'translateX(100%)';
      notificacion.style.opacity = '0';
      setTimeout(() => {
        notificacion.remove();
        console.log('✓ Notificación eliminada');
      }, 500);
    }, 5000);
  }

  // Mostrar notificaciones de sesión al cargar la página
  function verificarMensajesSesion() {
    console.log('Página cargada, verificando mensajes de sesión...');
    
    // Verificar si hay mensaje de éxito
    const sessionSuccess = document.getElementById('session-success');
    if (sessionSuccess) {
      const mensaje = sessionSuccess.getAttribute('data-message');
      console.log('Mensaje de éxito encontrado:', mensaje);
      if (mensaje && mensaje.trim() !== '') {
        mostrarNotificacion(mensaje, 'success');
      }
    }

    // Verificar si hay mensaje de error
    const sessionError = document.getElementById('session-error');
    if (sessionError) {
      const mensaje = sessionError.getAttribute('data-message');
      console.log('Mensaje de error encontrado:', mensaje);
      if (mensaje && mensaje.trim() !== '') {
        mostrarNotificacion(mensaje, 'error');
      }
    }

    // Verificar si hay errores de validación
    const sessionErrors = document.getElementById('session-errors');
    if (sessionErrors) {
      const mensaje = sessionErrors.getAttribute('data-message');
      console.log('Errores de validación encontrados:', mensaje);
      if (mensaje && mensaje.trim() !== '') {
        mostrarNotificacion(mensaje, 'error');
      }
    }
  }

  // Ejecutar cuando el DOM esté listo
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', verificarMensajesSesion);
  } else {
    // El DOM ya está listo, ejecutar inmediatamente
    verificarMensajesSesion();
  }

  // Validación de puntajes (clamp 0-100)
  document.querySelectorAll('input[type="number"]').forEach(function(input) {
    input.addEventListener('input', function() {
      const value = parseFloat(this.value);
      if (value < 0) this.value = 0;
      if (value > 100) this.value = 100;
    });
  });

  // Funciones para el modal de confirmación
  function mostrarModalConfirmacion() {
    const modal = document.getElementById('modalConfirmacion');
    modal.classList.remove('hidden');
    // Agregar animación
    setTimeout(() => {
      modal.querySelector('.relative').classList.add('scale-100', 'opacity-100');
    }, 10);
  }

  function cerrarModalConfirmacion() {
    const modal = document.getElementById('modalConfirmacion');
    modal.querySelector('.relative').classList.remove('scale-100', 'opacity-100');
    setTimeout(() => {
      modal.classList.add('hidden');
    }, 200);
  }

  function confirmarFinalizarFase() {
    document.getElementById('finalizarFaseForm').submit();
  }

  // Cerrar modal al hacer clic fuera de él
  document.getElementById('modalConfirmacion')?.addEventListener('click', function(e) {
    if (e.target === this) {
      cerrarModalConfirmacion();
    }
  });
</script>
@endsection
