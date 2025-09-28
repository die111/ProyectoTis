{{-- resources/views/components/modals/crear-area.blade.php --}}
<div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div @click.away="open = false" class="w-full max-w-lg rounded-lg bg-white p-6 shadow-lg">
        <div class="border-b pb-3">
            <h2 class="text-center text-lg font-semibold text-slate-700">Crear Área</h2>
        </div>
        {{-- Formulario --}}
        <form action="{{ route('admin.areas.store') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Nombre</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20">
                @error('name')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Descripción</label>
                <textarea name="description" rows="3" required class="mt-1 w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:ring-2 focus:ring-slate-500/20">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-xs text-red-500">{{ $message }}</span>
                @enderror
            </div>
            <div class="flex justify-center pt-4">
                <button type="submit"
                    class="rounded-lg bg-[#0C204A] px-6 py-2 text-sm font-semibold text-white hover:brightness-110">
                    Crear Área
                </button>
            </div>
        </form>
    </div>
</div>
