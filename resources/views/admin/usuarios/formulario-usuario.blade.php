@extends('layouts.app')

@section('content')
<div class="w-full py-6 px-0">
  <div class="w-full max-w-full">
    <!-- Header  -->
    <div class="flex items-center justify-between mb-6 px-6">
      <a href="{{ $return ?? route('admin.usuarios.index') }}" class="text-gray-700 hover:text-gray-900 transition" title="Volver">
        <i class="bi bi-arrow-left-circle text-3xl"></i>
      </a>
      <h1 class="text-2xl font-bold w-full text-center">{{ isset($user) ? 'Editar Usuario' : 'Crear Usuario' }}</h1>
      <div class="w-12"></div>
    </div>
    <!-- Body -->
    <div class="p-6 w-full">
      <p class="text-lg font-semibold text-gray-800">Los campos con * son obligatorios</p>
      <hr class="border-t border-gray-400 mt-2 mb-6" />
      <form action="{{ isset($user) ? route('admin.usuarios.update', $user->id) : route('admin.usuarios.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($user))
          @method('PUT')
          <!-- Campo oculto para la URL de retorno -->
          <input type="hidden" name="return" value="{{ $return ?? route('admin.usuarios.index') }}">
        @endif
        <!-- Rol, Código Usuario y Contraseña en la parte superior -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
          <div class="relative">
            <label for="role_id" class="block text-sm font-semibold text-gray-800 mb-1">Rol*</label>
            <select id="role_id" name="role_id" required
              class="appearance-none w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
              <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Selecciona un rol</option>
              @foreach($roles as $rol)
                <option value="{{ $rol->id }}" {{ (isset($user) && $user->role_id == $rol->id) ? 'selected' : '' }}>{{ $rol->name }}</option>
              @endforeach
            </select>
            <i class="bi bi-chevron-down absolute right-3 top-9 pointer-events-none text-gray-500"></i>
          </div>
          <div>
            <label for="user_code" class="block text-sm font-semibold text-gray-800 mb-1">Código Usuario*</label>
            <input id="user_code" type="text" name="user_code" required placeholder="Ingrese el código de Usuario"
              value="{{ old('user_code', isset($user) ? $user->user_code : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <div class="relative">
            <label for="password_user" class="block text-sm font-semibold text-gray-800 mb-1">Contraseña{{ isset($user) ? ' (opcional)' : '*' }}</label>
            <input id="password_user" name="password" type="password" {{ !isset($user) ? 'required' : '' }} minlength="8"
              pattern="^(?=.*[A-Z])(?=.*[^A-Za-z0-9]).{8,}$"
              title="La contraseña debe tener al menos 8 caracteres, una mayúscula y un caracter especial."
              placeholder="{{ isset($user) ? 'Dejar vacío para mantener la actual' : 'Ej: De14697@' }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-12 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
            <!-- botón mostrar/ocultar -->
            <button type="button" id="togglePasswordUser"
              class="absolute right-3 top-[2.85rem] -translate-y-1/2 p-1 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
              aria-label="Mostrar contraseña" aria-pressed="false">
              <svg id="icon-eye-user" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z" />
                <circle cx="12" cy="12" r="3" />
              </svg>
              <svg id="icon-eye-off-user" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 3l18 18M10.6 10.6A3 3 0 0113.4 13.4M9.9 4.24A10.9 10.9 0 0112 4c6.5 0 10 8 10 8a18.5 18.5 0 01-4.22 5.28M6.1 6.1A18.5 18.5 0 002 12s3.5 7 10 7a10.9 10.9 0 004.06-.76" />
              </svg>
            </button>
          </div>
        </div>
        <!-- Nombre y Apellido Paterno en una fila -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
          <div>
            <label for="name" class="block text-sm font-semibold text-gray-800 mb-1">Nombre*</label>
            <input id="name" name="name" type="text" required placeholder="Ingrese su nombre"
              value="{{ old('name', isset($user) ? $user->name : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <div>
            <label for="last_name_father" class="block text-sm font-semibold text-gray-800 mb-1">Apellido Paterno*</label>
            <input id="last_name_father" type="text" name="last_name_father" required placeholder="Ingrese su apellido paterno"
              value="{{ old('last_name_father', isset($user) ? $user->last_name_father : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
        </div>
        <!-- Apellido Materno, Carnet de Identidad y Fecha de Nacimiento en una fila -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
          <div>
            <label for="last_name_mother" class="block text-sm font-semibold text-gray-800 mb-1">Apellido Materno</label>
            <input id="last_name_mother" type="text" name="last_name_mother" placeholder="Ingrese su apellido materno"
              value="{{ old('last_name_mother', isset($user) ? $user->last_name_mother : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <div>
            <label for="ci" class="block text-sm font-semibold text-gray-800 mb-1">Carnet de Identidad*</label>
            <input id="ci" name="ci" type="text" required placeholder="Ingrese su CI"
              value="{{ old('ci', isset($user) ? $user->ci : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <div>
            <label for="date_of_birth" class="block text-sm font-semibold text-gray-800 mb-1">Fecha de Nacimiento*</label>
            <input id="date_of_birth" name="date_of_birth" type="date" required
              value="{{ old('date_of_birth', isset($user) ? $user->date_of_birth : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
          </div>
        </div>
        <!-- Teléfono y Área en una fila -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
          <div>
            <label for="telephone_number" class="block text-sm font-semibold text-gray-800 mb-1">Teléfono*</label>
            <input id="telephone_number" name="telephone_number" type="text" required placeholder="Ingrese su teléfono o celular"
              value="{{ old('telephone_number', isset($user) ? $user->telephone_number : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <div class="relative">
            <label for="area_id" class="block text-sm font-semibold text-gray-800 mb-1">Área*</label>
            <select id="area_id" name="area_id" required
              class="appearance-none w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
              <option value="" disabled {{ !isset($user) ? 'selected' : '' }}>Selecciona un área</option>
              @foreach($areas as $area)
                @if($area->is_active)
                  <option value="{{ $area->id }}" {{ (isset($user) && $user->area_id == $area->id) ? 'selected' : '' }}>{{ $area->name }}</option>
                @endif
              @endforeach
            </select>
            <i class="bi bi-chevron-down absolute right-3 top-9 pointer-events-none text-gray-500"></i>
          </div>
        </div>
        <!-- Correo electrónico y Dirección en una fila -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">Dirección de correo electrónico*</label>
            <input id="email" name="email" type="email" required placeholder="Ingrese un correo electrónico válido"
              value="{{ old('email', isset($user) ? $user->email : '') }}"
              pattern="[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
              title="Ingrese un correo electrónico válido (se permiten acentos)"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
          <div>
            <label for="address" class="block text-sm font-semibold text-gray-800 mb-1">Dirección</label>
            <input id="address" name="address" type="text" placeholder="Ingrese su dirección"
              value="{{ old('address', isset($user) ? $user->address : '') }}"
              class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
          </div>
        </div>
        <!-- Acciones -->
        <div class="pt-1">
          <div class="w-full flex items-center justify-center gap-4">
            <a href="{{ $return ?? route('admin.usuarios.index') }}"
              class="px-8 py-3 rounded-full font-semibold text-white bg-gray-500 hover:bg-gray-700 transition flex items-center gap-2">
              <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button type="submit"
              class="px-10 py-3 rounded-full font-semibold text-white bg-[#0C3E92] hover:opacity-95 transition">
              {{ isset($user) ? 'Actualizar Usuario' : 'Guardar Usuario' }}
            </button>
          </div>
          <div class="w-2/3 md:w-1/2 mx-auto h-1 rounded bg-gray-300 mt-2"></div>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Toggle contraseña (IDs únicos para este formulario) -->
