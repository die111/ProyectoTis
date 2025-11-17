@extends('layouts.app')
@section('title', 'Premiación · Admin')

@section('content')
<div class="mx-auto max-w-7xl px-5 py-8">
  <!-- Título -->
  <header class="mb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-semibold tracking-tight">Premiación de {{ $competicion->name }}</h1>
        <p class="text-sm text-gray-600 mt-2">Listado de premiados por área y nivel</p>
      </div>
      <a href="{{ route('admin.evaluacion.fases', $competicion->id) }}" class="rounded-full bg-gray-500 px-4 py-2 text-white text-sm shadow hover:bg-gray-600">
        ← Volver a Fases
      </a>
    </div>
  </header>

  <!-- Información de la competición -->
  <section class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
      <div>
        <span class="font-semibold text-blue-800">Competición:</span>
        <span class="text-blue-700">{{ $competicion->name }}</span>
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
        <span class="font-semibold text-blue-800">Total Clasificados:</span>
        <span class="text-blue-700">{{ $premiados->count() }}</span>
      </div>
    </div>
  </section>



  @if($premiadosGrouped->isEmpty())
    <!-- Tabla de Premiados -->
    <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.25" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay premiados</h3>
        <p class="mt-1 text-sm text-gray-500">No hay premiados configurados o aún no se han determinado.</p>
      </div>
    </section>
  @else
    <div class="space-y-6">
      @foreach($premiadosGrouped as $grupo => $items)
        <!-- Tabla de Premiados -->
        <section class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
          <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
              <h3 class="text-lg font-medium text-gray-900">{{ $grupo }}</h3>
              <p class="mt-1 text-sm text-gray-500">
                Mostrando {{ $items->count() }} clasificados
              </p>
            </div>
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-100 px-2 py-1 text-yellow-800">🥇 Oro</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-1 text-gray-700">🥈 Plata</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-amber-200 px-2 py-1 text-amber-900">🥉 Bronce</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2 py-1 text-blue-800">🎖️ Mención</span>
              </div>
              <button onclick="exportarGrupoPDF('{{ $grupo }}')" class="rounded-md px-4 py-2 text-sm font-medium text-white shadow-sm hover:opacity-90" style="background-color: #dc2626;">
                📄 Exportar PDF
              </button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Posición
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Estudiante
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Unidad Educativa
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nota
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Premio
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($items as $row)
                  @php
                    $badge = match($row['premio']){
                      'oro' => 'bg-yellow-100 text-yellow-900',
                      'plata' => 'bg-gray-100 text-gray-800',
                      'bronce' => 'bg-amber-100 text-amber-900',
                      'mencion_honor' => 'bg-blue-100 text-blue-800',
                      default => 'bg-slate-100 text-slate-800'
                    };
                    $label = match($row['premio']){
                      'oro' => '🥇 Oro',
                      'plata' => '🥈 Plata',
                      'bronce' => '🥉 Bronce',
                      'mencion_honor' => '🎖️ Mención de Honor',
                      default => ucfirst($row['premio'] ?? '—')
                    };
                  @endphp
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                          <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-sm font-bold text-indigo-700">
                              {{ $row['posicion'] }}
                            </span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ $row['nombre_completo'] }}
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {{ $row['unidad_educativa'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span class="font-semibold">{{ number_format($row['nota'] ?? 0, 2) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $badge }}">
                        {{ $label }}
                      </span>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </section>
      @endforeach
    </div>
  @endif
</div>

<!-- Scripts -->
<script>
  // Función para exportar grupo específico a PDF
  function exportarGrupoPDF(grupo) {
    const competicionId = {{ $competicion->id }};
    const area = grupo.split(' | ')[0];
    const nivel = grupo.split(' | ')[1];
    window.open(`/dashboard/admin/evaluacion/${competicionId}/premiacion/pdf?area=${encodeURIComponent(area)}&nivel=${encodeURIComponent(nivel)}`, '_blank');
  }
</script>
@endsection
