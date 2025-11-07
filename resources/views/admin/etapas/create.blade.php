@extends('layouts.app')

@section('title', 'Agregar Fase')

@section('content')
<div class="container mx-auto py-8 px-32"> <!-- Doble margen lateral -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold w-full text-center">Agregar Fase</h1> <!-- Centrado el título -->
    </div>
    <div class="p-6 w-full"> <!-- Formulario extendido a toda la pantalla -->
        <form action="{{ route('admin.phases.store') }}" method="POST">
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
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.phases.index') }}" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 transition">Cancelar</a>
                <button type="submit" class="bg-[#091c47] text-white px-4 py-2 rounded hover:bg-blue-700 transition">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
