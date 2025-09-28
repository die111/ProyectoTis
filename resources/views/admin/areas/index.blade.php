@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
    <section class="w-full py-6">
        {{-- Barra de título --}}
        <div x-data="{ open: false }"
            class="mx-[5%] mb-6 flex items-center justify-center rounded bg-slate-200/70 px-4 py-3">
            <h1 class="text-2xl font-semibold text-slate-700">Áreas</h1>

            <button @click="open = true"
                class="ml-auto inline-flex items-center gap-2 rounded-full bg-[#0C204A] px-4 py-2 text-sm font-semibold text-white shadow hover:brightness-110">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z" />
                </svg>
                Crear Área
            </button>

            {{-- Modal flotante --}}
            @include('components.modals.crear-area')
        </div>




        {{-- Buscador --}}
        <form method="GET" action="{{ route('admin.areas.index') }}"
            class="mx-auto mb-6 flex w-full max-w-2xl items-center gap-3">
            <div class="relative flex-1">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                    <svg class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="11" cy="11" r="7" stroke-width="2" />
                        <path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar Área"
                    class="w-full rounded-lg border border-slate-300 bg-slate-200/70 pl-10 pr-3 py-2.5 text-sm text-slate-800 placeholder-slate-500 focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
            </div>
            <button
                class="rounded-full bg-[#0C204A] px-5 py-2.5 text-sm font-semibold text-white shadow hover:brightness-110"
                type="submit">
                Buscar
            </button>
        </form>

        {{-- Panel tabla --}}
        <div class="mx-auto w-full max-w-5xl rounded bg-slate-100 p-6">
            <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-600">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-white">
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nombre de Área</th>
                            <th class="px-6 py-4">Descripción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white/95">
                        @if(empty($areas) || count($areas) === 0)
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-400 text-lg">No hay áreas
                                    registradas.</td>
                            </tr>
                        @else
                            @foreach($areas as $area)
                                <tr class="text-sm text-slate-800 hover:bg-slate-50">
                                    <td class="px-6 py-3">{{ $area->id }}</td>
                                    <td class="px-6 py-3 font-medium">{{ $area->name }}</td>
                                    <td class="px-6 py-3">{{ Str::limit($area->description, 90) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                {{-- Paginación --}}
                <div class="flex items-center justify-end gap-2 px-4 py-3">
                    {{ $areas->onEachSide(1)->links() }}
                </div>
            </div>
        </div>

        {{-- Acciones inferiores --}}
        <div class="mt-8 flex items-center justify-center gap-16">
            <form id="bulkActivate" method="POST" action="#">
                @csrf
                <input type="hidden" name="ids" id="activateIds">
                <button type="button"
                    class="rounded-xl bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white shadow disabled:opacity-40">
                    Activar
                </button>
            </form>

            <form id="bulkDeactivate" method="POST" action="#">
                @csrf
                <input type="hidden" name="ids" id="deactivateIds">
                <button type="button"
                    class="rounded-xl bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white shadow disabled:opacity-40">
                    Desactivar
                </button>
            </form>

            <form id="bulkEdit" method="POST" action="#">
                @csrf
                <input type="hidden" name="ids" id="editIds">
                <button type="button"
                    class="rounded-xl bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white shadow disabled:opacity-40">
                    Editar
                </button>
            </form>
        </div>
    </section>
@endsection