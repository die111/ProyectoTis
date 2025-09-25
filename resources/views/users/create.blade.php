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

            <div x-data="{ open: false, modalTitle: '' }">

                {{-- Botones --}}
                <div class="flex gap-2 mb-4">
                    <button @click="open = true; modalTitle = 'Crear :v'"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                        style="background:#0C3E92">
                        <i class="bi bi-plus-circle"></i>
                        crea un :v
                    </button>
                    <button @click="open = true; modalTitle = 'Crear Encargado de Área'"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                        style="background:#0C3E92">
                        <i class="bi bi-plus-circle"></i>
                        Crear Encargado de Área
                    </button>

                    <button @click="open = true; modalTitle = 'Crear Evaluador'"
                        class="inline-flex items-center gap-2 rounded-full px-4 py-2 font-semibold text-white hover:opacity-95"
                        style="background:#0C3E92">
                        <i class="bi bi-plus-circle"></i>
                        Crear Evaluador
                    </button>
                </div>

                {{-- Modal --}}
                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-transition>
                    <div @click.away="open = false" class="bg-white rounded-lg shadow-xl max-w-lg w-full p-6 relative">

                        {{-- Botón cerrar --}}
                        <button @click="open = false" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                            <i class="bi bi-x-lg"></i>
                        </button>

                        {{-- Título dinámico --}}
                        <h2 class="text-lg font-semibold mb-4" x-text="modalTitle"></h2>

                        {{-- Formulario --}}
                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" id="name" name="name" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Correo
                                    Electrónico</label>
                                <input type="email" id="email" name="email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                                <input type="password" id="password" name="password" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            </div>

                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700">Area</label>
                                <select id="role" name="role" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="admin">Admin</option>
                                    <option value="responsable_area">Responsable de Área</option>
                                    <option value="evaluador">Evaluador</option>
                                    <option value="coordinador">Coordinador</option>
                                </select>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                                    Crear Usuario
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

        {{-- Tarjetas (1 col móvil, 2 tablet, 4 desktop) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- Encargados de Área --}}
            <a href="#" class="block">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-people-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Encargados de Área</span>
                            <span class="text-lg">0</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-red-600 rounded-b-lg"></div>
                </div>
            </a>

            {{-- Evaluadores --}}
            <a href="#" class="block">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-clipboard-check-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Evaluadores</span>
                            <span class="text-lg">0</span>
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
            <a href="#" class="block">
                <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative h-32">
                    <div class="flex items-center justify-center gap-3 h-full">
                        <i class="bi bi-person-lines-fill text-3xl"></i>
                        <div class="flex flex-col items-center">
                            <span class="font-semibold">Usuarios Activos</span>
                            <span class="text-lg">0</span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 h-2 bg-cyan-500 rounded-b-lg"></div>
                </div>
            </a>

        </div>

    </div>





























    {{--
    <div class="container">
        <h2>Crear Nuevo Usuario</h2>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Rol</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="responsable_area">Responsable de Área</option>
                    <option value="evaluador">Evaluador</option>
                    <option value="coordinador">Coordinador</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </form>
    </div> --}}
@endsection