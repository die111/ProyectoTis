@extends('layouts.app')

@section('title', 'Categoria')

@section('content')
<div class="min-h-[80vh] bg-gray-100">
  <div class="w-full px-6 py-10">

    {{-- Modal de notificación de éxito --}}
    @if(session('success'))
      <div id="notification-modal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white rounded-lg text-center overflow-hidden shadow-xl transform transition-all max-w-lg w-full mx-4 p-10">
          <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-white border-4 border-blue-900 mb-6">
              <svg class="h-10 w-10 text-blue-900" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-3">
              ¡Éxito!
            </h3>
            <p class="text-base text-gray-700 mb-8">
              @if(session('success') === 'La categoría ha sido activada exitosamente.')
                Categoría activada correctamente.
              @elseif(session('success') === 'La categoría ha sido desactivada exitosamente.')  
                Categoría desactivada correctamente.
              @else
                {{ session('success') }}
              @endif
            </p>
            <button type="button" onclick="closeNotificationModal()" 
                    class="inline-flex justify-center rounded-md px-10 py-3 bg-[#0B2049] text-base font-semibold text-white hover:brightness-110 focus:outline-none">
              OK
            </button>
          </div>
        </div>
      </div>
    @endif

    {{-- Modal de notificación de error --}}
    @if(session('error'))
      <div id="notification-modal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        
        <div class="relative bg-white rounded-lg text-center overflow-hidden shadow-xl transform transition-all max-w-lg w-full mx-4 p-10">
          <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-white border-4 border-red-600 mb-6">
              <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </div>
            <h3 class="text-2xl font-semibold text-gray-900 mb-3">
              Error
            </h3>
            <p class="text-base text-gray-700 mb-8">
              {{ session('error') }}
            </p>
            <button type="button" onclick="closeNotificationModal()" 
                    class="inline-flex justify-center rounded-md px-10 py-3 bg-red-600 text-base font-semibold text-white hover:bg-red-700 focus:outline-none">
              OK
            </button>
          </div>
        </div>
      </div>
    @endif

    {{-- Encabezado + botón Crear --}}
    <div class="mb-8 flex items-center justify-between">
      <h1 class="text-3xl font-semibold text-slate-700 w-full text-center">Categoria</h1>
      <a href="{{ route('admin.categorias.create') }}"
         class="inline-flex items-center gap-2 rounded-full bg-[#0B2049] px-5 py-2 text-sm font-medium text-white shadow hover:brightness-110 active:scale-[.98]">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
          <path d="M11 11V5a1 1 0 1 1 2 0v6h6a1 1 0 1 1 0 2h-6v6a1 1 0 1 1-2 0v-6H5a1 1 0 1 1 0-2h6z"/>
        </svg>
        Crear
      </a>
    </div>
    {{-- Buscador --}}
    <form method="GET" class="mx-auto mb-10 flex max-w-2xl items-stretch gap-3">
      <div class="relative flex-1">
        <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M10 2a8 8 0 105.293 14.293l3.707 3.707a1 1 0 001.414-1.414l-3.707-3.707A8 8 0 0010 2z" clip-rule="evenodd"/>
          </svg>
        </span>
        <input name="q" value="{{ $q ?? '' }}"
               placeholder="Encuentra la categoría"
               class="w-full rounded-full border border-slate-300 bg-gray-200/70 py-2.5 pl-10 pr-4 text-sm placeholder:text-slate-400 shadow-sm focus:border-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-200" />
      </div>
      <button class="rounded-full bg-[#0B2049] px-5 text-sm font-medium text-white shadow hover:brightness-110">Buscar</button>
    </form>
    {{-- Tabla dentro de panel con scroll --}}
    <div>
      <div class="overflow-x-auto">
        <div class="overflow-hidden rounded-md">
          <table class="w-full border-separate border-spacing-0">
            <thead>
              <tr class="bg-gray-500 text-left text-white">
                <th class="px-5 py-3 text-sm font-semibold w-1/4">Nombre</th>
                <th class="px-5 py-3 text-sm font-semibold w-2/4">Descripción</th>
                <th class="px-5 py-3 text-sm font-semibold w-1/8">Estado</th>
                <th class="px-5 py-3 text-sm font-semibold w-1/8">Acciones</th>
              </tr>
            </thead>
            <tbody class="max-h-72 overflow-y-auto">
              @forelse ($categories as $i => $c)
                <tr class="text-sm {{ $i % 2 === 0 ? 'bg-white' : 'bg-[#d7dde4]' }}">
                  <td class="px-5 py-3 text-sm text-slate-700 w-1/4">{{ $c->nombre ?? 'N/A' }}</td>
                  <td class="px-5 py-3 text-sm text-slate-700 w-2/4">{{ $c->descripcion ?? 'N/A' }}</td>
                  <td class="px-5 py-3 text-sm w-1/8">
                    @if($c->is_active ?? true)
                      <span class="inline-flex px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Activo</span>
                    @else
                      <span class="inline-flex px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">Inactivo</span>
                    @endif
                  </td>
                  <td class="px-5 py-3 text-sm w-1/8">
                    <div class="flex items-center justify-end gap-2">
                      @if($c->is_active ?? true)
                        @if(in_array($c->id, $categoriesInUse ?? []))
                          {{-- Botón deshabilitado cuando está en uso --}}
                          <button type="button"
                                  disabled
                                  title="No se puede desactivar: Esta categoría está siendo utilizada en una competición activa"
                                  class="inline-flex items-center gap-1 rounded-lg px-3 py-1 text-xs font-medium text-white shadow-sm cursor-not-allowed opacity-50"
                                  style="background-color: #DC2626;">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Desactivar
                          </button>
                        @else
                          <form action="{{ route('admin.categorias.deactivate', $c->id) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center gap-1 rounded-lg px-3 py-1 text-xs font-medium text-white shadow-sm hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                    style="background-color: #DC2626;">
                              <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                              </svg>
                              Desactivar
                            </button>
                          </form>
                        @endif
                      @else
                        <form action="{{ route('admin.categorias.activate', $c->id) }}" method="POST" class="inline">
                          @csrf @method('PATCH')
                          <button type="submit"
                                  class="inline-flex items-center gap-1 rounded-lg px-3 py-1 text-xs font-medium text-white shadow-sm hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                  style="background-color: #15803D;">
                            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Activar
                          </button>
                        </form>
                      @endif

                      <a href="{{ route('admin.categorias.edit', $c->id) }}"
                         class="inline-flex items-center gap-1 rounded-lg px-3 py-1 text-xs font-medium text-white shadow-sm hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
                         style="background-color: #091C47;">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                          </path>
                        </svg>
                        Editar
                      </a>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td class="px-5 py-4 text-sm text-slate-500 text-center" colspan="4">No hay categorías disponibles...</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <div class="mt-4">
        {{ $categories->links() }}
      </div>
    </div>
    {{-- Información adicional o acciones globales --}}
    <div class="mt-8 text-center">
      <p class="text-sm text-gray-600">
        Total de categorías: {{ $categories->total() ?? $categories->count() }}
      </p>
    </div>
  </div>
</div>

<style>
  thead tr.bg-gray-500 {
    background: #949BA2 !important;
  }
  thead tr.bg-gray-500 th {
    color: #fff !important;
  }
</style>

<script>
  function closeNotificationModal() {
    const modal = document.getElementById('notification-modal');
    if (modal) {
      modal.remove();
    }
  }

  // Cerrar modal al hacer click en el fondo
  document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('notification-modal');
    if (modal) {
      const background = modal.querySelector('.fixed.inset-0.bg-gray-500');
      if (background) {
        background.addEventListener('click', closeNotificationModal);
      }
    }
  });

  // Cerrar modal con la tecla Escape
  document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
      closeNotificationModal();
    }
  });
</script>
@endsection
