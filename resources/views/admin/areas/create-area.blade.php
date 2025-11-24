@extends('layouts.app')

@section('title', 'Crear Área')

@section('content')
<div class="-mx-4 lg:-mx-6 w-full">
    <div class="px-4 lg:px-6 w-full mx-auto">
        <div class="relative mb-6">
            <a href="{{ route('admin.areas.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 transition absolute right-0">
                <i class="fas fa-arrow-left mr-2"></i> Atrás
            </a>
            <h1 class="text-2xl font-semibold text-center">Crear Área</h1>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            <form action="{{ route('admin.areas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nombre</label>
                    <input type="text" name="name" maxlength="20" required value="{{ old('name') }}"
                        class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-500/50 focus:border-slate-500">
                    @error('name')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Descripción</label>
                    <textarea name="description" rows="3" maxlength="30" required class="mt-1 w-full rounded-md border border-slate-300 px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-slate-500/50 focus:border-slate-500">{{ old('description') }}</textarea>
                    @error('description')<span class="text-red-500 text-sm">{{ $message }}</span>@enderror
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <a href="{{ route('admin.areas.index') }}" class="rounded-lg bg-[#D1D5DB] px-6 py-2 text-sm font-semibold text-black hover:bg-gray-400 transition">Volver</a>
                    <button type="submit"
                        class="rounded-lg bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white hover:brightness-110">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
