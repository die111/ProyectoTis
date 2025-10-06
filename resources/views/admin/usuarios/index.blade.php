@extends('layouts.app')
@section('title', 'Dashboard Administrador')
@section('content')
    @push('styles')        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    @endpush



    <div class="max-w-7xl mx-auto py-4">

        {{-- Header: título + botones a la derecha --}}
        <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
            <h3 class="text-xl font-semibold">Usuarios</h3>

            <div class="flex gap-2 mb-4">
                <a href="{{ route('admin.formulario-encargado') }}"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                    style="background:#0C3E92">
                    <i class="bi bi-plus-circle"></i> Crear Encargado de Area
                </a>
                <a href="{{ route('admin.formulario-evaluador') }}"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                    style="background:#0C3E92">
                    <i class="bi bi-plus-circle"></i> Crear Evaluador
                </a>
            </div>
        </div>

        {{-- Tarjetas (1 col móvil, 2 tablet, 4 desktop) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            {{-- Encargados de Área --}}
            <a href="?role=responsable_area" class="block group">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32 transition-all duration-200 hover:ring-4 hover:ring-red-600" id="card-encargado">
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
            <a href="?role=evaluador" class="block group">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32 transition-all duration-200 hover:ring-4 hover:ring-red-800" id="card-evaluador">
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
            <a href="#" class="block group">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32 transition-all duration-200 hover:ring-4 hover:ring-green-700" id="card-olimpista">
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
            <a href="?role=activos" class="block group">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32 transition-all duration-200 hover:ring-4 hover:ring-cyan-500" id="card-activo">
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
                {{-- Buscador --}}
                <form action="{{ route('admin.usuarios.index') }}" method="GET" class="flex w-full max-w-3xl items-center gap-3" id="searchForm">
                    <div class="relative flex-1">
                        <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2">
                            <svg class="h-5 w-5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                aria-hidden="true">
                                <circle cx="11" cy="11" r="7" stroke-width="2" />
                                <path d="M20 20l-3.5-3.5" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </span>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar Usuario"
                            class="w-full rounded-lg border border-slate-300 bg-white/95 pl-10 pr-4 py-2.5 text-slate-800 placeholder-slate-400 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20" />
                    </div>
                    <select id="area" name="area" class="appearance-none w-72 rounded-lg border border-slate-300 bg-white/95 px-4 py-2.5 pr-10 text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                        <option value="" selected>Área</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
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
                            <!-- <th scope="col" class="px-6 py-4">ID</th> -->
                            <th scope="col" class="px-6 py-4">Nombre</th>
                            <th scope="col" class="px-6 py-4">Rol</th>
                            <th scope="col" class="px-6 py-4">Apellido Paterno</th>
                            <th scope="col" class="px-6 py-4">Apellido Materno</th>
                            <th scope="col" class="px-6 py-4">Correo</th>
                            <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white/95">
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-lg">No hay usuarios registrados.
                                </td>
                            </tr>
                        @else
                            @foreach($users as $user)
                                <tr class="text-sm text-slate-800 hover:bg-slate-50">
                                    <!-- <td class="px-6 py-3">{{ $user->id }}</td> -->
                                    <td class="px-6 py-3">{{ $user->name }}</td>
                                    <td class="px-6 py-3">{{ $user->role ? $user->role->name : '-' }}</td>
                                    <td class="px-6 py-3">{{ $user->last_name_father }}</td>
                                    <td class="px-6 py-3">{{ $user->last_name_mother }}</td>
                                    <td class="px-6 py-3">{{ $user->email }}</td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.usuarios.edit', $user->id) }}?return={{ urlencode(request()->fullUrl()) }}" class="px-3 py-1.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md transition-colors duration-200" title="Editar">
                                                Editar
                                            </a>
                                            <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" style="display:inline" class="delete-user-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="px-3 py-1.5 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors duration-200 btn-eliminar" title="Eliminar">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                {{-- Paginación --}}
                <div class="px-6 py-4 bg-white/95">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </section>

    @if(session('role'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const role = '{{ session('role') }}';
                if(role === 'responsable_area') {
                    document.getElementById('card-encargado').classList.add('ring-4', 'ring-red-600');
                } else if(role === 'evaluador') {
                    document.getElementById('card-evaluador').classList.add('ring-4', 'ring-red-800');
                } else if(role === 'activos') {
                    document.getElementById('card-activo').classList.add('ring-4', 'ring-cyan-500');
                } else if(role === 'olimpista') {
                    document.getElementById('card-olimpista').classList.add('ring-4', 'ring-green-700');
                }
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const role = params.get('role');
            if(role === 'responsable_area') {
                document.getElementById('card-encargado').classList.add('ring-4', 'ring-red-600');
            } else if(role === 'evaluador') {
                document.getElementById('card-evaluador').classList.add('ring-4', 'ring-red-800');
            } else if(role === 'activos') {
                document.getElementById('card-activo').classList.add('ring-4', 'ring-cyan-500');
            } else if(role === 'olimpista') {
                document.getElementById('card-olimpista').classList.add('ring-4', 'ring-green-700');
            }
            // Búsqueda automática al cambiar área
            document.getElementById('area').addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        });
    </script>

    {{-- Modal para confirmar eliminación --}}
    <div id="modal-eliminar" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 hidden">
        <div class="bg-white rounded-lg shadow-lg p-8 w-full max-w-md text-center relative">
            <h3 class="text-lg font-semibold mb-4">¿Seguro que deseas eliminar este usuario?</h3>
            <video id="michi-video" src="{{ asset('videos/MichiSad.mp4') }}" preload="auto" loop class="mx-auto mb-4 rounded-lg shadow w-48 h-48 object-cover"></video>
            <div class="flex justify-center gap-6 mt-6">
                <button id="cancelar-eliminar" class="px-5 py-2 rounded bg-slate-300 hover:bg-slate-400 text-slate-800 font-semibold">Cancelar</button>
                <button id="confirmar-eliminar" class="px-5 py-2 rounded bg-red-600 hover:bg-red-700 text-white font-semibold">Eliminar</button>
            </div>
            <button id="cerrar-modal" class="absolute top-3 right-3 text-slate-400 hover:text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    <script>
        let formToDelete = null;
        const michiVideo = document.getElementById('michi-video');
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                formToDelete = btn.closest('form');
                document.getElementById('modal-eliminar').classList.remove('hidden');
                if(michiVideo) {
                    michiVideo.currentTime = 0;
                    michiVideo.play();
                }
            });
        });
        document.getElementById('cancelar-eliminar').onclick = function() {
            document.getElementById('modal-eliminar').classList.add('hidden');
            if(michiVideo) michiVideo.pause();
            formToDelete = null;
        };
        document.getElementById('cerrar-modal').onclick = function() {
            document.getElementById('modal-eliminar').classList.add('hidden');
            if(michiVideo) michiVideo.pause();
            formToDelete = null;
        };
        document.getElementById('confirmar-eliminar').onclick = function() {
            if(formToDelete) formToDelete.submit();
        };
    </script>
@endsection
