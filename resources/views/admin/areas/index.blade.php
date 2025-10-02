@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
<section class="w-full py-6" 
    x-data="{ 
        openCrear: false, 
        openEditar: false,
        area: { id: '', name: '', description: '' } 
    }">

    {{-- Barra de título --}}
    <div class="mx-[5%] mb-6 flex items-center justify-between rounded bg-slate-200/70 px-4 py-3">
        <h1 class="text-2xl font-semibold text-slate-700">Áreas</h1>

        {{-- Botón Crear --}}
        <button @click="openCrear = true"
            class="inline-flex items-center gap-2 rounded-full bg-[#0C204A] px-4 py-2 text-sm font-semibold text-white shadow hover:brightness-110">
            Crear Área
        </button>

        {{-- Modales --}}
        @include('components.modals.crear-area', ['state' => 'openCrear'])
        @include('components.modals.editar-area', ['state' => 'openEditar'])
    </div>

    {{-- Buscador --}}
    <form method="GET" action="{{ route('admin.areas.index') }}"
        class="mx-auto mb-6 flex w-full max-w-2xl items-center gap-3">
        <div class="relative flex-1">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar Área"
                class="w-full rounded-lg border border-slate-300 bg-slate-200/70 pl-10 pr-3 py-2.5 text-sm text-slate-800 placeholder-slate-500 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
        </div>
        <button class="rounded-full bg-[#0C204A] px-5 py-2.5 text-sm font-semibold text-white shadow hover:brightness-110" type="submit">
            Buscar
        </button>
    </form>

    {{-- Tabla de Áreas --}}
    <div class="mx-auto w-full max-w-5xl rounded bg-slate-100 p-6">
        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-600">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wider text-white">
                        <th class="px-4 py-4 text-center"></th>
                        <th class="px-6 py-4">Nombre de Área</th>
                        <th class="px-6 py-4">Descripción</th>
                        <th class="px-6 py-4">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white/95">
                    @forelse($areas as $area)
                        <tr class="text-sm text-slate-800 hover:bg-slate-50 cursor-pointer" onclick="selectRowCheckbox(this)">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" class="area-checkbox" 
                                    data-id="{{ $area->id }}" 
                                    data-name="{{ $area->name }}" 
                                    data-description="{{ $area->description }}"
                                    data-route="{{ route('admin.areas.destroy', $area->id) }}"
                                    data-active="{{ $area->is_active }}"
                                    onclick="onlyOneCheckbox(this); event.stopPropagation();">
                            </td>
                            <td class="px-6 py-3 font-medium">{{ $area->name }}</td>
                            <td class="px-6 py-3">{{ Str::limit($area->description, 90) }}</td>
                            <td class="px-6 py-3 text-center">
                                @if($area->is_active)
                                    <span class="rounded bg-green-100 px-2 py-1 text-xs text-green-700">Activo</span>
                                @else
                                    <span class="rounded bg-red-100 px-2 py-1 text-xs text-red-700">Inactivo</span>
                                @endif
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

    {{-- Acciones Inferiores --}}
    <div class="mt-8 flex items-center justify-center gap-16">
        <form id="bulkActivate" method="POST" action="{{ route('admin.areas.bulk-activate') }}" onsubmit="return submitSelectedIds(this)">
            @csrf
            <input type="hidden" name="ids" id="activateIds">
            <button type="submit" class="rounded-xl bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white shadow">Activar</button>
        </form>

        <form id="bulkDeactivate" method="POST" action="{{ route('admin.areas.bulk-deactivate') }}" onsubmit="return submitSelectedIds(this)">
            @csrf
            <input type="hidden" name="ids" id="deactivateIds">
            <button type="submit" class="rounded-xl bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white shadow">Desactivar</button>
        </form>

        <form id="bulkEdit" method="POST" action="#" @submit.prevent>
            @csrf
            <button type="button"
                @click="
                    const selected = document.querySelector('.area-checkbox:checked');
                    if(selected){
                        area.id = selected.dataset.id;
                        area.name = selected.dataset.name;
                        area.description = selected.dataset.description;
                        openEditar = true;
                    } else {
                        alert('Selecciona un área para editar.');
                    }
                "
                class="rounded-xl bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white shadow">
                Editar
            </button>
        </form>

        <form id="bulkDelete" method="POST" action="" onsubmit="event.preventDefault();">
            @csrf
            @method('DELETE')
            <button type="button"
                onclick="
                    const selected = document.querySelector('.area-checkbox:checked');
                    if(selected){
                        if(confirm('¿Seguro que deseas eliminar esta área?')){
                            const form = document.getElementById('bulkDelete');
                            form.action = selected.dataset.route;
                            form.onsubmit = null; // Permite el envío normal
                            form.submit();
                        }
                    } else {
                        alert('Selecciona un área para eliminar.');
                    }
                "
                class="rounded-xl bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow hover:bg-red-700">
                Eliminar
            </button>
        </form>
    </div>

    {{-- Scripts --}}
    <script>
        function onlyOneCheckbox(checkbox) {
            const checkboxes = document.querySelectorAll('.area-checkbox');
            checkboxes.forEach(cb => { if (cb !== checkbox) cb.checked = false; });
        }
        function selectRowCheckbox(row) {
            const checkbox = row.querySelector('.area-checkbox');
            if (checkbox) {
                checkbox.checked = true;
                onlyOneCheckbox(checkbox);
            }
        }
        function submitSelectedIds(form) {
            const selected = document.querySelector('.area-checkbox:checked');
            if (selected) {
                form.querySelector('input[name="ids"]').value = selected.dataset.id;
                return true;
            } else {
                alert('Selecciona un área para la acción.');
                return false;
            }
        }
    </script>
</section>
@endsection
