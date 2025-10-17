@extends('layouts.app')

@section('title', 'Editar Competición')

@section('content')

    <body class="bg-gray-50 min-h-screen p-6 md:p-12">
        <div class="max-w-5xl mx-auto">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900 mb-2">Editar Competencia</h1>
                    <p class="text-gray-600">Modifica los datos de la competencia seleccionada</p>
                </div>
                <a href="{{ route('admin.competicion.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Atrás
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <h3 class="font-semibold text-red-800 mb-2">Errores de validación:</h3>
                    <ul class="list-disc list-inside text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.competicion.update', $competicion->id) }}">
                @csrf
                @method('PUT')

                <!-- Información General -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-trophy text-blue-600"></i>
                            Información General
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div>
                                <label for="competition-name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre de la Competencia
                                </label>
                                <input type="text" id="competition-name" name="name" 
                                    value="{{ old('name', $competicion->name) }}"
                                    placeholder="Ej: Olimpiada Nacional de Ciencias 2025"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label for="competition-description"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>
                                <textarea id="competition-description" name="description" rows="3" 
                                    placeholder="Descripción de la competencia..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $competicion->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fechas de la Competencia -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar text-blue-600"></i>
                            Fechas de la Competencia
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fechaInicio" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Inicio
                                </label>
                                <input type="date" id="fechaInicio" name="fechaInicio" 
                                    value="{{ old('fechaInicio', $competicion->fechaInicio ? $competicion->fechaInicio->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                            </div>
                            <div>
                                <label for="fechaFin" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Fin
                                </label>
                                <input type="date" id="fechaFin" name="fechaFin" 
                                    value="{{ old('fechaFin', $competicion->fechaFin ? $competicion->fechaFin->format('Y-m-d') : '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                    required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fases -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-project-diagram text-blue-600"></i>
                            Fases de la Competencia
                        </h2>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-gray-600 mb-4">Selecciona las fases y configura sus fechas de inicio y fin.</p>
                        
                        <div class="space-y-4">
                            @foreach($fasesCatalog as $index => $fase)
                                @php
                                    $phaseData = $competicion->phases->firstWhere('id', $fase->id);
                                    $isSelected = $phaseData !== null;
                                    $startDate = $isSelected && $phaseData->pivot->start_date ? \Carbon\Carbon::parse($phaseData->pivot->start_date)->format('Y-m-d') : '';
                                    $endDate = $isSelected && $phaseData->pivot->end_date ? \Carbon\Carbon::parse($phaseData->pivot->end_date)->format('Y-m-d') : '';
                                @endphp
                                
                                <div class="border border-gray-200 rounded-lg p-4 {{ $isSelected ? 'bg-blue-50' : 'bg-white' }}">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                                        <div class="md:col-span-1">
                                            <label class="flex items-center">
                                                <input type="checkbox" 
                                                    name="phases[{{ $index }}][selected]" 
                                                    value="1"
                                                    {{ $isSelected ? 'checked' : '' }}
                                                    class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                    onchange="togglePhase(this, {{ $index }})">
                                                <span class="font-medium text-gray-900">{{ $fase->name }}</span>
                                            </label>
                                            <input type="hidden" name="phases[{{ $index }}][phase_id]" value="{{ $fase->id }}">
                                        </div>
                                        
                                        <div class="md:col-span-1">
                                            <label class="block text-xs text-gray-600 mb-1">Fecha de Inicio</label>
                                            <input type="date" 
                                                name="phases[{{ $index }}][start_date]" 
                                                id="phase_start_{{ $index }}"
                                                value="{{ old('phases.'.$index.'.start_date', $startDate) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                                {{ !$isSelected ? 'disabled' : '' }}>
                                        </div>
                                        
                                        <div class="md:col-span-1">
                                            <label class="block text-xs text-gray-600 mb-1">Fecha de Fin</label>
                                            <input type="date" 
                                                name="phases[{{ $index }}][end_date]" 
                                                id="phase_end_{{ $index }}"
                                                value="{{ old('phases.'.$index.'.end_date', $endDate) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                                {{ !$isSelected ? 'disabled' : '' }}>
                                        </div>
                                        
                                        <div class="md:col-span-1 text-right">
                                            @if($isSelected)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Activa
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Inactiva
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Niveles Educativos -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-blue-600"></i>
                            Niveles Educativos
                        </h2>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Selecciona los Niveles</label>
                        <div class="space-y-2">
                            @foreach($levelsCatalog as $level)
                                <label class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                        name="level_ids[]" 
                                        value="{{ $level->id }}"
                                        {{ $competicion->levels->pluck('id')->contains($level->id) ? 'checked' : '' }}
                                        class="mr-3 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <span class="text-gray-900">{{ $level->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Áreas de Conocimiento -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-book text-blue-600"></i>
                            Áreas de Conocimiento
                        </h2>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Selecciona las Áreas</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($areasCatalog as $area)
                                <label class="flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50 cursor-pointer">
                                    <input type="checkbox" 
                                        name="area_ids[]" 
                                        value="{{ $area->id }}"
                                        {{ $competicion->areas->pluck('id')->contains($area->id) ? 'checked' : '' }}
                                        class="mr-3 h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                    <span class="text-gray-900">{{ $area->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.competicion.index') }}" 
                        class="px-6 py-2 border border-gray-300 text-black rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="btn btn-primary bg-[#091c47] text-white px-6 py-2 rounded-md hover:bg-[#0a2556] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar Competencia
                    </button>
                </div>
            </form>
        </div>
    </body>
@endsection

<script>
// JavaScript simple para habilitar/deshabilitar campos de fecha cuando se selecciona una fase
function togglePhase(checkbox, index) {
    const startDateInput = document.getElementById('phase_start_' + index);
    const endDateInput = document.getElementById('phase_end_' + index);
    const parentDiv = checkbox.closest('.border');
    
    if (checkbox.checked) {
        startDateInput.disabled = false;
        endDateInput.disabled = false;
        parentDiv.classList.remove('bg-white');
        parentDiv.classList.add('bg-blue-50');
    } else {
        startDateInput.disabled = true;
        endDateInput.disabled = true;
        startDateInput.value = '';
        endDateInput.value = '';
        parentDiv.classList.remove('bg-blue-50');
        parentDiv.classList.add('bg-white');
    }
}
</script>

<style>
/* Estilos adicionales si son necesarios */
.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}
</style>
