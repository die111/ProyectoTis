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
                <a href="{{ route('admin.formulario-usuario') }}"
                    class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                    style="background:#091C47">
                    <i class="bi bi-person-plus"></i> Crear Usuario
                </a>
            </div>
        </div>

        {{-- Tarjetas (1 col móvil, 2 tablet, 2 desktop) --}}
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
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

            {{-- Usuarios Inactivos --}}
            <a href="?role=inactivos" class="block group">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32 transition-all duration-200 hover:ring-4 hover:ring-gray-500" id="card-inactivo">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-person-dash-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Usuarios Inactivos</span>
                            <span class="text-lg">{{ $usuarios_inactivos_count ?? 0 }}</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-gray-500 rounded-b-lg"></div>
                </div>
            </a>
        </div>

    </div>


    <section class="min-h-[80vh] w-full bg-white">
    <div class="mx-auto w-full max-w-full px-6 py-10">

            {{-- Filtro superior --}}
            <div class="flex flex-col items-center gap-6">
                {{-- Buscador --}}
                <form action="{{ route('admin.usuarios.index') }}" method="GET" class="flex w-full max-w-4xl items-center gap-3" id="searchForm">
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
                    <select id="role_id" name="role_id" class="appearance-none w-56 rounded-lg border border-slate-300 bg-white/95 px-4 py-2.5 pr-10 text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                        <option value="" selected>Todos los Roles</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name == 'olimpista' ? 'Estudiante' : ucfirst(str_replace('_', ' ', $role->name)) }}
                            </option>
                        @endforeach
                    </select>
                    <select id="area" name="area" class="appearance-none w-56 rounded-lg border border-slate-300 bg-white/95 px-4 py-2.5 pr-10 text-slate-800 shadow-sm focus:border-slate-500 focus:outline-none focus:ring-2 focus:ring-slate-500/20">
                        <option value="" selected>Todas las Áreas</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ request('area') == $area->id ? 'selected' : '' }}>{{ $area->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="rounded-full bg-[#091C47] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:brightness-110 active:translate-y-[1px]">
                        Buscar
                    </button>
                </form>
            </div>

            {{-- Tabla --}}
            <div class="mt-10 w-full overflow-x-auto rounded-lg shadow-sm ring-1 ring-slate-300/60">
                <table class="min-w-full w-full divide-y divide-slate-200">
                    <thead class="bg-slate-600">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wider text-white">
                            <th scope="col" class="px-6 py-4">Nombre</th>
                            <th scope="col" class="px-6 py-4">Apellido Paterno</th>
                            <th scope="col" class="px-6 py-4">Apellido Materno</th>
                            <th scope="col" class="px-6 py-4">Rol</th>
                            <th scope="col" class="px-6 py-4">Nivel</th>
                            <th scope="col" class="px-6 py-4">Estado</th>
                            <th scope="col" class="px-6 py-4 text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 bg-white/95">
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-slate-400 text-lg">No hay usuarios registrados.
                                </td>
                            </tr>
                        @else
                            @foreach($users as $i => $user)
                                <tr class="text-sm text-slate-800 hover:bg-slate-50 {{ $i % 2 === 0 ? 'bg-white' : 'bg-[#d7dde4]' }}">
                                    <td class="px-6 py-3">{{ $user->name }}</td>
                                    <td class="px-6 py-3">{{ $user->last_name_father }}</td>
                                    <td class="px-6 py-3">{{ $user->last_name_mother }}</td>
                                    <td class="px-6 py-3">{{ $user->role ? $user->role->name : '-' }}</td>
                                    <td class="px-6 py-3">{{ $user->level ?? '-' }}</td>
                                    <td class="px-6 py-3">
                                        @if($user->is_active)
                                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Activo</span>
                                        @else
                                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route('admin.usuarios.destroy', $user->id) }}" method="POST" style="display:inline" class="deactivate-user-form">
                                                @csrf
                                                @method('DELETE')
                                                @if($user->is_active)
                                                    <button type="submit" class="px-2 py-1 text-xs font-medium text-white bg-red-600 hover:bg-red-700 rounded-full transition-colors duration-200" title="Desactivar">
                                                        Desactivar
                                                    </button>
                                                @else
                                                    <button type="submit" class="px-2 py-1 text-xs font-medium text-white bg-green-700 hover:brightness-110 rounded-full transition-colors duration-200" title="Activar">
                                                        Activar
                                                    </button>
                                                @endif
                                            </form>
                                            <a href="{{ route('admin.usuarios.edit', $user->id) }}?return={{ urlencode(request()->fullUrl()) }}" class="px-2 py-1 text-xs font-medium text-white bg-[#091C47] hover:brightness-110 rounded-full transition-colors duration-200" title="Editar">
                                                Editar
                                            </a>
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
                if(role === 'activos') {
                    document.getElementById('card-activo').classList.add('ring-4', 'ring-cyan-500');
                } else if(role === 'inactivos') {
                    document.getElementById('card-inactivo').classList.add('ring-4', 'ring-gray-500');
                }
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const role = params.get('role');
            if(role === 'activos') {
                document.getElementById('card-activo').classList.add('ring-4', 'ring-cyan-500');
            } else if(role === 'inactivos') {
                document.getElementById('card-inactivo').classList.add('ring-4', 'ring-gray-500');
            }
            // Búsqueda automática al cambiar área o rol
            document.getElementById('area').addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
            document.getElementById('role_id').addEventListener('change', function() {
                document.getElementById('searchForm').submit();
            });
        });
    </script>

    {{-- Notificación de éxito tipo modal flotante centrada --}}
    @if(session('success'))
        <div id="success-modal-overlay" class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" onclick="document.getElementById('success-modal-overlay').remove()">
            <div class="bg-white rounded-2xl shadow-2xl px-10 py-10 flex flex-col items-center max-w-md w-full animate-fade-in-modal relative" onclick="event.stopPropagation()">
                <svg xmlns="http://www.w3.org/2000/svg" class="mb-6" width="100" height="100" viewBox="0 0 100 100" fill="none">
                    <circle cx="50" cy="50" r="42" stroke="#091C47" stroke-width="6" fill="white"/>
                    <path d="M32 52L46 66L68 38" stroke="#091C47" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="text-center text-2xl font-normal text-black mb-8" style="font-family: 'Montserrat', Arial, sans-serif;">
                    {{ session('success') }}
                </div>
                <button onclick="document.getElementById('success-modal-overlay').remove()" class="px-6 py-2 rounded-xl bg-[#091C47] text-white text-base font-medium hover:brightness-110 transition-all">Ok</button>
            </div>
        </div>
        <style>
            @keyframes fade-in-modal { from { opacity: 0; transform: scale(0.95);} to { opacity: 1; transform: scale(1);} }
            .animate-fade-in-modal { animation: fade-in-modal 0.3s; }
        </style>
        <script>
            setTimeout(function() {
                var modal = document.getElementById('success-modal-overlay');
                if(modal) modal.remove();
            }, 3500);
        </script>
    @endif

    <style>
        thead.bg-slate-600, thead.bg-slate-600 tr, thead.bg-slate-600 th, thead tr.text-white {
            background: #949BA2 !important;
            color: #fff !important;
        }
    </style>
@endsection
