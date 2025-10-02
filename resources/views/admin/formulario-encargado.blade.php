{{-- Vista independiente para crear Encargado de Área --}}
@extends('layouts.app')
@section('content')
<div class="container mx-auto py-10">
  <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg overflow-hidden">
    <div class="bg-[#0C3E92] text-white px-6 py-5 flex items-center justify-between">
      <h2 class="text-2xl font-semibold">Crear Encargado de Área</h2>
    </div>
    <div class="bg-gray-200 px-6 py-6">
      <p class="text-lg font-semibold text-gray-800">Los campos con * son obligatorios</p>
      <hr class="border-t border-gray-400 mt-2 mb-6" />
      <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-6">
        @csrf
        <input type="hidden" name="role" value="responsable_area">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
          <!-- Nombre -->
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">Nombre*</label>
            <input id="name" name="name" type="text" required placeholder="Ingrese su nombre"
                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
            @error('name')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>
          <!-- Apellido Paterno -->
          <div>
            <label class="block text-sm font-semibold text-gray-800 mb-1">Apellido Paterno*</label>
            <input type="text" name="last_name_father" placeholder="Ingrese su apellido paterno" required
                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
            @error('last_name_father')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>
          <!-- Apellido Materno -->
          <div>
            <label class="block text-sm font-semibold text-gray-800 mb-1">Apellido Materno</label>
            <input type="text" name="last_name_mother" placeholder="Ingrese su apellido materno"
                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
            @error('last_name_mother')
              <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
          </div>
          <!-- Área -->
          <div class="relative">
            <label for="area_id" class="block text-sm font-semibold text-gray-800 mb-1">Área*</label>
            <select id="area_id" name="area_id" required
                    class="appearance-none w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
              <option value="" disabled selected>Selecciona un área</option>
              @foreach($areas as $area)
                  <option value="{{ $area->id }}">{{ $area->name }}</option>
              @endforeach
            </select>
            <i class="bi bi-chevron-down absolute right-3 top-9 pointer-events-none text-gray-500"></i>
          </div>
          <!-- Código Usuario -->
          <div>
            <label class="block text-sm font-semibold text-gray-800 mb-1">Código Usuario*</label>
            <input type="text" name="user_code" required placeholder="Ingrese el codigo de Usuario"
                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <!-- Contraseña -->
          <div>
            <label for="password" class="block text-sm font-semibold text-gray-800 mb-1">Contraseña*</label>
            <input id="password" name="password" type="password" required placeholder="De14697@"
                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <!-- Unidad Educativa -->
          <div>
            <label class="block text-sm font-semibold text-gray-800 mb-1">Unidad Educativa</label>
            <input type="text" name="school" placeholder="Ingrese la unidad educativa"
                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <!-- Nivel -->
          <div class="relative">
            <label for="level" class="block text-sm font-semibold text-gray-800 mb-1">Nivel</label>
            <select id="level" name="level"
                    class="appearance-none w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
              <option value="" disabled selected>Selecciona un nivel</option>
              <option value="Primaria">Primaria</option>
              <option value="Secundaria">Secundaria</option>
              <option value="Preuniversitario">Preuniversitario</option>
            </select>
            <i class="bi bi-chevron-down absolute right-3 top-9 pointer-events-none text-gray-500"></i>
          </div>
          <div class="hidden md:block"></div>
          <!-- Email (col-span-3) -->
          <div class="md:col-span-3">
            <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">Dirección de correo electrónico*</label>
            <input id="email" name="email" type="email" required placeholder="ingrese un correo electronico valido"
                   class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
        </div>
        <div class="pt-1">
          <div class="w-full flex items-center justify-center gap-4">
            <a href="{{ route('admin.usuarios.index') }}" class="px-8 py-3 rounded-full font-semibold text-white bg-gray-500 hover:bg-gray-700 transition flex items-center gap-2">
              <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button type="submit"
                    class="px-10 py-3 rounded-full font-semibold text-white bg-[#0C3E92] hover:opacity-95 transition">
              Guardar y crear Encargado
            </button>
          </div>
          <div class="w-2/3 md:w-1/2 mx-auto h-1 rounded bg-gray-300 mt-2"></div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
