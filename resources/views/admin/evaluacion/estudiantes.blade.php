@extends('layouts.app')
@section('title', 'Gestión de Estudiantes · Admin')

@section('content')
<div class="mx-auto max-w-full px-8 py-8">
  <!-- Título -->
  <header class="mb-6">
    <div class="flex items-center justify-between">
      <div class="flex-1">
        <h1 class="text-center text-3xl font-semibold tracking-tight">{{ $fase->name }}</h1>
        <p class="text-center text-sm text-gray-600 mt-2">Gestión de estudiantes - {{ $competicion->name }}</p>
      </div>
      <a href="{{ route('admin.evaluacion.fases', $competicion->id) }}" class="rounded-full bg-gray-500 px-4 py-2 text-white text-sm shadow hover:bg-gray-600">
        ← Volver a Fases
      </a>
    </div>
  </header>

  <!-- Información de la fase -->
  <section class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 text-sm">
      <div>
        <span class="font-semibold text-blue-800">Competición:</span>
        <span class="text-blue-700">{{ $competicion->name }}</span>
      </div>
      <div>
        <span class="font-semibold text-blue-800">Fase:</span>
        <span class="text-blue-700">{{ $fase->name }} (Nivel {{ $numeroFase }})</span>
      </div>
      <div>
        <span class="font-semibold text-blue-800">Estado:</span>
        <span class="text-blue-700">{{ ucfirst($competicion->state) }}</span>
      </div>
      @if($competicion->area)
      <div>
        <span class="font-semibold text-blue-800">Área:</span>
        <span class="text-blue-700">{{ $competicion->area->name }}</span>
      </div>
      @endif
      <div>
        <span class="font-semibold text-blue-800">Total Estudiantes:</span>
        <span class="text-blue-700">{{ $estudiantes->total() }}</span>
      </div>
    </div>
  </section>

  <!-- Filtros -->
  <section class="mb-6">
    <form method="GET" action="{{ route('admin.evaluacion.fase.estudiantes', ['competicion' => $competicion->id, 'fase' => $fase->id]) }}" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <!-- Preservar número de fase -->
      <input type="hidden" name="fase_n" value="{{ $numeroFase }}">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <!-- Combo box de Categorías -->
        <div>
          <label for="categoria" class="block text-sm font-medium text-gray-700 mb-2">Categoría</label>
          <select name="categoria" id="categoria" class="w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $categoria)
              <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                {{ $categoria->nombre }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Combo box de Áreas -->
        <div>
          <label for="area" class="block text-sm font-medium text-gray-700 mb-2">Área</label>
          <select name="area" id="area" class="w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="">Todas las áreas</option>
            @foreach($areas as $area)
              <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>
                {{ $area->name }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Combo box de Estado Activo -->
        <div>
          <label for="estado_activo" class="block text-sm font-medium text-gray-700 mb-2">Estado de Registro</label>
          <select name="estado_activo" id="estado_activo" class="w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <option value="activo" {{ request('estado_activo', 'activo') == 'activo' ? 'selected' : '' }}>Solo Activos</option>
            <option value="inactivo" {{ request('estado_activo') == 'inactivo' ? 'selected' : '' }}>Solo Inactivos</option>
            <option value="todos" {{ request('estado_activo') == 'todos' ? 'selected' : '' }}>Todos los Estados</option>
          </select>
        </div>
      </div>

      <!-- Botón Filtrar -->
      <div class="flex justify-start">
        <button type="submit" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
          Filtrar
        </button>
        @if(request()->hasAny(['categoria', 'area', 'search', 'estado_activo']))
          <a href="{{ route('admin.evaluacion.fase.estudiantes', ['competicion' => $competicion->id, 'fase' => $fase->id, 'fase_n' => $numeroFase]) }}" class="ml-3 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            Limpiar Filtros
          </a>
        @endif
      </div>
    </form>
  </section>

  <!-- Buscador -->
  <section class="mb-6">
    <form method="GET" action="{{ route('admin.evaluacion.fase.estudiantes', ['competicion' => $competicion->id, 'fase' => $fase->id]) }}" class="flex items-center gap-3 justify-center">
      <!-- Mantener filtros actuales -->
      @if(request('categoria'))
        <input type="hidden" name="categoria" value="{{ request('categoria') }}">
      @endif
      @if(request('area'))
        <input type="hidden" name="area" value="{{ request('area') }}">
      @endif
      @if(request('estado_activo'))
        <input type="hidden" name="estado_activo" value="{{ request('estado_activo') }}">
      @endif
      <!-- Preservar número de fase -->
      <input type="hidden" name="fase_n" value="{{ $numeroFase }}">
      <div class="relative flex-1 max-w-md w-full flex justify-center">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-gray-400">
            <path fill-rule="evenodd" d="M10 2a8 8 0 105.293 14.293l3.707 3.707a1 1 0 001.414-1.414l-3.707-3.707A8 8 0 0010 2zm-6 8a6 6 0 1110.392 3.906.997.997 0 00-.116.116A6 6 0 014 10z" clip-rule="evenodd" />
          </svg>
        </span>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nombre, apellido o unidad educativa..." class="w-full rounded-md border border-gray-300 bg-white py-2 pl-10 pr-4 text-sm placeholder:text-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
      </div>
      <button type="submit" class="rounded-md px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2" style="background-color: #091C47;">
        Buscar
      </button>
    </form>
  </section>

  <!-- Tabla de Estudiantes -->
  <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
      <div>
        <h3 class="text-lg font-medium text-gray-900">Lista de Estudiantes</h3>
        <p class="mt-1 text-sm text-gray-500">
          Mostrando {{ $estudiantes->count() }} de {{ $estudiantes->total() }} estudiantes
        </p>
      </div>
      <div class="flex gap-3 items-center">
        <a href="{{ route('admin.evaluacion.fase.estudiantes.pdf', ['competicion' => $competicion->id, 'fase' => $fase->id, 'fase_n' => $numeroFase, 'categoria' => request('categoria'), 'area' => request('area'), 'estado_activo' => request('estado_activo'), 'search' => request('search')]) }}" target="_blank" class="rounded-md px-6 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90" style="background-color: #2563eb;">
          Crear Lista Inscritos
        </a>
        <a href="{{ route('admin.evaluacion.fase.clasificados.pdf', ['competicion' => $competicion->id, 'fase' => $fase->id, 'fase_n' => $numeroFase, 'categoria' => request('categoria'), 'area' => request('area'), 'estado_activo' => request('estado_activo'), 'search' => request('search')]) }}" target="_blank" class="rounded-md px-6 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90" style="background-color: #059669;">
          Crear Lista Clasificados
        </a>
        <a href="{{ route('admin.evaluacion.calificar', ['competicion' => $competicion->id, 'fase' => $fase->id, 'fase_n' => $numeroFase]) }}" class="rounded-md px-6 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90" style="background-color: #091C47;">
          Iniciar Calificación Individual
        </a>
        <a href="{{ route('admin.evaluacion.calificar.grupal', ['competicion' => $competicion->id, 'fase' => $fase->id, 'fase_n' => $numeroFase]) }}" class="rounded-md px-6 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90" style="background-color: #091c47;">
          Iniciar Calificación Grupal
        </a>
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
                Unidad Educativa
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Área
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Categoría
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Estado
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($estudiantes as $estudiante)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <span class="text-sm font-medium text-indigo-700">
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
                  {{ $estudiante->user->school ?? 'No especificada' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $estudiante->area->name ?? 'No asignada' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $estudiante->categoria->nombre ?? 'No asignada' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @php
                    $estadoClass = 'bg-gray-100 text-gray-800';
                    $estadoTexto = 'Pendiente';
                    switch($estudiante->estado) {
                      case 'confirmada':
                        $estadoClass = 'bg-green-100 text-green-800';
                        $estadoTexto = 'Confirmada';
                        break;
                      case 'rechazada':
                        $estadoClass = 'bg-red-100 text-red-800';
                        $estadoTexto = 'Rechazada';
                        break;
                      case 'pendiente':
                      default:
                        $estadoClass = 'bg-yellow-100 text-yellow-800';
                        $estadoTexto = 'Pendiente';
                        break;
                    }
                  @endphp
                  <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $estadoClass }}">
                    {{ $estadoTexto }}
                  </span>
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
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.25" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay estudiantes</h3>
        <p class="mt-1 text-sm text-gray-500">No se encontraron estudiantes con los filtros aplicados.</p>
      </div>
    @endif
  </section>
</div>

<!-- Scripts -->
<script>
  // Auto-submit del formulario cuando cambian los selects
  document.getElementById('categoria').addEventListener('change', function() {
    this.form.submit();
  });

  document.getElementById('area').addEventListener('change', function() {
    this.form.submit();
  });

  document.getElementById('estado_activo').addEventListener('change', function() {
    this.form.submit();
  });
</script>
@endsection
