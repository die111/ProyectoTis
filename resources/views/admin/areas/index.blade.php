@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
    <section class="w-full py-6" x-data="{ 
                    openCrear: false, 
                    openEditar: false,
                    area: { id: '', name: '', description: '' } 
                }">

        {{-- Barra de título --}}
        <div class="mx-[5%] mb-6 flex items-center justify-center px-4 py-3">
            <h1 class="text-2xl font-semibold text-slate-700 text-center w-full">Áreas</h1>

            {{-- Botón Crear --}}
            <a href="{{ route('admin.areas.create') }}"
                class="create-btn btn-pressable"
                style="display: flex; align-items: center; gap: 10px; background: #091c47; color: #fff; padding: 10px 18px; border-radius: 15px; font-family: 'Ubuntu',sans-serif; font-size: 16px; position: absolute; right: 2rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
                  <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
                </svg>
                <span>Crear Área</span>
            </a>

            {{-- Modales --}}
            {{-- @include('components.modals.crear-area', ['state' => 'openCrear']) --}}
            @include('components.modals.editar-area', ['state' => 'openEditar'])
        </div>

        {{-- Buscador --}}
        <form method="GET" action="{{ route('admin.areas.index') }}"
            class="mx-auto mb-6 flex w-full items-center gap-3" onsubmit="return flexibleAreaSearch(event)">
            <div class="relative flex-1">
                <input type="text" name="q" id="areaSearchInput" value="{{ request('q') }}" placeholder="Buscar Área"
                    class="w-full rounded-lg border border-slate-300 bg-slate-200/70 pl-10 pr-3 py-2.5 text-sm text-slate-800 placeholder-slate-500 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
            </div>
            <button
                class="rounded-full bg-[#0C204A] px-5 py-2.5 text-sm font-semibold text-white shadow hover:brightness-110"
                type="submit">
                Buscar
            </button>
        </form>


        {{-- Tabla de Áreas --}}
        <div class="-mx-4 lg:-mx-6 w-full">
            <div class="px-4 lg:px-6 w-full rounded bg-slate-100 p-6">
                <div class="overflow-auto rounded-lg border border-slate-200 bg-white shadow-sm w-full">
                    <table class="w-full divide-y divide-slate-200">
                    <thead style="background-color: #949BA2;">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-white">
                            <th class="px-6 py-4">Nombre de Área</th>
                            <th class="px-6 py-4">Descripción</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white/95">
                        @forelse($areas as $i => $area)
                            <tr class="text-sm text-slate-800 hover:bg-slate-50 cursor-pointer area-row {{ $i % 2 === 0 ? 'bg-white' : 'bg-[#d7dde4]' }}"
                                data-route="{{ route('admin.areas.destroy', $area->id) }}" data-id="{{ $area->id }}"
                                data-name="{{ $area->name }}" data-description="{{ $area->description }}"
                                data-active="{{ $area->is_active }}" onclick="selectRow(this)">
                                <td class="px-6 py-3 font-medium">{{ $area->name }}</td>
                                <td class="px-6 py-3">{{ Str::limit($area->description, 90) }}</td>
                                <td class="px-6 py-3 text-center">
                                    @if($area->is_active)
                                        <span class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($area->is_active)
                                            @php
                                                $isInUse = $area->isInUse();
                                            @endphp
                                            <form method="POST" action="{{ route('admin.areas.bulk-deactivate') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="ids" value="{{ $area->id }}">
                                                <button type="submit"
                                                    {{ $isInUse ? 'disabled' : '' }}
                                                    class="inline-flex items-center gap-1 rounded-lg px-3 py-1 text-xs font-medium text-white shadow-sm hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2 {{ $isInUse ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                    style="background-color: #DC2626;"
                                                    title="{{ $isInUse ? 'No se puede desactivar porque está en uso' : '' }}">
                                                    Desactivar
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.areas.bulk-activate') }}" class="inline">
                                                @csrf
                                                <input type="hidden" name="ids" value="{{ $area->id }}">
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg px-3 py-1 text-xs font-medium text-white shadow-sm hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                                    style="background-color: #15803D;">
                                                    Activar
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('admin.areas.edit', $area->id) }}"
                                           class="inline-flex items-center gap-1 rounded-lg px-3 py-1 text-xs font-medium text-white shadow-sm hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-offset-2"
                                           style="background-color: #091C47;">
                                            Editar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-lg">
                                    No hay áreas registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Paginación --}}
                <div class="flex items-center justify-end gap-2 px-4 py-3">
                    {{ $areas->onEachSide(1)->links() }}
                </div>
            </div>
        </div>



        {{-- Scripts --}}
        <script>
            function selectRow(row) {
                // Remover selección previa
                const rows = document.querySelectorAll('.area-row');
                rows.forEach(r => {
                    r.classList.remove('selected', 'bg-blue-100', 'border-l-4', 'border-blue-500');
                });

                // Agregar selección a la fila actual
                row.classList.add('selected', 'bg-blue-100', 'border-l-4', 'border-blue-500');
            }

            function submitSelectedIds(form) {
                const selected = document.querySelector('.area-row.selected');
                if (selected) {
                    form.querySelector('input[name="ids"]').value = selected.dataset.id;
                    return true;
                } else {
                    alert('Selecciona un área para la acción.');
                    return false;
                }
            }

            function flexibleAreaSearch(event) {
                event.preventDefault();
                const input = document.getElementById('areaSearchInput');
                const query = removeDiacritics(input.value.trim().toLowerCase());
                const rows = document.querySelectorAll('.area-row');
                let found = false;
                rows.forEach(row => {
                    const name = removeDiacritics(row.querySelector('td:nth-child(1)').textContent.toLowerCase());
                    const desc = removeDiacritics(row.querySelector('td:nth-child(2)').textContent.toLowerCase());
                    // Permite buscar por palabras separadas, no solo por frase exacta
                    const queryWords = query.split(/\s+/).filter(Boolean);
                    const matches = queryWords.every(word => name.includes(word) || desc.includes(word));
                    row.classList.toggle('hidden', !matches);
                    if (matches) found = true;
                });
                if (!found && query.length > 0) {
                    // Si no hay coincidencias, mostrar todas las filas
                    rows.forEach(row => row.classList.remove('hidden'));
                }
                return false;
            }

            function removeDiacritics(str) {
                return str.normalize('NFD').replace(/\p{Diacritic}/gu, '');
            }
        </script>
    </section>
@endsection