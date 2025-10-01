@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('estilos/GestionUsuario.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @endpush



    <div class="max-w-7xl mx-auto py-4">

        {{-- Header: título + botones a la derecha --}}
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h3 class="text-xl font-semibold">Usuarios</h3>

            <div x-data="{ openEval:false, openEnc:false, modalTitle:'' }">
                <div class="flex gap-2 mb-4">

                    <button @click="openEnc = true; modalTitle = 'Crear Encargado de Área'"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                        style="background:#0C3E92">
                        <i class="bi bi-plus-circle"></i> Crear Encargado de Area
                    </button>

                    <button @click="openEval = true; modalTitle = 'Crear Evaluador'"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                        style="background:#0C3E92">
                        <i class="bi bi-plus-circle"></i> Crear Evaluador
                    </button>

                </div>

                {{-- Pasa el nombre del estado que controlará cada modal --}}
                @include('components.modals.formulario-evaluador', ['state' => 'openEval', 'areas' => $areas])
                @include('components.modals.formulario-encargado', ['state' => 'openEnc', 'areas' => $areas])
            </div>



        </div>

        {{-- Tarjetas (1 col móvil, 2 tablet, 4 desktop) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- Encargados de Área --}}
            <a href="?role=responsable_area" class="block">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-people-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Encargados de Área</span>
                            <span class="text-lg">{{ $encargados_count ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-red-600 rounded-b-lg"></div>
                </div>
            </a>

            {{-- Evaluadores --}}
            <a href="?role=evaluador" class="block">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-clipboard-check-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Evaluadores</span>
                            <span class="text-lg">{{ $evaluadores_count ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-red-800 rounded-b-lg"></div>
                </div>
            </a>

            {{-- Olimpistas --}}
            <a href="#" class="block">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-mortarboard-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Olimpistas</span>
                            <span class="text-lg">0</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-green-700 rounded-b-lg"></div>
                </div>
            </a>

            {{-- Usuarios Activos --}}
            <a href="?role=activos" class="block">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-person-lines-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Usuarios Activos</span>
                            <span class="text-lg">{{ $usuarios_activos_count ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-cyan-500 rounded-b-lg"></div>
                </div>
            </a>

        </div>

    </div>


    <section class="min-h-[80vh] w-full bg-slate-400/70">
        <div class="mx-auto max-w-6xl px-6 py-10">

            {{-- Filtro superior --}}
            <div class="flex flex-col items-center gap-6">
                {{-- Select de Área --}}
                <div>
                    <label for="area" class="sr-only">Área</label>
                    <div class="relative">
                        <select id="area" name="area"
                            class="appearance-none w-72 rounded-lg border border-slate-300 bg-white/95 px-4 py-2.5 pr-10 text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                            <option selected>Area</option>
                            {{-- Ejemplos; reemplaza con tus áreas --}}
                            <option value="fisica">Física</option>
                            <option value="quimica">Química</option>
                            <option value="biologia">Biología</option>
                        </select>
                        <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2">
                            <svg class="h-4 w-4 text-slate-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                </div>

                {{-- Buscador --}}
                <form action="#" method="GET" class="flex w-full max-w-3xl items-center gap-3">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                aria-hidden="true">
                                <circle cx="11" cy="11" r="7" stroke-width="2" />
                                <path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <input type="text" name="q" placeholder="Buscar Usuario"
                            class="w-full rounded-lg border border-slate-300 bg-white/95 pl-10 pr-4 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20" />
                    </div>

                    <button type="submit"
                        class="rounded-full bg-[#0C204A] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:brightness-110 active:translate-y-[1px]">
                        Buscar
                    </button>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="mt-10 overflow-hidden rounded-lg shadow-sm ring-1 ring-slate-300/60">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-600">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-white">
                            <th scope="col" class="px-6 py-4">ID</th>
                            <th scope="col" class="px-6 py-4">Nombre</th>
                            <th scope="col" class="px-6 py-4">Apellido Paterno</th>
                            <th scope="col" class="px-6 py-4">Apellido Materno</th>
                            <th scope="col" class="px-6 py-4">Correo</th>
                            <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white/95">
                        @if(empty($users) || count($users) === 0)
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-lg">No hay usuarios registrados.
                                </td>
                            </tr>
                        @else
                            @foreach($users as $user)
                                <tr class="text-sm text-slate-800 hover:bg-slate-50">
                                    <td class="px-6 py-3">{{ $user->id }}</td>
                                    <td class="px-6 py-3">{{ $user->name }}</td>
                                    <td class="px-6 py-3">{{ $user->last_name_father }}</td>
                                    <td class="px-6 py-3">{{ $user->last_name_mother }}</td>
                                    <td class="px-6 py-3">{{ $user->email }}</td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.usuarios.edit', $user->id) }}" class="rounded p-1.5 hover:bg-slate-100" title="Editar" aria-label="Editar">
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1.003 1.003 0 0 0 0-1.42l-2.34-2.34a1.003 1.003 0 0 0-1.42 0l-1.83 1.83 3.75 3.75 1.84-1.82z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded p-1.5 hover:bg-slate-100" title="Eliminar" aria-label="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar este usuario?');">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                                        <path d="M6 7h12v2H6zM9 9h6l-1 11H10L9 9zm3-6a2 2 0 0 1 2 2h3v2H6V5h3a2 2 0 0 1 2-2z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
