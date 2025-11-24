@extends('layouts.app')
@section('title', 'Competicion · Admin')

@section('content')
<div class="mx-auto max-w-full px-8 py-8">
  <!-- Título -->
  <header class="mb-6">
    <h1 class="text-center text-3xl font-semibold tracking-tight">Gestion de Evaluacion</h1>
  </header>

  <!-- Buscador -->
  <section class="mb-8">
    <form method="GET" action="{{ route('admin.evaluacion.index') }}" class="mx-auto flex max-w-2xl items-stretch gap-2">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <!-- icono lupa -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-slate-400">
            <path fill-rule="evenodd" d="M10 2a8 8 0 105.293 14.293l3.707 3.707a1 1 0 001.414-1.414l-3.707-3.707A8 8 0 0010 2zm-6 8a6 6 0 1110.392 3.906.997.997 0 00-.116.116A6 6 0 014 10z" clip-rule="evenodd" />
          </svg>
        </span>
        <input name="search" type="text" placeholder="Buscar competición..." value="{{ request('search') }}" class="w-full rounded-full border border-slate-300 bg-white py-2.5 pl-10 pr-4 text-sm placeholder:text-slate-400 shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200" />
      </div>
      <button type="submit" class="rounded-full bg-slate-700 px-5 text-sm font-medium text-white shadow hover:bg-slate-800 active:scale-[.98]">Buscar</button>
      @if(request('search'))
        <a href="{{ route('admin.evaluacion.index') }}" class="rounded-full bg-gray-500 px-5 text-sm font-medium text-white shadow hover:bg-gray-600 active:scale-[.98] flex items-center">Limpiar</a>
      @endif
    </form>
  </section>

  <!-- Grid de tarjetas -->
  <section id="grid" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
    @forelse($competiciones as $index => $competicion)
      @php
        $colors = ['bg-red-600', 'bg-blue-600', 'bg-green-600', 'bg-purple-600', 'bg-yellow-600', 'bg-pink-600'];
        $svgColors = ['#ef4444', '#2563eb', '#16a34a', '#9333ea', '#eab308', '#ec4899'];
        $colorIndex = $index % count($colors);
        
        $estadoTexto = '';
        $estadoColor = '';
        $btnTexto = 'Ver';
        $btnAction = '';
        
        switch($competicion->state) {
          case 'activa':
            $estadoTexto = 'En Proceso';
            $btnTexto = 'Gestionar';
            break;
          case 'finalizada':
            $estadoTexto = 'Finalizada';
            $btnTexto = 'Ver Resultados';
            break;
          case 'pendiente':
          default:
            $estadoTexto = 'Pendiente';
            $btnTexto = 'Iniciar';
            break;
        }
      @endphp
      
      <!-- Competición {{ $competicion->name }} -->
      <article class="phase-card rounded-xl bg-white shadow ring-2 ring-{{ str_replace('bg-', '', $colors[$colorIndex]) }} overflow-hidden transition-all duration-200 hover:shadow-lg hover:scale-[1.02] hover:ring-4 cursor-pointer" data-phase="{{ strtolower($competicion->name) }}">
        
        <div class="h-1 {{ $colors[$colorIndex] }}"></div>
        <div class="brush relative px-4 pt-3">
          <svg viewBox="0 0 360 90" class="w-full">
            <defs>
              <filter id="cloudShadow{{ $index }}">
                <feDropShadow dx="2" dy="3" stdDeviation="4" flood-opacity="0.2"/>
              </filter>
            </defs>
            <!-- Nube orgánica con círculos superpuestos -->
            <!-- Base principal de la nube -->
            <ellipse cx="180" cy="45" rx="100" ry="20" 
                     fill="{{ $svgColors[$colorIndex] }}" 
                     filter="url(#cloudShadow{{ $index }})"
                     opacity="0.9"/>
            
            <!-- Círculos para crear la forma de nube -->
            <!-- Lado izquierdo -->
            <circle cx="90" cy="40" r="28" fill="{{ $svgColors[$colorIndex] }}" opacity="0.85"/>
            <circle cx="115" cy="32" r="22" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            <circle cx="100" cy="55" r="18" fill="{{ $svgColors[$colorIndex] }}" opacity="0.75"/>
            
            <!-- Centro superior -->
            <circle cx="155" cy="28" r="20" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            <circle cx="180" cy="25" r="24" fill="{{ $svgColors[$colorIndex] }}" opacity="0.85"/>
            <circle cx="205" cy="28" r="20" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            
            <!-- Lado derecho -->
            <circle cx="270" cy="40" r="28" fill="{{ $svgColors[$colorIndex] }}" opacity="0.85"/>
            <circle cx="245" cy="32" r="22" fill="{{ $svgColors[$colorIndex] }}" opacity="0.8"/>
            <circle cx="260" cy="55" r="18" fill="{{ $svgColors[$colorIndex] }}" opacity="0.75"/>
            
            <!-- Detalles inferiores -->
            <circle cx="145" cy="58" r="15" fill="{{ $svgColors[$colorIndex] }}" opacity="0.7"/>
            <circle cx="180" cy="62" r="16" fill="{{ $svgColors[$colorIndex] }}" opacity="0.7"/>
            <circle cx="215" cy="58" r="15" fill="{{ $svgColors[$colorIndex] }}" opacity="0.7"/>
          </svg>
          <div class="pointer-events-none absolute inset-0 flex items-center justify-center px-3">
            <span class="select-none text-base md:text-lg font-extrabold tracking-wide text-white drop-shadow-lg text-center leading-tight max-w-full">
              {{ Str::upper($competicion->name) }}
            </span>
          </div>
        </div>
        <div class="space-y-1.5 px-4 pb-4 pt-2 text-xs">
          <p><span class="font-semibold">Estado:</span> {{ $estadoTexto }}</p>
          
          @if($competicion->area)
            <p><span class="font-semibold">Área:</span> {{ $competicion->area->name }}</p>
          @endif
          
          @if($competicion->categorias && $competicion->categorias->count() > 0)
            <p><span class="font-semibold">Categorías:</span> 
              {{ $competicion->categorias->pluck('nombre')->join(', ') }}
            </p>
          @endif
          
          <p><span class="font-semibold">Fecha Inicio:</span> {{ $competicion->fechaInicio ? $competicion->fechaInicio->format('d/m/Y') : 'No definida' }}</p>
          
          @if($competicion->fechaFin)
            <p><span class="font-semibold">Fecha Fin:</span> {{ $competicion->fechaFin->format('d/m/Y') }}</p>
          @endif
          
          @if($competicion->phases && $competicion->phases->count() > 0)
            <p><span class="font-semibold">Fases:</span> {{ $competicion->phases->count() }}</p>
          @endif
          
          @if($competicion->description)
            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ Str::limit($competicion->description, 60) }}</p>
          @endif
          
          <div class="pt-1.5">
            @if($competicion->state === 'activa')
              <a href="{{ route('admin.evaluacion.fases', $competicion->id) }}" class="inline-block rounded-full bg-slate-700 px-5 py-2 text-white text-sm shadow hover:bg-slate-800 no-underline">
                {{ $btnTexto }}
              </a>
            @else
              <button class="rounded-full bg-slate-700 px-5 py-2 text-white text-sm shadow hover:bg-slate-800" onclick="gestionarCompeticion({{ $competicion->id }}, '{{ $competicion->state }}')">
                {{ $btnTexto }}
              </button>
            @endif
          </div>
        </div>
      </article>
    @empty
      <div class="col-span-full text-center py-12">
        <div class="text-gray-500">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">No hay competiciones</h3>
          <p class="mt-1 text-sm text-gray-500">Comienza creando una nueva competición.</p>
        </div>
      </div>
    @endforelse
  </section>

  <!-- Paginación -->
  @if($competiciones->hasPages())
    <div class="mt-8">
      {{ $competiciones->links() }}
    </div>
  @endif
</div>

<!-- Script para gestionar competiciones -->
<script>
  // Función para gestionar competición según su estado
  function gestionarCompeticion(competicionId, estado) {
    switch(estado) {
      case 'pendiente':
        // Redirigir a página para iniciar competición
        console.log(`Iniciar competición ${competicionId}`);
        // window.location.href = `/dashboard/admin/competicion/${competicionId}/iniciar`;
        alert('Funcionalidad para iniciar competición en desarrollo');
        break;
      case 'finalizada':
        // Redirigir a página de resultados finales
        console.log(`Ver resultados de competición ${competicionId}`);
        // window.location.href = `/dashboard/admin/competicion/${competicionId}/resultados`;
        alert('Funcionalidad para ver resultados en desarrollo');
        break;
      default:
        // Acción por defecto
        console.log(`Gestionar competición ${competicionId}`);
        break;
    }
  }
</script>
@endsection

