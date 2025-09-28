<div x-show="{{ $state }}" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">{{ $title }}</h2>

        {{-- Alertas de validación --}}
        @if($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                    <div>
                        <p class="font-medium">Se encontraron los siguientes errores:</p>
                        <ul class="mt-1 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Alertas de éxito/error para el modal --}}
        @if(session('modal_success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('modal_success') }}
                </div>
            </div>
        @endif
        @if(session('modal_error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('modal_error') }}
                </div>
            </div>
        @endif

    {{ $slot }}
        <button @click="{{ $state }} = false" class="mt-4 px-4 py-2 bg-gray-300 rounded">Cerrar</button>
    </div>
    <div class="fixed inset-0 bg-black opacity-50"></div>
</div>