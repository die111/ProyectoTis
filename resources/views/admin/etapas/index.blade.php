@extends('layouts.app')

@section('title', 'Fases de la Competición')

@section('content')
    <div class="container mx-auto py-8" x-data="{ modalOpen: false, editModalOpen: null }">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Fases de la Competición</h1>
            <a href="{{ route('admin.etapas.create') }}" class="bg-[#091c47] text-white px-4 py-2 rounded hover:bg-[#122a5c] transition">Agregar Fase</a>
        </div>
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($phases as $phase)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $phase->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $phase->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                <a href="{{ route('admin.etapas.edit', $phase->id) }}" class="bg-[#091c47] text-white px-3 py-1 rounded hover:bg-[#122a5c] transition">Editar</a>
                                <form action="{{ route('admin.etapas.destroy', $phase->id) }}" method="POST"
                                    class="delete-phase-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-[#091c47] text-white px-3 py-1 rounded hover:bg-red-700 transition">Inabilitar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay fases registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                 {{ $phases->links() }}
            </div>
        </div>

        {{-- Modal para crear fase --}}
        <!-- El formulario de creación se maneja en create.blade.php -->

        {{-- Modales de edición para cada fase --}}
        <!-- El formulario de edición se maneja en edit.blade.php -->
    </div>
    </div>
@endsection
