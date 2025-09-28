@extends('layouts.app')

@section('title', 'Agregar Fase')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Agregar Fase</h1>
    <div class="bg-white shadow rounded-lg p-6 max-w-lg mx-auto">
        <form action="{{ route('admin.etapas.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre</label>
                <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" value="{{ old('name') }}" required>
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-semibold mb-2">Descripción</label>
                <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">{{ old('description') }}</textarea>
                @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label for="start_date" class="block text-gray-700 font-semibold mb-2">Fecha de Inicio</label>
                <input type="date" name="start_date" id="start_date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" value="{{ old('start_date') }}" required>
                @error('start_date')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 font-semibold mb-2">Fecha de Fin</label>
                <input type="date" name="end_date" id="end_date" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" value="{{ old('end_date') }}" required>
                @error('end_date')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.etapas.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 transition">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
