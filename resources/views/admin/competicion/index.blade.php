@extends('layouts.app')
@section('title', 'Competicion · Admin')

@section('content')
    <!--  CABECERA / ACCIONES   -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold mb-2 text-black">Gestión de Competencias</h1>
            <p class="text-muted-foreground text-gray-700">
                Crea y administra competencias académicas con fases, áreas y niveles educativos
            </p>
        </div>
        <button class="flex items-center gap-2 bg-[#091c47] hover:bg-blue-800 transition text-white font-semibold px-5 py-2 rounded-lg shadow create-btn btn-pressable self-start md:self-auto w-full md:w-auto justify-center" type="button"
            onclick="window.location='{{ route('admin.competicion.create') }}'">
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" />
            </svg>
            <span>Crear</span>
        </button>
    </div>

    <form id="searchForm" class="search-panel w-full max-w-md mx-auto mb-6" action="{{ route('admin.competicion.index') }}" method="GET">
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <input id="searchInput" name="search" type="text" value="{{ request('search') }}" placeholder="Buscar por nombre o descripción..." aria-label="Buscar competencia" class="w-full px-4 py-2 pr-10 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" />
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <button class="search-btn btn-pressable bg-[#091c47] hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition flex-shrink-0" type="submit">Buscar</button>
        </div>
    </form>

    @if (request()->routeIs('admin.competicion.create'))
        {{-- formulario de creacion --}}
        @include('admin.competicion.partials.form')
    @else
        {{-- Lista de competiciones con filtros y tarjetas --}}
        @if ($competiciones->count() === 0)
            <div class="rounded-lg border border-border bg-card py-12 my-8">
                <p class="text-center text-lg font-semibold">No hay competiciones registradas.</p>
            </div>
        @else
            @include('admin.competicion.partials.list')
            <div class="mt-8 flex justify-center">
                {{ $competiciones->links('pagination::bootstrap-4') }}
            </div>
        @endif
    @endif

      @push('scripts')
      <script>
          // Filtros dinámicos
          document.addEventListener('DOMContentLoaded', function() {
              const filterButtons = document.querySelectorAll('[data-filter]');
              const competitionCards = document.querySelectorAll('[data-status]');
        
              filterButtons.forEach(button => {
                  button.addEventListener('click', function() {
                      const filter = this.getAttribute('data-filter');
                
                      // Actualizar botones activos
                      filterButtons.forEach(btn => {
                          btn.classList.remove('bg-primary', 'text-primary-foreground', 'bg-emerald-500', 'bg-blue-500', 'bg-red-500', 'text-white');
                          btn.classList.add('border', 'border-border', 'text-foreground', 'hover:bg-secondary', 'bg-transparent');
                      });
                
                      // Botones
                      this.classList.remove('border', 'border-border', 'text-foreground', 'hover:bg-secondary', 'bg-transparent');
                      if (filter === 'active') {
                          this.classList.add('bg-emerald-500', 'text-white');
                      } else if (filter === 'completed') {
                          this.classList.add('bg-blue-500', 'text-white');
                      } else if (filter === 'cancelled') {
                          this.classList.add('bg-red-500', 'text-white');
                      } else {
                          this.classList.add('bg-primary', 'text-primary-foreground');
                      }
                
                      // Filtrar tarjetas
                      competitionCards.forEach(card => {
                          if (filter === 'all' || card.getAttribute('data-status') === filter) {
                              card.style.display = 'block';
                          } else {
                              card.style.display = 'none';
                          }
                      });
                  });
              });
          });
      </script>
      @endpush
@endsection