<script>
  (function () {
    const input = document.getElementById('password_user');
    const btn = document.getElementById('togglePasswordUser');
    const eye = document.getElementById('icon-eye-user');
    const eyeOff = document.getElementById('icon-eye-off-user');
    if (input && btn && eye && eyeOff) {
      btn.addEventListener('click', () => {
        const isVisible = input.type === 'text';
        input.type = isVisible ? 'password' : 'text';
        btn.setAttribute('aria-pressed', String(!isVisible));
        btn.setAttribute('aria-label', isVisible ? 'Mostrar contraseña' : 'Ocultar contraseña');
        eye.classList.toggle('hidden', !isVisible);
        eyeOff.classList.toggle('hidden', isVisible);
      });
    }
  })();
  // Solo letras y espacios en nombre y apellidos
  function soloLetras(e) {
    e.target.value = e.target.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
  }
  document.getElementById('name').addEventListener('input', soloLetras);
  document.getElementById('last_name_father').addEventListener('input', soloLetras);
  document.getElementById('last_name_mother').addEventListener('input', soloLetras);

  // Validación personalizada para email con caracteres especiales del español
  document.getElementById('email').addEventListener('blur', function(e) {
    const emailPattern = /^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const email = e.target.value;
    
    if (email && !emailPattern.test(email)) {
      e.target.setCustomValidity('Ingrese un correo electrónico válido. Se permiten acentos.');
    } else {
      e.target.setCustomValidity('');
    }
  });
</script>
@endsection
