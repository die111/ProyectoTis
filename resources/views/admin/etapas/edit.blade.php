@extends('layouts.app')

@section('title', 'Editar Fase')

@section('content')
<div class="container mx-auto py-8 px-32">
    <div class="relative mb-6">
        <a href="{{ route('admin.phases.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition absolute right-0">
            <i class="fas fa-arrow-left mr-2"></i> Atrás
        </a>
        <h1 class="text-2xl font-bold text-center">Editar Fase</h1>
    </div>
    <div class="p-6 w-full">
        <form action="{{ route('admin.phases.update', $phase->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre</label>
                <input type="text" name="name" id="name" maxlength="20" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400" value="{{ old('name', $phase->name) }}" required>
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-semibold mb-2">Descripción</label>
                <textarea name="description" id="description" rows="3" maxlength="30" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">{{ old('description', $phase->description) }}</textarea>
                @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.phases.index') }}" class="bg-gray-300 text-black px-4 py-2 rounded hover:bg-gray-400 transition">Cancelar</a>
                <button type="submit" class="bg-[#091c47] text-white px-4 py-2 rounded hover:bg-blue-700 transition">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
