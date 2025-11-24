@extends('layouts.app')
@section('title', 'Competicion · Admin')

@section('title', 'Competicion · Admin')

@section('content')
    <!--  CABECERA / ACCIONES   -->
    <div class="container mx-auto px-4 max-w-full relative">
    <div class="mb-8">
        <div class="w-full text-center max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-2 text-black">Gestión de Competencias</h1>
            <p class="text-muted-foreground text-gray-700">
                Crea y administra competencias académicas con categirias, fases y areas
            </p>
        </div>
        <button class="absolute right-0 top-0 flex items-center gap-2 bg-[#091c47] hover:bg-blue-800 transition text-white font-semibold px-5 py-2 rounded-lg shadow create-btn btn-pressable" type="button"
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
                <!-- Ajustado placeholder para indicar que sólo busca por nombre -->
                <input id="searchInput" name="search" type="text" value="{{ request('search') }}" placeholder="Buscar por nombre..." aria-label="Buscar competencia" class="w-full px-4 py-2 pr-10 rounded-lg border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800" />
                <svg class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="7"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
            <button class="search-btn btn-pressable bg-[#091c47] hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition flex-shrink-0" type="submit">Buscar</button>
        </div>
    </form>

    @if (request()->routeIs('admin.competicion.create') && View::exists('admin.competicion.partials.form'))
        {{-- formulario de creacion --}}
        @include('admin.competicion.partials.form')
    @elseif (request()->routeIs('admin.competicion.create'))
        <div class="rounded-lg border border-red-300 bg-red-50 p-6 my-8 text-sm text-red-700">
            La vista de formulario no está disponible.
        </div>
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
    </div>

      @push('scripts')
      <script>
          document.addEventListener('DOMContentLoaded', function() {
              const filterButtons = document.querySelectorAll('[data-filter]');
              const competitionCards = document.querySelectorAll('[data-status]');
              const searchInput = document.getElementById('searchInput');
              let currentFilter = 'all';

              function normalize(str){
                  return (str || '').toString().toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
              }

              function applyFilters(){
                  const query = normalize(searchInput.value.trim());
                  const tokens = query.length ? query.split(/\s+/).filter(Boolean) : [];
                  competitionCards.forEach(card => {
                      const status = card.getAttribute('data-status');
                      const nameText = normalize(card.querySelector('h3')?.textContent || '');
                      const matchesFilter = (currentFilter === 'all' || status === currentFilter);
                      const matchesSearch = !tokens.length || tokens.every(t => nameText.includes(t));
                      card.style.display = (matchesFilter && matchesSearch) ? 'block' : 'none';
                  });
              }

              // Ajustado: no cambiar colores originales, solo manejar clase 'active'
              filterButtons.forEach(button => {
                  button.addEventListener('click', function() {
                      currentFilter = this.getAttribute('data-filter');
                      filterButtons.forEach(btn => btn.classList.remove('active'));
                      this.classList.add('active'); // mantiene sus clases originales de color
                      applyFilters();
                  });
              });

              searchInput.addEventListener('input', applyFilters);
              applyFilters();
          });
      </script>
      @endpush
@endsection

