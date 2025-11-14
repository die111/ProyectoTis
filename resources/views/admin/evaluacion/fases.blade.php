@extends('layouts.app')
@section('title', 'Fases · Admin')

@section('content')
<div class="mx-auto max-w-6xl px-5 py-8">
  <!-- Título -->
  <header class="mb-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-center text-3xl font-semibold tracking-tight">Fases de {{ $competicion->name }}</h1>
        <p class="text-sm text-gray-600 mt-2">Gestión de fases para la competición</p>
      </div>
      <a href="{{ route('admin.evaluacion.index') }}" class="rounded-full bg-gray-500 px-4 py-2 text-white text-sm shadow hover:bg-gray-600">
        ← Volver a Competiciones
      </a>
    </div>
  </header>

  <!-- Información de la competición -->
  <section class="mb-8 bg-blue-50 rounded-lg p-4 border border-blue-200">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
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
        <span class="font-semibold text-blue-800">Total de Fases:</span>
        <span class="text-blue-700">{{ $fases->count() }}</span>
      </div>
    </div>
  </section>

  <!-- Buscador -->
  <section class="mb-8">
    <div class="mx-auto flex max-w-2xl items-stretch gap-2">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <!-- icono lupa -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-slate-400">
            <path fill-rule="evenodd" d="M10 2a8 8 0 105.293 14.293l3.707 3.707a1 1 0 001.414-1.414l-3.707-3.707A8 8 0 0010 2zm-6 8a6 6 0 1110.392 3.906.997.997 0 00-.116.116A6 6 0 014 10z" clip-rule="evenodd" />
          </svg>
        </span>
        <input id="searchInput" type="text" placeholder="Buscar fase..." class="w-full rounded-full border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm placeholder:text-slate-400 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
      </div>
      <button id="searchBtn" class="rounded-full bg-slate-700 px-5 text-sm font-medium text-white shadow hover:bg-slate-800 active:scale-[.98]">Buscar</button>
    </div>
  </section>

  <!-- Grid de tarjetas de fases -->
  <section id="grid" class="grid grid-cols-1 gap-6 md:grid-cols-2">
    @forelse($fases as $index => $fase)
      @php
        $colors = ['bg-red-600', 'bg-blue-600', 'bg-green-600', 'bg-purple-600', 'bg-yellow-600', 'bg-pink-600'];
        $svgColors = ['#ef4444', '#2563eb', '#16a34a', '#9333ea', '#eab308', '#ec4899'];
        $colorIndex = $index % count($colors);
        
        // Por ahora, simplificamos la lógica para que siempre muestre "Gestionar"
        $estadoTexto = 'Disponible';
        $btnTexto = 'Gestionar';
        
        // Determinar estado basado en las fechas del pivot (comentado por ahora)
        $fechaInicio = $fase->pivot->start_date ?? null;
        $fechaFin = $fase->pivot->end_date ?? null;
        $ahora = now();
        
        // Mostrar fechas si están disponibles
        if ($fechaInicio && $fechaFin) {
          if ($ahora < $fechaInicio) {
            $estadoTexto = 'Pendiente';
          } elseif ($ahora >= $fechaInicio && $ahora <= $fechaFin) {
            $estadoTexto = 'En Proceso';
          } else {
            $estadoTexto = 'Finalizada';
          }
        } elseif ($fechaInicio || $fechaFin) {
          $estadoTexto = 'Parcialmente Configurada';
        } else {
          $estadoTexto = 'Sin Fechas Configuradas';
        }

        // Numeración visible para la tarjeta (1-based)
        $numeroFase = $index + 1;
      @endphp
      
      <!-- Fase {{ $fase->name }} -->
      <article class="phase-card rounded-2xl bg-white shadow ring-2 ring-{{ str_replace('bg-', '', $colors[$colorIndex]) }} overflow-hidden transition-all duration-200 hover:shadow-lg hover:scale-[1.02] hover:ring-4 cursor-pointer" data-phase="{{ strtolower($numeroFase . ' ' . $fase->name) }}" data-phase-number="{{ $numeroFase }}">
        
        <div class="h-1.5 {{ $colors[$colorIndex] }}"></div>
        <div class="brush relative px-8 pt-6">
          <svg viewBox="0 0 360 120" class="w-full">
            <defs>
              <filter id="cloudShadow{{ $index }}">
                <feDropShadow dx="2" dy="3" stdDeviation="4" flood-opacity="0.2"/>
              </filter>
            </defs>
            <!-- Nube orgánica con círculos superpuestos -->
            <!-- Base principal de la nube -->
            <ellipse cx="180" cy="55" rx="120" ry="25" 
                     fill="{{ $svgColors[$colorIndex] }}" 
                     filter="url(#cloudShadow{{ $index }})"
                     opacity="0.9"/>
            
            <!-- Círculos para crear la forma de nube -->
            <!-- Lado izquierdo -->
            <circle cx="80" cy="50" r="35" fill="{{ $svgColors[$colorIndex] }}" opacity="0.85"/>
            <circle cx="110" cy="40" r="28" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            <circle cx="95" cy="65" r="22" fill="{{ $svgColors[$colorIndex] }}" opacity="0.75"/>
            
            <!-- Centro superior -->
            <circle cx="150" cy="35" r="25" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            <circle cx="180" cy="30" r="30" fill="{{ $svgColors[$colorIndex] }}" opacity="0.85"/>
            <circle cx="210" cy="35" r="25" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            
            <!-- Lado derecho -->
            <circle cx="280" cy="50" r="35" fill="{{ $svgColors[$colorIndex] }}" opacity="0.85"/>
            <circle cx="250" cy="40" r="28" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            <circle cx="265" cy="65" r="22" fill="{{ $svgColors[$colorIndex] }}" opacity="0.75"/>
            
            <!-- Detalles inferiores -->
            <circle cx="140" cy="70" r="18" fill="{{ $svgColors[$colorIndex] }}" opacity="0.7"/>
            <circle cx="180" cy="75" r="20" fill="{{ $svgColors[$colorIndex] }}" opacity="0.7"/>
            <circle cx="220" cy="70" r="18" fill="{{ $svgColors[$colorIndex] }}" opacity="0.7"/>
          </svg>

          <!-- Insignia con numeración de la tarjeta -->
          <div class="absolute left-4 top-3">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full {{ $colors[$colorIndex] }} text-white text-xs font-bold shadow">
              {{ $numeroFase }}
            </span>
          </div>

          <div class="pointer-events-none absolute inset-0 flex items-center justify-center px-4">
            <span class="select-none text-lg md:text-xl lg:text-2xl font-extrabold tracking-wide text-white drop-shadow-lg text-center leading-tight max-w-full">
              {{ Str::upper($fase->name) }}
            </span>
          </div>
        </div>
        <div class="space-y-2 px-8 pb-6 pt-2 text-sm">
          <p><span class="font-semibold">Estado:</span> {{ $estadoTexto }}</p>
          
          @if($fechaInicio)
            <p><span class="font-semibold">Fecha Inicio:</span> {{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</p>
          @endif
          
          @if($fechaFin)
            <p><span class="font-semibold">Fecha Fin:</span> {{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</p>
          @endif
          
          @if($fase->description)
            <p><span class="font-semibold">Descripción:</span></p>
            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($fase->description, 80) }}</p>
          @endif
          
          <div class="pt-2">
             {{-- @php
              $hoy = now();
              $fueraDeRango = false;
              if ($fechaInicio && $fechaFin) {
                $fueraDeRango = $hoy->lt($fechaInicio) || $hoy->gt($fechaFin);
              } elseif ($fechaInicio) {
                $fueraDeRango = $hoy->lt($fechaInicio);
              } elseif ($fechaFin) {
                $fueraDeRango = $hoy->gt($fechaFin);
              }
            @endphp
            <button class="rounded-full bg-slate-700 px-4 py-1.5 text-white text-sm shadow hover:bg-slate-800 {{ $fueraDeRango ? 'pointer-events-none opacity-60' : '' }}"
                    onclick="gestionarFase({{ $fase->id }}, {{ $numeroFase }}, '{{ $btnTexto }}')" @if($fueraDeRango) disabled @endif> --}}
             
              <button class="rounded-full bg-slate-700 px-4 py-1.5 text-white text-sm shadow hover:bg-slate-800" 
              onclick="gestionarFase({{ $fase->id }}, {{ $numeroFase }}, '{{ $btnTexto }}')">
              
              {{ $btnTexto }}
            </button>
          </div>
        </div>
      </article>
    @empty
      <div class="col-span-full text-center py-12">
        <div class="text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No hay fases configuradas</h3>
          <p class="mt-1 text-sm text-gray-500">Esta competición aún no tiene fases asignadas.</p>
          <div class="mt-4">
            <button class="rounded-full bg-blue-600 px-4 py-2 text-white text-sm shadow hover:bg-blue-700">
              Agregar Fase
            </button>
          </div>
        </div>
      </div>
    @endforelse
  </section>
</div>

<!-- Scripts -->
<script>
  const input = document.getElementById('searchInput');
  const btn = document.getElementById('searchBtn');
  const cards = Array.from(document.querySelectorAll('.phase-card'));

  function applyFilter() {
    const q = input.value.trim().toLowerCase();
    cards.forEach(card => {
      const faseName = card.dataset.phase || '';
      const cardText = card.textContent.toLowerCase();
      const matches = faseName.includes(q) || cardText.includes(q) || q === '';
      card.classList.toggle('hidden', !matches);
    });
  }

  btn.addEventListener('click', applyFilter);
  input.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') applyFilter();
  });
  
  // Búsqueda en tiempo real
  input.addEventListener('input', applyFilter);

  // Función para gestionar fase
  function gestionarFase(faseId, faseNumero, accion) {
    console.log(`Gestionar fase ${faseId} (número ${faseNumero}) - Acción: ${accion}`);
    const competicionId = {{ $competicion->id }};
    // Usar fase_n para no chocar con el parámetro de ruta {fase}
    window.location.href = `/dashboard/admin/evaluacion/${competicionId}/fase/${faseId}/estudiantes?fase_n=${faseNumero}`;
  }
</script>
@endsection
