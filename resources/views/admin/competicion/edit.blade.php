@extends('layouts.app')
@section('title', 'Editar Competición')
@section('content')
    <body class="bg-gray-50 min-h-screen p-6 md:p-12">
        <div class="max-w-5xl mx-auto" x-data="competitionFormEdit({{
            json_encode($competicion),
            }})" x-init="initEdit()">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900 mb-2">Editar Competencia</h1>
                    <p class="text-gray-600">Modifica los datos de la competencia seleccionada</p>
                </div>
                <a href="{{ route('admin.competicion.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Atrás
                </a>
            </div>

            <form method="POST" action="{{ route('admin.competicion.update', $competicion->id) }}" @submit.prevent="submitFormEdit">
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
                                <input type="text" id="competition-name" name="name" x-model="competitionName"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>
                            <div>
                                <label for="competition-description"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>
                                <textarea id="competition-description" name="description" rows="3" x-model="competitionDescription" placeholder="Descripción de la competencia..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline y Fases -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar text-blue-600"></i>
                            Seleccionar Rango de Fechas de la Competencia
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="fechaInicio" class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                                <input type="date" id="fechaInicio" name="fechaInicio" x-model="fechaInicio"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label for="fechaFin" class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                                <input type="date" id="fechaFin" name="fechaFin" x-model="fechaFin"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Niveles y Áreas -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-layer-group text-blue-600"></i>
                            Niveles y Áreas
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="levels" class="block text-sm font-medium text-gray-700 mb-2">Niveles</label>
                                <select name="levels[]" id="levels" multiple x-model="selectedLevels"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($levelsCatalog as $level)
                                        <option value="{{ $level->id }}">{{ $level->nombre }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted-foreground">Ctrl+Click para seleccionar varios</small>
                            </div>
                            <div>
                                <label for="areas" class="block text-sm font-medium text-gray-700 mb-2">Áreas</label>
                                <select name="areas[]" id="areas" multiple x-model="selectedAreas"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($areasCatalog as $a)
                                        <option value="{{ $a->id }}">{{ $a->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted-foreground">Ctrl+Click para seleccionar varios</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fases -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-project-diagram text-blue-600"></i>
                            Fases
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($fasesCatalog as $i => $fase)
                                @php
                                    $pivot = $competicion->phases->firstWhere('id', $fase->id)?->pivot;
                                @endphp
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                    <div>
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="phases[{{ $i }}][phase_id]" value="{{ $fase->id }}" x-model="selectedPhases" :checked="selectedPhases.includes('{{ $fase->id }}')">
                                            <span>{{ $fase->name }}</span>
                                        </label>
                                        <input type="hidden" name="phases[{{ $i }}][phase_id_hidden]" value="{{ $fase->id }}">
                                    </div>
                                    <div>
                                        <label class="block text-xs mb-1">Fecha inicio</label>
                                        <input type="date" name="phases[{{ $i }}][start_date]" x-model="phaseStartDates[{{ $fase->id }}]" value="{{ old('phases.'.$i.'.start_date', $pivot?->start_date) }}" class="w-full border border-border rounded-md px-2 py-1">
                                    </div>
                                    <div>
                                        <label class="block text-xs mb-1">Fecha fin</label>
                                        <input type="date" name="phases[{{ $i }}][end_date]" x-model="phaseEndDates[{{ $fase->id }}]" value="{{ old('phases.'.$i.'.end_date', $pivot?->end_date) }}" class="w-full border border-border rounded-md px-2 py-1">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('admin.competicion.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </body>
@endsection

<script>
function competitionFormEdit(competicion) {
    return {
        competitionName: competicion.name || '',
        competitionDescription: competicion.description || '',
        fechaInicio: competicion.fechaInicio ? competicion.fechaInicio.substring(0, 10) : '',
        fechaFin: competicion.fechaFin ? competicion.fechaFin.substring(0, 10) : '',
        selectedLevels: (competicion.levels || []).map(l => l.id.toString()),
        selectedAreas: (competicion.areas || []).map(a => a.id.toString()),
        selectedPhases: (competicion.phases || []).map(f => f.id.toString()),
        phaseStartDates: Object.fromEntries((competicion.phases || []).map(f => [f.id, f.pivot.start_date ? f.pivot.start_date.substring(0, 10) : ''])),
        phaseEndDates: Object.fromEntries((competicion.phases || []).map(f => [f.id, f.pivot.end_date ? f.pivot.end_date.substring(0, 10) : ''])),

        initEdit() {
        },

        submitFormEdit(e) {
            e.target.submit();
        }
    }
}
</script>
