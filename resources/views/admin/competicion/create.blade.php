@extends('layouts.app')

@section('title', 'Agregar Fase')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competicion-create.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/competicion-create.js') }}"></script>
    <!-- Eliminado: listeners DOM para clasificación; se maneja con Alpine por fase -->
@endpush

@section('content')

    <body class="bg-gray-50 min-h-screen p-6 md:p-12">
        <div class="max-w-5xl mx-auto" x-data="competitionForm()" x-init="init()">
            <!-- Título y descripción centrados -->
            <div class="mb-8">
                <h1 class="text-3xl font-semibold text-gray-900 mb-2 text-center">Crear Competencia</h1>
                <p class="text-gray-600 text-center">Configura una nueva competencia académica con fases y áreas de conocimiento</p>
            </div>
            <!-- Botón 'Atrás' alineado a la derecha -->
            <div class="mb-8 flex items-center justify-end">
                <a href="{{ route('admin.competicion.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Atrás
                </a>
            </div>

            <form method="POST" action="{{ route('admin.competicion.store') }}" onsubmit="return validateForm()">
                @csrf

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
                                    placeholder="Ej: Olimpiada Nacional de Ciencias 2025"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    maxlength="64"
                                    title="Debe contener al menos 1 letra. Máximo 60 letras y 4 números. No se permiten caracteres especiales ni más de 2 caracteres idénticos consecutivos."
                                    required>
                                <div class="mt-1 flex justify-between text-xs">
                                    <span class="text-gray-500">Mínimo 1 letra. Máximo 60 letras y 4 números. No más de 2 caracteres idénticos consecutivos.</span>
                                    <span class="text-gray-600">
                                        Letras: <span id="letter-count" class="font-semibold">0</span>/60 | 
                                        Números: <span id="number-count" class="font-semibold">0</span>/4
                                    </span>
                                </div>
                                <div id="validation-error" class="mt-1 text-xs text-red-600 flex items-center gap-1" style="display: none;">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span id="validation-error-message"></span>
                                </div>
                            </div>
                            <div>
                                <label for="competition-description"
                                    class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>
                                <textarea id="competition-description" name="description" rows="3" placeholder="Descripción de la competencia..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline y Fases - CON CALENDARIO VISUAL MEJORADO -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-calendar text-blue-600"></i>
                            Seleccionar Rango de Fechas de la Competencia
                        </h2>
                    </div>
                    <div class="p-6">
                        <!-- Calendario Visual -->
                        @include('components.calendar-date-range-picker')

                        <!-- Campos de Fechas Adicionales -->
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Etapa de Inscripción -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-users text-green-600"></i>
                                    Etapa de Inscripción
                                </h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                                        <input type="date" name="inscripcion_inicio"
                                            x-model="inscripcionInicio"
                                            :disabled="!startDate || !endDate"
                                            :min="startDate"
                                            :max="endDate"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                                        <input type="date" name="inscripcion_fin"
                                            x-model="inscripcionFin"
                                            :disabled="!startDate || !endDate"
                                            :min="startDate"
                                            :max="endDate"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    </div>
                                </div>
                                <div x-show="!startDate || !endDate" class="mt-2 text-xs text-amber-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Selecciona primero el rango de fechas de la competencia</span>
                                </div>
                            </div>

                            <!-- Etapa de Evaluación -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" :class="{'opacity-50': !inscripcionCompleta}">
                                <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-clipboard-check text-blue-600"></i>
                                    Etapa de Evaluación
                                </h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio</label>
                                        <input type="date" name="evaluacion_inicio"
                                            x-model="evaluacionInicio"
                                            :disabled="!inscripcionCompleta"
                                            :min="startDate"
                                            :max="endDate"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin</label>
                                        <input type="date" name="evaluacion_fin"
                                            x-model="evaluacionFin"
                                            :disabled="!inscripcionCompleta"
                                            :min="startDate"
                                            :max="endDate"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm disabled:bg-gray-100 disabled:cursor-not-allowed">
                                    </div>
                                </div>
                                <div x-show="!inscripcionCompleta" class="mt-2 text-xs text-amber-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Completa primero las fechas de inscripción</span>
                                </div>
                            </div>

                            <!-- Etapa de Premiación -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200" :class="{'opacity-50': !evaluacionCompleta, 'border-red-300': evaluacionCompleta && !premiacionCompleta}">
                                <h3 class="text-md font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                    <i class="fas fa-trophy text-yellow-600"></i>
                                    Etapa de premiación
                                    <span class="text-red-600 text-xs" x-show="evaluacionCompleta && !premiacionCompleta">*Requerido</span>
                                </h3>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Inicio <span class="text-red-600">*</span></label>
                                        <input type="date" name="premiacion_inicio"
                                            x-model="premiacionInicio"
                                            :disabled="!evaluacionCompleta"
                                            :min="startDate"
                                            :max="endDate"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                                            :class="{'border-red-300': evaluacionCompleta && !premiacionInicio}"
                                            required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de Fin <span class="text-red-600">*</span></label>
                                        <input type="date" name="premiacion_fin"
                                            x-model="premiacionFin"
                                            :disabled="!evaluacionCompleta"
                                            :min="startDate"
                                            :max="endDate"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
                                            :class="{'border-red-300': evaluacionCompleta && !premiacionFin}"
                                            required>
                                    </div>
                                </div>
                                <div x-show="!evaluacionCompleta" class="mt-2 text-xs text-amber-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Completa primero las fechas de evaluación</span>
                                </div>
                                <div x-show="evaluacionCompleta && !premiacionCompleta" class="mt-2 text-xs text-red-600 flex items-center gap-1">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Debes seleccionar las fechas de la etapa de premiación para crear la competencia</span>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Visualization -->
                        <div class="mt-8">
                            <div x-show="evaluacionInicio && evaluacionFin" class="transition-all duration-300">
                                @include('components.phases-timeline')
                            </div>
                            <div x-show="!evaluacionInicio || !evaluacionFin" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                <div class="text-gray-500">
                                    <i class="fas fa-calendar-times text-3xl mb-3 block"></i>
                                    <h3 class="text-lg font-medium mb-2">Timeline de Fases no disponible</h3>
                                    <p class="text-sm">Selecciona las fechas de inicio y fin de la <strong>Etapa de Evaluación</strong> para visualizar el timeline de fases.</p>
                                </div>
                            </div>
                        </div>

                        <!-- NUEVO: Clasificar estudiantes por fase -->
                        <div class="mt-8">
                            <h3 class="text-md font-semibold text-gray-900 mb-3">Clasificar estudiantes por fase</h3>
                            <p class="text-sm text-gray-600 mb-4">Configura cómo se clasifican los estudiantes para pasar a la siguiente fase. Aplica a cada fase agregada.</p>

                            <template x-for="(phase, idx) in phases" :key="phase.id">
                                <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 mb-3">
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Fase</label>
                                            <div class="text-sm text-gray-900" x-text="phase.name"></div>
                                        </div>
                                        <div class="md:col-span-1">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                            <select x-model="phase.classification_type" :name="`phases[${idx}][classification][type]`" class="w-full rounded-md border border-gray-300 bg-white py-2 px-3 text-sm shadow-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500">
                                                <option value="" selected disabled>Selecciona</option>
                                                <option value="cupo">Por cupo</option>
                                                <option value="notas_altas">Notas altas</option>
                                            </select>
                                        </div>
                                        <div class="md:col-span-1" x-show="phase.classification_type === 'cupo'">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Cupo</label>
                                            <!-- Agregado max="999" -->
                                            <input type="number" min="1" max="999" x-model="phase.classification_cupo" :required="phase.classification_type === 'cupo'" :disabled="phase.classification_type !== 'cupo'" placeholder="Ej: 50" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500" :name="`phases[${idx}][classification][cupo]`">
                                        </div>
                                        <div class="md:col-span-1" x-show="phase.classification_type === 'notas_altas'">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Nota mínima</label>
                                            <input type="number" min="0" max="100" step="0.1" x-model="phase.classification_nota_minima" :required="phase.classification_type === 'notas_altas'" :disabled="phase.classification_type !== 'notas_altas'" placeholder="70" class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-green-500 focus:outline-none focus:ring-1 focus:ring-green-500" :name="`phases[${idx}][classification][nota_minima]`">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Campos ocultos para las fechas de evaluación del timeline -->
                        <input type="hidden" name="timeline_start_date" :value="evaluacionInicio">
                        <input type="hidden" name="timeline_end_date" :value="evaluacionFin">
                    </div>
                </div>



                <!-- Categorías y Áreas -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Categorias y Áreas</h2>
                    </div>
                    <div class="p-6" x-data='{
                        categoriaSeleccionada: "",
                        areaSeleccionada: "",
                        areas: @json($areasCatalog->values()),
                        seleccionados: [],
                        get areasDisponibles() {
                            if (!this.categoriaSeleccionada) return this.areas;
                            const areasUsadas = this.seleccionados
                                .filter(item => item.categoriaId == this.categoriaSeleccionada)
                                .map(item => item.areaId);
                            return this.areas.filter(area => !areasUsadas.includes(area.id));
                        },
                        get categoriasSeleccionadas() {
                            // categorías únicas a partir de las selecciones
                            return [...new Set(this.seleccionados.map(i => i.categoriaId))];
                        },
                        agregarSeleccionado() {
                            if (this.categoriaSeleccionada && this.areaSeleccionada) {
                                const categoriaText = document.querySelector("#categoria_id option:checked").textContent;
                                const areaText = document.querySelector("#area_ids option:checked").textContent;
                                this.seleccionados.push({ 
                                    categoria: categoriaText, 
                                    area: areaText,
                                    categoriaId: this.categoriaSeleccionada,
                                    areaId: parseInt(this.areaSeleccionada)
                                });
                                this.areaSeleccionada = "";
                            }
                        }
                    }'>
                        <div class="flex gap-6">
                            <!-- ComboBox de Categorías -->
                            <div class="flex-1">
                                <label for="categoria_id" class="block text-sm font-medium text-gray-700 mb-2">Selecciona Categoría</label>
                                <select id="categoria_id" name="categoria_id" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500" x-model="categoriaSeleccionada" @change="areaSeleccionada = ''">
                                    <option value="">-- Selecciona una categoría --</option>
                                    @foreach($categoriasCatalog as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- ComboBox de Áreas -->
                            <div class="flex-1">
                                <label for="area_ids" class="block text-sm font-medium text-gray-700 mb-2">Selecciona Áreas</label>
                                <div class="flex items-center gap-2">
                                    <select id="area_ids" name="area_ids[]" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" x-model="areaSeleccionada" :disabled="!categoriaSeleccionada">
                                        <option value="">-- Selecciona un área --</option>
                                        <template x-for="area in areasDisponibles" :key="area.id">
                                            <option :value="area.id" x-text="area.name"></option>
                                        </template>
                                    </select>
                                    <button type="button"
                                        :class="[(!areaSeleccionada || !categoriaSeleccionada) ? 'bg-gray-400 cursor-not-allowed text-white' : 'bg-[#091C47] text-white hover:bg-[#0a1d4a] focus:ring-[#091C47] focus:ring-offset-2', 'px-4 py-2 rounded-md focus:outline-none focus:ring-2 transition-colors ml-2']"
                                        :disabled="!areaSeleccionada || !categoriaSeleccionada"
                                        @click="agregarSeleccionado()">
                                        Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Tabla de Categorías y Áreas seleccionadas -->
                        <div class="mt-8 flex justify-center">
                            <div class="w-2/5">
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm" x-bind:class="{'max-h-72 overflow-hidden': seleccionados.length > 6}">
                                    <table class="w-full table-fixed">
                                        <thead>
                                            <tr>
                                                <th class="w-1/2 px-4 py-2 border-b text-center text-sm font-bold text-gray-700">CATEGORÍAS</th>
                                                <th class="w-1/2 px-4 py-2 border-b text-center text-sm font-bold text-gray-700">ÁREAS</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    <div x-bind:class="{'overflow-y-auto max-h-48': seleccionados.length > 6}">
                                        <table class="w-full table-fixed">
                                            <tbody>
                                                <template x-for="(item, idx) in seleccionados" :key="idx">
                                                    <tr>
                                                        <td class="w-1/2 px-4 py-2 border-b text-center" x-text="item.categoria"></td>
                                                        <td class="w-1/2 px-4 py-2 border-b text-center relative">
                                                            <span x-text="item.area"></span>
                                                            <button type="button" @click="seleccionados.splice(idx, 1)" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-red-600 hover:text-red-800 text-lg" title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos ocultos para enviar las áreas seleccionadas -->
                        <template x-for="(item, index) in seleccionados" :key="`pair-${index}`">
                            <div>
                                <input type="hidden" :name="`pairs[${index}][categoria_id]`" :value="item.categoriaId">
                                <input type="hidden" :name="`pairs[${index}][area_id]`" :value="item.areaId">
                            </div>
                        </template>
                        <!-- Campos ocultos para enviar sólo IDs planos (compatibilidad) -->
                        <template x-for="(item, index) in seleccionados.filter(i => i.areaId)" :key="`a-${index}`">
                            <input type="hidden" :name="`area_ids[${index}]`" :value="item.areaId">
                        </template>
                        <template x-for="(catId, idx) in categoriasSeleccionadas" :key="`cat-${idx}`">
                            <input type="hidden" name="categoria_ids[]" :value="catId">
                        </template>
                    </div>
                </div>

                <!-- Botones de acción -->
                <!-- Campos ocultos para enviar fases -->
                <template x-for="(phase, index) in phases" :key="phase.id">
                    <div>
                        <input type="hidden" :name="`phases[${index}][phase_id]`" :value="phase.phase_id">
                        <input type="hidden" :name="`phases[${index}][start_date]`" :value="phase.start_date">
                        <input type="hidden" :name="`phases[${index}][end_date]`" :value="phase.end_date">
                        <input type="hidden" :name="`phases[${index}][color]`" :value="phase.color">
                    </div>
                </template>
                <div class="flex justify-end gap-3">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 text-black rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        onclick="window.location='{{ route('admin.competicion.index') }}'">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="btn btn-primary px-6 py-2 rounded-md transition-all"
                        :class="todasEtapasCompletas ? 'bg-[#091c47] text-white hover:bg-[#0a1d4a]' : 'bg-gray-400 text-gray-200 cursor-not-allowed'"
                        :disabled="!todasEtapasCompletas"
                        :title="!todasEtapasCompletas ? 'Debes completar las fechas de las 3 etapas (Inscripción, Evaluación y Premiación)' : ''">
                        Crear Competencia
                    </button>
                </div>
                
                <!-- Mensaje de advertencia si faltan etapas -->
                <div x-show="!todasEtapasCompletas" class="mt-3 flex justify-end">
                    <div class="text-sm text-red-600 flex items-center gap-2 bg-red-50 border border-red-200 rounded-md px-4 py-2">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Debes completar las fechas de las 3 etapas para crear la competencia</span>
                    </div>
                </div>

                <!-- Campos ocultos para rango de fechas -->
                <input type="hidden" name="fechaInicio" :value="startDate">
                <input type="hidden" name="fechaFin" :value="endDate">
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const input = document.getElementById('competition-name');
                const letterCountEl = document.getElementById('letter-count');
                const numberCountEl = document.getElementById('number-count');
                const validationError = document.getElementById('validation-error');
                const validationErrorMsg = document.getElementById('validation-error-message');
                
                let lastValidValue = '';
                
                function removeConsecutiveDuplicates(str) {
                    // Remove more than 2 consecutive identical characters
                    return str.replace(/(.)\1{2,}/g, '$1$1');
                }
                
                function validateInput(value) {
                    // Remove special characters, only allow letters, spaces, and numbers
                    value = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s0-9]/g, '');
                    
                    // Remove double spaces
                    value = value.replace(/\s{2,}/g, ' ');
                    
                    // Remove more than 2 consecutive identical characters
                    value = removeConsecutiveDuplicates(value);
                    
                    // Separate text and numbers - Remove anything after numbers
                    let textPart = '';
                    let numberPart = '';
                    
                    // Find where numbers start at the end
                    let lastNumberIndex = -1;
                    for (let i = value.length - 1; i >= 0; i--) {
                        if (/[0-9]/.test(value[i])) {
                            lastNumberIndex = i;
                        } else if (lastNumberIndex !== -1) {
                            // Found a non-number after finding numbers at the end
                            // This means there's text after numbers - remove everything after the first number block
                            break;
                        }
                    }
                    
                    // Separate if there are numbers at the end
                    if (lastNumberIndex !== -1 && /[0-9]$/.test(value)) {
                        let firstNumberIndex = lastNumberIndex;
                        for (let i = lastNumberIndex; i >= 0; i--) {
                            if (/[0-9]/.test(value[i])) {
                                firstNumberIndex = i;
                            } else {
                                break;
                            }
                        }
                        textPart = value.substring(0, firstNumberIndex);
                        numberPart = value.substring(firstNumberIndex);
                        
                        // Remove any text that might be after the numbers
                        // By only taking characters up to and including the last number
                    } else if (value.match(/\d/)) {
                        // If there are numbers but not at the end, remove everything after the first number sequence
                        const match = value.match(/^([^0-9]*\d+)/);
                        if (match) {
                            value = match[1];
                            // Re-process to separate text and numbers
                            const numMatch = value.match(/\d+$/);
                            if (numMatch) {
                                numberPart = numMatch[0];
                                textPart = value.substring(0, value.length - numberPart.length);
                            } else {
                                textPart = value;
                                numberPart = '';
                            }
                        } else {
                            textPart = value;
                            numberPart = '';
                        }
                    } else {
                        textPart = value;
                        numberPart = '';
                    }
                    
                    // Limit text part to 60 characters
                    if (textPart.length > 60) {
                        textPart = textPart.substring(0, 60);
                    }
                    
                    // Limit numbers to 4 digits
                    if (numberPart.length > 4) {
                        numberPart = numberPart.substring(0, 4);
                    }
                    
                    return {
                        value: textPart + numberPart,
                        textPart: textPart,
                        numberPart: numberPart
                    };
                }
                
                function showError(message) {
                    validationErrorMsg.textContent = message;
                    validationError.style.display = 'flex';
                    input.classList.add('border-red-500');
                }
                
                function hideError() {
                    validationError.style.display = 'none';
                    input.classList.remove('border-red-500');
                }
                
                // Initialize with current value (if any, e.g., after validation error)
                if (input.value) {
                    const result = validateInput(input.value);
                    input.value = result.value;
                    letterCountEl.textContent = result.textPart.length;
                    numberCountEl.textContent = result.numberPart.length;
                    
                    const lettersOnly = result.textPart.replace(/[\s0-9]/g, '');
                    if (result.value && lettersOnly.length < 1) {
                        showError('El nombre debe contener al menos 1 letra (sin contar espacios y números)');
                    }
                }
                
                input.addEventListener('input', function(e) {
                    const cursorPosition = this.selectionStart;
                    const oldValue = this.value;
                    
                    const result = validateInput(this.value);
                    const newValue = result.value;
                    
                    this.value = newValue;
                    lastValidValue = newValue;
                    
                    // Update counters
                    letterCountEl.textContent = result.textPart.length;
                    numberCountEl.textContent = result.numberPart.length;
                    
                    // Check if has at least 1 letter
                    const lettersOnly = result.textPart.replace(/[\s0-9]/g, '');
                    if (newValue && lettersOnly.length < 1) {
                        showError('El nombre debe contener al menos 1 letra (sin contar espacios y números)');
                    } else {
                        hideError();
                    }
                    
                    // Restore cursor position
                    const diff = oldValue.length - newValue.length;
                    const newCursorPosition = Math.max(0, cursorPosition - diff);
                    this.setSelectionRange(newCursorPosition, newCursorPosition);
                });
                
                // Validate on paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const currentValue = this.value;
                    const cursorPosition = this.selectionStart;
                    
                    // Insert pasted text at cursor position
                    const newValue = currentValue.substring(0, cursorPosition) + pastedText + currentValue.substring(this.selectionEnd);
                    
                    // Validate the new value
                    const result = validateInput(newValue);
                    this.value = result.value;
                    
                    // Trigger input event to update counters
                    this.dispatchEvent(new Event('input'));
                });
            });
        </script>
    </body>
@endsection
