@extends('layouts.app')

@section('title', 'Editar Fase')

@section('content')
<div class="w-full min-h-screen bg-[#f5f5f7] flex items-center justify-center py-8">
    <div class="w-full max-w-2xl mx-auto bg-white rounded-lg shadow p-8">
        <form action="{{ route('admin.phases.update', $phase->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nombre:</label>
                <input type="text" name="name" id="name" value="{{ old('name', $phase->name) }}" required
                    class="w-full bg-[#f8f9fa] border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#091c47]">
                @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Descripción:</label>
                <textarea name="description" id="description" rows="2"
                    class="w-full bg-[#f8f9fa] border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#091c47]">{{ old('description', $phase->description) }}</textarea>
                @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div>
                <label for="clasificados" class="block text-sm font-semibold text-gray-700 mb-1">Clasificados:</label>
                <input type="number" name="clasificados" id="clasificados" value="{{ old('clasificados', $phase->clasificados) }}" required min="1"
                    class="w-full bg-[#f8f9fa] border border-gray-300 rounded-lg px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#091c47]">
                @error('clasificados')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
            </div>
            <div class="flex justify-center gap-8 mt-8">
                <a href="{{ route('admin.phases.index') }}" class="px-8 py-3 rounded-lg bg-[#d90429] text-white font-semibold text-lg hover:bg-[#a6031f] transition">Cancelar</a>
                <button type="submit" class="px-8 py-3 rounded-lg bg-[#091c47] text-white font-semibold text-lg hover:bg-[#122a5c] transition">Actualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
