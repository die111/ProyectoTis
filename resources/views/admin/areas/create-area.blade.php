@extends('layouts.app')

@section('title', 'Crear Área')

@section('content')
<div class="container mx-auto py-8 px-8">
    <div class="max-w-lg w-full mx-auto rounded-lg bg-white p-6 shadow-lg">
        <div class="border-b pb-3">
            <h2 class="text-center text-lg font-semibold text-slate-700">Crear Área</h2>
        </div>
        <form action="{{ route('admin.areas.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Nombre</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Descripción</label>
                <textarea name="description" rows="3" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20">{{ old('description') }}</textarea>
            </div>
            <div class="flex justify-end gap-2 pt-4">
                <a href="{{ url()->previous() }}" class="rounded-lg bg-[#D1D5DB] px-6 py-2 text-sm font-semibold text-black hover:bg-gray-400 transition">Volver</a>
                <button type="submit"
                    class="rounded-lg bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white hover:brightness-110">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
