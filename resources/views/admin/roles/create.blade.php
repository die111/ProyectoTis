@extends('layouts.app')
@section('title', 'Crear Rol')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-lg shadow p-8 mt-8">
    <h2 class="text-2xl font-bold mb-6 text-center">Crear Rol</h2>
    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Nombre del Rol <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" class="w-full border border-gray-300 rounded px-3 py-2" value="{{ old('name') }}" required>
            @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="mb-4">
            <label for="description" class="block text-gray-700 font-semibold mb-2">Descripción</label>
            <textarea name="description" id="description" rows="3" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('description') }}</textarea>
            @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
        </div>
        <div class="flex justify-center gap-4 mt-8">
            <a href="{{ route('admin.roles.index') }}" class="px-6 py-2 rounded bg-gray-300 text-gray-700 font-semibold hover:bg-gray-400">Cancelar</a>
            <button type="submit" class="px-6 py-2 rounded bg-[#091c47] text-white font-semibold hover:bg-[#122a5c]">Crear Rol</button>
        </div>
    </form>
</div>
@endsection
