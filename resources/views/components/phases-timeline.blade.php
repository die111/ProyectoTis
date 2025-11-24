<!-- Timeline Visualization -->
<div x-show="(typeof evaluacionInicio !== 'undefined' && evaluacionInicio && evaluacionFin) || (startDate && endDate)" class="border-t border-gray-200 pt-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Timeline de Fases</h3>
        <button type="button" @click="addPhase"
            class="btn btn-primary inline-flex items-center px-4 py-2 text-sm font-medium rounded-md"
            :disabled="!((typeof evaluacionInicio !== 'undefined' && evaluacionInicio && evaluacionFin) || (startDate && endDate))">
            <i class="fas fa-plus mr-2"></i>
            Agregar Fase
        </button>
    </div>

    <!-- Visual Timeline -->
    <div class="mb-6">
        <div class="relative">
            <!-- Línea del timeline -->
            <div class="h-2 bg-gray-200 rounded-full mb-2"></div>

            <!-- Fases en el timeline -->
            <template x-for="(phase, index) in phases" :key="phase.id">
                <div class="absolute top-0 h-2 rounded-full cursor-pointer transition-all hover:opacity-80"
                    :style="{
                        left: getPhasePosition(phase).left,
                        width: getPhasePosition(phase).width,
                        backgroundColor: phase.color || '#3B82F6'
                    }"
                    @click="setEditingPhase(phase.id)"
                    :title="phase.name + ' (' + formatDate(phase.start_date) + ' - ' + formatDate(
                        phase.end_date) + ')'">
                </div>
            </template>
        </div>

        <!-- Etiquetas del timeline -->
        <div class="flex justify-between text-xs text-gray-500 mt-1">
            <span x-text="(typeof evaluacionInicio !== 'undefined' && evaluacionInicio) ? formatDate(evaluacionInicio) : (startDate ? formatDate(startDate) : '')"></span>
            <span x-text="(typeof evaluacionFin !== 'undefined' && evaluacionFin) ? formatDate(evaluacionFin) : (endDate ? formatDate(endDate) : '')"></span>
        </div>
    </div>

    <!-- Detalles de las Fases -->
    <div class="space-y-4">
        <h4 class="text-sm font-semibold text-gray-900">Detalles de las Fases</h4>

        <template x-for="(phase, index) in phases" :key="phase.id">
            <div class="p-4 rounded-lg border border-gray-200 bg-gray-50"
                :class="{ 'ring-2 ring-blue-500': editingPhase === phase.id }">
                <div class="flex items-start gap-3">
                    <!-- Color indicator -->
                    <div class="w-3 h-3 rounded-full flex-shrink-0 mt-1.5"
                        :style="{ backgroundColor: phase.color || '#3B82F6' }"></div>

                    <!-- Contenido de la fase -->
                    <div class="flex-1 space-y-3">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="col-span-1 md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nombre de la Fase
                                </label>
                                <select x-model="phase.phase_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">-- Selecciona una fase --</option>
                                    @foreach($fasesCatalog as $fase)
                                        <option value="{{ $fase->id }}" x-bind:disabled="phases.filter(p => p.phase_id == {{ $fase->id }}).length > 0 && phase.phase_id != {{ $fase->id }}">{{ $fase->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Color
                                </label>
                                <input type="color" x-model="phase.color" class="w-full h-10 px-1 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha de Inicio
                                </label>
                                <input type="date" x-model="phase.start_date"
                                    @change="updatePhaseValidation(phase)"
                                    :min="(typeof evaluacionInicio !== 'undefined' && evaluacionInicio) ? evaluacionInicio : startDate" 
                                    :max="(typeof evaluacionFin !== 'undefined' && evaluacionFin) ? evaluacionFin : endDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': phase.errors.length }"
                                    required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha de Fin
                                </label>
                                <input type="date" x-model="phase.end_date"
                                    @change="updatePhaseValidation(phase)"
                                    :min="phase.start_date || ((typeof evaluacionInicio !== 'undefined' && evaluacionInicio) ? evaluacionInicio : startDate)"
                                    :max="(typeof evaluacionFin !== 'undefined' && evaluacionFin) ? evaluacionFin : endDate"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': phase.errors.length }"
                                    required>
                            </div>
                        </div>

                        <!-- Errores de validación -->
                        <template x-if="phase.errors.length">
                            <div class="space-y-1">
                                <template x-for="error in phase.errors"
                                    :key="error">
                                    <div class="flex items-start gap-2 text-xs text-red-600">
                                        <i
                                            class="fas fa-exclamation-circle mt-0.5 flex-shrink-0"></i>
                                        <span x-text="error"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Botón eliminar -->
                    <button type="button" @click="removePhase(phase.id)"
                        class="text-gray-400 hover:text-red-500 flex-shrink-0">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </template>

        <div x-show="phases.length === 0"
            class="text-center py-8 text-gray-500 border-2 border-dashed border-gray-300 rounded-lg">
            <i class="fas fa-calendar-plus text-3xl text-gray-400 mb-3"></i>
            <p>No hay fases agregadas. Haz clic en "Agregar Fase" para dividir el timeline en
                fases.</p>
        </div>
    </div>
</div>
