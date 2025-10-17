{{-- components/modals/formulario-evaluador.blade.php --}}
<div x-show="{{ $state }}" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
    <!-- Backdrop: cierra solo este modal -->
    <div class="absolute inset-0 bg-black/50" @click="{{ $state }} = false"></div>

    <!-- Panel -->
    <div class="relative z-10 w-full max-w-4xl rounded-2xl overflow-hidden" @click.stop
        @keydown.escape.window="{{ $state }} = false">

        <!-- Header -->
        <div class="bg-[#0C3E92] text-white px-6 py-5 flex items-center justify-between">
            <h2 class="text-2xl font-semibold" x-text="modalTitle || 'Crear Evaluador'"></h2>
            <button @click="{{ $state }} = false" class="text-white/90 hover:text-white">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="bg-gray-200 px-6 py-6">
            <p class="text-lg font-semibold text-gray-800">Los campos con * son obligatorios</p>
            <hr class="border-t border-gray-400 mt-2 mb-6" />

            <form action="{{ route('admin.usuarios.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="role" value="evaluador">

                <!-- Grid 3 columnas -->
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

                    <!-- Área (si el evaluador no tiene área, puedes quitar este bloque) -->
                    <div class="relative">
                        <label for="area_id" class="block text-sm font-semibold text-gray-800 mb-1">Área*</label>
                        <select id="area_id" name="area_id" required
                            class="appearance-none w-full rounded-xl border border-gray-300 bg-white px-4 py-3 pr-10 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-gray-800">
                            <option value="" disabled selected>Selecciona un área</option>
                            <option value="1">Matemática</option>
                            <option value="2">Informática</option>
                            <option value="3">Física</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-3 top-9 pointer-events-none text-gray-500"></i>
                        @error('area_id')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Código Usuario -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-800 mb-1">Código Usuario*</label>
                        <input type="text" name="user_code" placeholder="Ingrese el codigo de Usuario" required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
                        @error('user_code')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-800 mb-1">Contraseña*</label>
                        <input id="password" name="password" type="password" placeholder="Ingrese su contraseña"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
                        <p id="passwordMessage" class="mt-1 text-sm"></p>
                        @error('password')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email (ocupa 3 columnas) -->
                    <div class="md:col-span-3">
                        <label for="email" class="block text-sm font-semibold text-gray-800 mb-1">Dirección de correo
                            electrónico*</label>
                        <input id="email" name="email" type="email" placeholder="Ingrese un correo electronico valido"
                            required
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 placeholder:text-gray-400">
                        @error('email')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Botón central con barrita inferior -->
                <div class="pt-1">
                    <div class="w-full flex items-center justify-center">
                        <button type="submit"
                            class="px-10 py-3 rounded-full font-semibold text-white bg-[#0C3E92] hover:opacity-95 transition">
                            Guardar y crear
                        </button>
                    </div>
                    <div class="w-2/3 md:w-1/2 mx-auto h-1 rounded bg-gray-300 mt-2"></div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const passwordInput = document.getElementById('password');
    const passwordMessage = document.getElementById('passwordMessage');

    passwordInput.addEventListener('input', () => {
        if (passwordInput.value.length >= 8) {
            passwordMessage.textContent = "Contraseña válida ✅";
            passwordMessage.style.color = "green";
        } else {
            passwordMessage.textContent = "La contraseña debe tener al menos 8 caracteres ⚠️";
            passwordMessage.style.color = "red";
        }
    });
</script>