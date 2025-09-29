<div x-show="{{ $state }}" x-transition x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/50" @click="{{ $state }} = false"></div>

    <div class="relative z-10 w-full max-w-4xl rounded-2xl overflow-hidden" @click.stop @keydown.escape.window="{{ $state }} = false">
        <div class="bg-[#0C3E92] text-white px-6 py-5 flex items-center justify-between">
            <h2 class="text-2xl font-semibold">Editar Encargado</h2>
            <button @click="{{ $state }} = false" class="text-white/90 hover:text-white">
                <i class="bi bi-x-lg text-xl"></i>
            </button>
        </div>

        <div class="bg-gray-200 px-6 py-6">
            <form :action="'/dashboard/admin/usuarios/' + modalUser.id" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label>Nombre*</label>
                        <input x-model="modalUser.name" type="text" name="name" required class="w-full ...">
                    </div>
                    <div>
                        <label>Apellido Paterno*</label>
                        <input x-model="modalUser.last_name_father" type="text" name="last_name_father" required class="w-full ...">
                    </div>
                    <div>
                        <label>Apellido Materno</label>
                        <input x-model="modalUser.last_name_mother" type="text" name="last_name_mother" class="w-full ...">
                    </div>
                    <div>
                        <label>Área*</label>
                        <select x-model="modalUser.area_id" name="area_id" required class="w-full ...">
                            <option value="" disabled>Selecciona un área</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Código Usuario*</label>
                        <input x-model="modalUser.user_code" type="text" name="user_code" required class="w-full ...">
                    </div>
                    <div class="md:col-span-3">
                        <label>Email*</label>
                        <input x-model="modalUser.email" type="email" name="email" required class="w-full ...">
                    </div>
                </div>

                <div class="pt-1 flex justify-center">
                    <button type="submit" class="px-10 py-3 rounded-full bg-[#0C3E92] text-white font-semibold">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
