@extends('layouts.app')
@section('title', 'Calificación Grupal · Admin')

@section('content')
<div class="mx-auto max-w-full px-8 py-8">
  <!-- Título -->
  <header class="mb-6">
    <div class="flex flex-col items-center justify-center">
      <div class="text-center">
        <h1 class="text-3xl font-semibold tracking-tight">Calificar Estudiantes (Grupal)</h1>
        <p class="text-sm text-gray-600 mt-2">{{ $fase->name }} - {{ $competicion->name }}</p>
      </div>
      <a href="{{ route('admin.evaluacion.fase.estudiantes', ['competicion' => $competicion->id, 'fase' => $fase->id, 'fase_n' => $numeroFase]) }}" class="rounded-full bg-gray-500 px-4 py-2 text-white text-sm shadow hover:bg-gray-600 mt-4">
        ← Volver a Estudiantes
      </a>
    </div>
  </header>

  <!-- Información de la competición y fase -->
  <section class="mb-8 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-lg p-4 border border-purple-200">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm">
      <div>
        <span class="font-semibold text-purple-800">Competición:</span>
        <span class="text-purple-700">{{ $competicion->name }}</span>
      </div>
      <div>
        <span class="font-semibold text-purple-800">Fase:</span>
        <span class="text-purple-700">{{ $fase->name }} (Nivel {{ $numeroFase }})</span>
      </div>
      <div>
        <span class="font-semibold text-purple-800">Estado:</span>
        <span class="text-purple-700">{{ ucfirst($competicion->state) }}</span>
      </div>
      <div>
        <span class="font-semibold text-purple-800">Total Estudiantes:</span>
        <span class="text-purple-700">{{ $estudiantes->total() }}</span>
      </div>
      <div>
        <span class="font-semibold text-purple-800">Nivel de Fase:</span>
        <span class="text-purple-700">{{ $numeroFase }}</span>
      </div>
    </div>
  </section>

  <!-- Buscador -->
  <section class="mb-6">
    <form method="GET" action="{{ route('admin.evaluacion.calificar.grupal', ['competicion' => $competicion->id, 'fase' => $fase->id]) }}" class="flex items-center gap-3 justify-center">
      <input type="hidden" name="fase_n" value="{{ $numeroFase }}">
      <div class="relative flex-1 max-w-md w-full flex justify-center">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-gray-400">
            <path fill-rule="evenodd" d="M10 2a8 8 0 105.293 14.293l3.707 3.707a1 1 0 001.414-1.414l-3.707-3.707A8 8 0 0010 2zm-6 8a6 6 0 1110.392 3.906.997.997 0 00-.116.116A6 6 0 014 10z" clip-rule="evenodd" />
          </svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, apellido, unidad educativa o CI..." class="w-full rounded-md border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm placeholder:text-gray-400 shadow-sm focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500" />
      </div>
      <button type="submit" class="rounded-md px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" style="background-color: #7C3AED;">
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
        <h3 class="text-lg font-medium text-gray-900">Calificación de Estudiantes (Categoría Grupal)</h3>
        <p class="mt-1 text-sm text-gray-500">
          Mostrando {{ $estudiantes->count() }} de {{ $estudiantes->total() }} estudiantes
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
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de Grupo</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CI</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Puntaje (0-100)</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Promedio</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado Calificación</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @php
              $grupoAnterior = null;
            @endphp
            @foreach($estudiantes as $estudiante)
              @php
                $evaluacion = $estudiante->evaluations->first();
                $estaCalificado = $evaluacion && $evaluacion->nota !== null;
                $grupoActual = $estudiante->name_grupo ?? 'Sin nombre';
              @endphp
              
              <!-- Separador de grupo -->
              @if($grupoAnterior === null || $grupoAnterior !== $grupoActual)
                <tr class="bg-purple-100">
                  <td colspan="9" class="px-6 py-3">
                    <div class="flex items-center justify-center">
                      <div class="flex-grow border-t-2 border-purple-300"></div>
                      <span class="px-4 text-sm font-semibold text-purple-700">Grupo: {{ $grupoActual }}</span>
                      <div class="flex-grow border-t-2 border-purple-300"></div>
                    </div>
                  </td>
                </tr>
              @endif
              
              @php
                $grupoAnterior = $grupoActual;
              @endphp
              
              <tr class="hover:bg-gray-50 {{ $estaCalificado ? 'bg-gray-50/50' : '' }}">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                        <span class="text-sm font-medium text-purple-700">
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
                  <span class="font-medium text-purple-700">{{ $estudiante->name_grupo ?? 'Sin nombre' }}</span>
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
                         class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm text-center focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  @php
                    $observacionesExistentes = $evaluacion ? $evaluacion->observaciones_evaluador : '';
                  @endphp
                  <textarea form="calif-{{ $estudiante->id }}"
                            name="calificaciones[{{ $estudiante->id }}][observaciones]"
                            rows="1"
                            placeholder="Observaciones..."
                            class="w-32 rounded-md border border-gray-300 px-2 py-1 text-sm resize-none focus:border-purple-500 focus:outline-none focus:ring-1 focus:ring-purple-500">{{ $observacionesExistentes }}</textarea>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  @php $promedioValor = $evaluacion && $evaluacion->promedio !== null ? number_format($evaluacion->promedio, 2) : ''; @endphp
                  <input type="number" value="{{ $promedioValor }}" class="w-20 rounded-md border border-gray-300 px-2 py-1 text-sm text-center bg-gray-100 text-indigo-700" readonly tabindex="-1" style="pointer-events:none;">
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
                      <button type="submit" class="rounded-md px-3 py-1.5 text-xs font-medium text-white shadow-sm hover:opacity-90" style="background-color: #7C3AED;">
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

      <!-- Paginación -->
      <div class="px-6 py-4 border-t border-gray-200">
        {{ $estudiantes->appends(request()->query())->links() }}
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
    @php
      // Contar grupos únicos
      $gruposUnicos = $estudiantes->pluck('name_grupo')->unique()->filter(function($grupo) {
        return $grupo !== null && $grupo !== 'N/A';
      })->count();
    @endphp
    <p class="text-sm text-gray-600 text-center mb-6">
      Esta acción clasificará a la siguiente fase <span class="font-semibold text-blue-600">solo los {{ $gruposUnicos }} {{ $gruposUnicos == 1 ? 'grupo listado' : 'grupos listados' }} actualmente</span> que cumplan el criterio configurado. 
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
  // Funciones para el modal de confirmación
  function mostrarModalConfirmacion() {
    const modal = document.getElementById('modalConfirmacion');
    modal.classList.remove('hidden');
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
  document.getElementById('modalConfirmacion')?.addEventListener('click', function(e) {
    if (e.target === this) {
      cerrarModalConfirmacion();
    }
  });
</script>
@endsection
