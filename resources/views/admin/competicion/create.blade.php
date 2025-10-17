@extends('layouts.app')

@section('title', 'Agregar Fase')

@section('content')

    <body class="bg-gray-50 min-h-screen p-6 md:p-12">
        <div class="max-w-5xl mx-auto" x-data="competitionForm()" x-init="init()">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-semibold text-gray-900 mb-2">Crear Competencia</h1>
                    <p class="text-gray-600">Configura una nueva competencia académica con fases y áreas de conocimiento</p>
                </div>
                <a href="{{ route('admin.competicion.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Atrás
                </a>
            </div>

            <form method="POST" action="{{ route('admin.competicion.store') }}" @submit.prevent="submitForm">
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
                                <input type="text" id="competition-name" name="name" x-model="competitionName"
                                    placeholder="Ej: Olimpiada Nacional de Ciencias 2025"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
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
                        <div class="mb-6">
                            <div class="bg-white rounded-lg border border-gray-200 p-4">
                                <!-- Navegación del calendario -->
                                <div class="flex items-center justify-between mb-4">
                                    <button type="button" @click="previousMonth" class="p-2 hover:bg-gray-100 rounded">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="currentMonthYear"></h3>
                                    <button type="button" @click="nextMonth" class="p-2 hover:bg-gray-100 rounded">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                                <!-- Calendario de dos meses -->
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- Mes 1 -->
                                    <div>
                                        <h4 class="text-center font-semibold text-gray-700 mb-3"
                                            x-text="getMonthName(currentMonth)"></h4>
                                        <div class="grid grid-cols-7 gap-1 mb-2">
                                            <template x-for="day in ['lu', 'ma', 'mi', 'ju', 'vi', 'sá', 'do']"
                                                :key="day">
                                                <div class="text-center text-xs font-medium text-gray-500 py-1"
                                                    x-text="day"></div>
                                            </template>
                                        </div>
                                        <div class="grid grid-cols-7 gap-1">
                                            <template x-for="day in calendarDays1" :key="day.date">
                                                <button type="button" class="calendar-day"
                                                    :class="{
                                                        'selected': isSelected(day.date),
                                                        'range-start': isRangeStart(day.date),
                                                        'range-end': isRangeEnd(day.date),
                                                        'range': isInRange(day.date),
                                                        'other-month': !day.isCurrentMonth,
                                                        'today': isToday(day.date),
                                                        'disabled': !day.isCurrentMonth
                                                    }"
                                                    @click="selectDate(day.date, day.isCurrentMonth)" x-text="day.day"
                                                    :disabled="!day.isCurrentMonth"></button>
                                            </template>
                                        </div>
                                    </div>

                                    <!-- Mes 2 -->
                                    <div>
                                        <h4 class="text-center font-semibold text-gray-700 mb-3"
                                            x-text="getMonthName(nextMonth)"></h4>
                                        <div class="grid grid-cols-7 gap-1 mb-2">
                                            <template x-for="day in ['lu', 'ma', 'mi', 'ju', 'vi', 'sá', 'do']"
                                                :key="day">
                                                <div class="text-center text-xs font-medium text-gray-500 py-1"
                                                    x-text="day"></div>
                                            </template>
                                        </div>
                                        <div class="grid grid-cols-7 gap-1">
                                            <template x-for="day in calendarDays2" :key="day.date">
                                                <button type="button" class="calendar-day"
                                                    :class="{
                                                        'selected': isSelected(day.date),
                                                        'range-start': isRangeStart(day.date),
                                                        'range-end': isRangeEnd(day.date),
                                                        'range': isInRange(day.date),
                                                        'other-month': !day.isCurrentMonth,
                                                        'today': isToday(day.date),
                                                        'disabled': !day.isCurrentMonth
                                                    }"
                                                    @click="selectDate(day.date, day.isCurrentMonth)" x-text="day.day"
                                                    :disabled="!day.isCurrentMonth"></button>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <!-- Información del rango seleccionado -->
                                <div x-show="startDate && endDate"
                                    class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                    <p class="text-sm text-blue-800">
                                        <span class="font-semibold">Rango seleccionado:</span>
                                        <span x-text="formatDate(startDate)"></span> - <span
                                            x-text="formatDate(endDate)"></span>
                                    </p>
                                    <p class="text-sm text-blue-600 mt-1"
                                        x-text="'Duración: ' + getTotalDays() + ' días'"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Timeline Visualization -->
                        <div x-show="startDate && endDate" class="border-t border-gray-200 pt-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Timeline de Fases</h3>
                                <button type="button" @click="addPhase"
                                    class="btn btn-primary inline-flex items-center px-4 py-2 text-sm font-medium rounded-md"
                                    :disabled="!startDate || !endDate">
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
                                        <div class="absolute top-0 h-2 rounded-full phase-color-0 cursor-pointer transition-all hover:opacity-80"
                                            :class="'phase-color-' + (index % 6)" :style="getPhasePosition(phase)"
                                            @click="setEditingPhase(phase.id)"
                                            :title="phase.name + ' (' + formatDate(phase.start_date) + ' - ' + formatDate(
                                                phase.end_date) + ')'">
                                        </div>
                                    </template>
                                </div>

                                <!-- Etiquetas del timeline -->
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span x-text="startDate ? formatDate(startDate) : ''"></span>
                                    <span x-text="endDate ? formatDate(endDate) : ''"></span>
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
                                                :class="'phase-color-' + (index % 6)"></div>

                                            <!-- Contenido de la fase -->
                                            <div class="flex-1 space-y-3">
                                                <div>
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

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">
                                                            Fecha de Inicio
                                                        </label>
                                                        <input type="date" x-model="phase.start_date"
                                                            @change="updatePhaseValidation(phase)"
                                                            :min="startDate" :max="endDate"
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
                                                            :min="phase.start_date || startDate"
                                                            :max="endDate"
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
                    </div>
                </div>

                <!-- Resto del formulario (Niveles y Áreas) se mantiene igual -->
                <!-- Niveles Educativos -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-blue-600"></i>
                            Niveles Educativos
                        </h2>
                    </div>
                    <div class="p-6">
                        <label for="level_ids" class="block text-sm font-medium text-gray-700 mb-2">Selecciona Niveles</label>
                        <div x-data='{
                            selectedLevels: [],
                            levels: @json($levelsCatalog->values())
                        }'>
                            <select id="level_ids" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" @change="let val = $event.target.value; if(val && !selectedLevels.includes(val)) selectedLevels.push(val); $event.target.value=''">
                                <option value="">-- Selecciona un nivel --</option>
                                <template x-for="level in levels" :key="level.id">
                                    <option :value="level.id" x-show="!selectedLevels.includes(level.id.toString())" x-text="level.nombre"></option>
                                </template>
                            </select>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <template x-for="levelId in selectedLevels" :key="levelId">
                                    <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center gap-2">
                                        <span x-text="levels.find(l => l.id == levelId)?.nombre"></span>
                                        <button type="button" @click="selectedLevels = selectedLevels.filter(l => l !== levelId)" class="text-blue-600 hover:text-red-600">&times;</button>
                                        <input type="hidden" name="level_ids[]" :value="levelId">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Áreas de Conocimiento -->
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm mb-6">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Áreas de Conocimiento</h2>
                    </div>
                    <div class="p-6">
                        <label for="area_ids" class="block text-sm font-medium text-gray-700 mb-2">Selecciona Áreas</label>
                        <div x-data='{
                            selectedAreas: [],
                            areas: @json($areasCatalog->values())
                        }'>
                            <select id="area_ids" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" @change="let val = $event.target.value; if(val && !selectedAreas.includes(val)) selectedAreas.push(val); $event.target.value=''">
                                <option value="">-- Selecciona un área --</option>
                                <template x-for="area in areas" :key="area.id">
                                    <option :value="area.id" x-show="!selectedAreas.includes(area.id.toString())" x-text="area.name"></option>
                                </template>
                            </select>
                            <div class="flex flex-wrap gap-2 mt-2">
                                <template x-for="areaId in selectedAreas" :key="areaId">
                                    <div class="bg-green-100 text-green-800 px-3 py-1 rounded-full flex items-center gap-2">
                                        <span x-text="areas.find(a => a.id == areaId)?.name"></span>
                                        <button type="button" @click="selectedAreas = selectedAreas.filter(a => a !== areaId)" class="text-green-600 hover:text-red-600">&times;</button>
                                        <input type="hidden" name="area_ids[]" :value="areaId">
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <!-- Campos ocultos para enviar fases -->
                <template x-for="(phase, index) in phases" :key="phase.id">
                    <div>
                        <input type="hidden" :name="`phases[${index}][phase_id]`" :value="phase.phase_id">
                        <input type="hidden" :name="`phases[${index}][start_date]`" :value="phase.start_date">
                        <input type="hidden" :name="`phases[${index}][end_date]`" :value="phase.end_date">
                    </div>
                </template>
                <div class="flex justify-end gap-3">
                    <button type="button"
                        class="px-6 py-2 border border-gray-300 text-black rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                        onclick="window.location='{{ route('admin.competicion.index') }}'">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="btn btn-primary  bg-[#091c47]  text-white px-6 py-2 rounded-md">
                        Crear Competencia
                    </button>
                </div>

                <!-- Campos ocultos para rango de fechas -->
                <input type="hidden" name="fechaInicio" :value="startDate">
                <input type="hidden" name="fechaFin" :value="endDate">
            </form>
        </div>
    </body>
@endsection

<script>
    function competitionForm() {
        return {
            competitionName: '',
            startDate: '',
            endDate: '',
            phases: [],
            areas: [],
            levels: [],
            editingPhase: null,
            currentDate: new Date(), // Mes actual

            init() {
                this.addArea();
                this.addLevel();
                // Eliminar la línea que fija septiembre 2025
            },

            get currentMonth() {
                return this.currentDate.getMonth();
            },

            get currentYear() {
                return this.currentDate.getFullYear();
            },

            get currentMonthYear() {
                const month1 = this.currentDate.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long'
                });
                const nextMonth = new Date(this.currentYear, this.currentMonth + 1, 1);
                const month2 = nextMonth.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long'
                });
                return `${month1} - ${month2}`;
            },

            get nextMonth() {
                return new Date(this.currentYear, this.currentMonth + 1, 1);
            },

            get calendarDays1() {
                return this.generateCalendarDays(this.currentYear, this.currentMonth);
            },

            get calendarDays2() {
                return this.generateCalendarDays(this.currentYear, this.currentMonth + 1);
            },

            // Métodos del calendario 
            generateCalendarDays(year, month, isFirstCalendar = true) {
                const firstDay = new Date(year, month, 1);
                const lastDay = new Date(year, month + 1, 0);
                const startDay = firstDay.getDay();
                const daysInMonth = lastDay.getDate();

                const adjustedStartDay = startDay === 0 ? 6 : startDay - 1;

                const days = [];

                // Días del mes anterior
                const prevMonthLastDay = new Date(year, month, 0).getDate();
                for (let i = 0; i < adjustedStartDay; i++) {
                    const day = prevMonthLastDay - adjustedStartDay + i + 1;
                    const date = new Date(year, month - 1, day);
                    
                    const isShared = !isFirstCalendar && month === this.currentMonth && 
                                month - 1 === this.currentMonth - 1;
                    
                    days.push({
                        day: day,
                        date: date.toISOString().split('T')[0],
                        isCurrentMonth: false,
                        isShared: false,
                        isDisabled: true
                    });
                }

                // Días del mes actual
                for (let day = 1; day <= daysInMonth; day++) {
                    const date = new Date(year, month, day);
                    days.push({
                        day: day,
                        date: date.toISOString().split('T')[0],
                        isCurrentMonth: true,
                        isShared: false,
                        isDisabled: false
                    });
                }

                // Completar con días del siguiente mes
                const totalCells = 42;
                const remainingDays = totalCells - days.length;
                for (let day = 1; day <= remainingDays; day++) {
                    const date = new Date(year, month + 1, day);
                    
                    // Verificar si esta fecha aparece en el otro calendario
                    const isShared = isFirstCalendar && day <= 31; // Solo compartido si aparece en octubre
                    
                    days.push({
                        day: day,
                        date: date.toISOString().split('T')[0],
                        isCurrentMonth: false,
                        isShared: isShared,
                        isDisabled: true
                    });
                }

                return days;
            },

            isDateSharedWithOtherCalendar(date, currentCalendarMonth) {
                // Esta función determina si una fecha del siguiente mes
                // aparece también en el segundo calendario
                const dateMonth = date.getMonth();
                const nextCalendarMonth = this.currentMonth + 1;
                
                // Si estamos viendo el primer calendario y la fecha es del mes siguiente
                // que también aparece en el segundo calendario
                if (currentCalendarMonth === this.currentMonth && dateMonth === nextCalendarMonth) {
                    return true;
                }
                
                return false;
            },

            getMonthName(date) {
                if (date instanceof Date) {
                    return date.toLocaleDateString('es-ES', {
                        year: 'numeric',
                        month: 'long'
                    });
                }
                return '';
            },

            previousMonth() {
                this.currentDate = new Date(this.currentYear, this.currentMonth - 1, 1);
            },

            nextMonth() {
                this.currentDate = new Date(this.currentYear, this.currentMonth + 1, 1);
            },

            selectDate(date, isCurrentMonth) {
                // Solo permitir seleccionar días del mes actual
                if (!isCurrentMonth) return;

                // No permitir fechas anteriores a hoy
                const today = new Date();
                today.setHours(0,0,0,0);
                const selected = new Date(date);
                if (selected < today) return;

                // Si ya hay un rango completo seleccionado, empezamos de nuevo
                if (this.startDate && this.endDate) {
                    this.startDate = date;
                    this.endDate = '';
                    this.phases = [];
                    return;
                }
                // Si no hay fecha de inicio, la establecemos
                if (!this.startDate) {
                    this.startDate = date;
                    return;
                }
                // Si hay fecha de inicio pero no de fin
                if (!this.endDate) {
                    const start = new Date(this.startDate);
                    if (selected < start) {
                        this.endDate = this.startDate;
                        this.startDate = date;
                    } else {
                        this.endDate = date;
                    }
                }
            },

            isRangeStart(date) {
                return date === this.startDate;
            },

            isRangeEnd(date) {
                return date === this.endDate;
            },

            isSelected(date) {
                return this.isRangeStart(date) || this.isRangeEnd(date);
            },

            isInRange(date) {
                if (!this.startDate || !this.endDate) return false;

                const currentDate = new Date(date);
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);

                // Incluir todas las fechas entre start y end (excluyendo start y end)
                return currentDate > start && currentDate < end;
            },

            isToday(date) {
                const today = new Date().toISOString().split('T')[0];
                return date === today;
            },

            addPhase() {
                if (!this.startDate || !this.endDate) return;

                const newPhase = {
                    id: 'phase-' + Date.now(),
                    name: 'Fase ' + (this.phases.length + 1),
                    start_date: this.startDate,
                    end_date: this.calculateDefaultEndDate(),
                    errors: []
                };

                this.phases.push(newPhase);
                this.updatePhaseValidation(newPhase);
                this.editingPhase = newPhase.id;
            },

            calculateDefaultEndDate() {
                if (!this.startDate) return '';
                const start = new Date(this.startDate);
                start.setDate(start.getDate() + 7);
                return start.toISOString().split('T')[0];
            },

            removePhase(phaseId) {
                this.phases = this.phases.filter(phase => phase.id !== phaseId);
                if (this.editingPhase === phaseId) {
                    this.editingPhase = null;
                }
            },

            setEditingPhase(phaseId) {
                this.editingPhase = phaseId;
            },

            updatePhaseValidation(phase) {
                phase.errors = [];

                if (!phase.start_date || !phase.end_date) {
                    return;
                }

                const startDate = new Date(this.startDate);
                const endDate = new Date(this.endDate);
                const phaseStart = new Date(phase.start_date);
                const phaseEnd = new Date(phase.end_date);

                // Validar que esté dentro del rango de la competencia
                if (phaseStart < startDate || phaseStart > endDate) {
                    phase.errors.push('La fecha de inicio está fuera del rango de la competencia');
                }

                if (phaseEnd < startDate || phaseEnd > endDate) {
                    phase.errors.push('La fecha de fin está fuera del rango de la competencia');
                }

                // Validar que la fecha de fin sea posterior a la de inicio
                if (phaseStart > phaseEnd) {
                    phase.errors.push('La fecha final debe ser posterior a la fecha de inicio');
                }

                // Validar superposición con otras fases
                const hasOverlap = this.checkPhaseOverlap(phase);
                if (hasOverlap) {
                    phase.errors.push('Esta fase se superpone con otra fase existente');
                }
            },

            checkPhaseOverlap(phaseToCheck) {
                return this.phases.some(phase => {
                    if (phase.id === phaseToCheck.id) return false;

                    const phaseStart = new Date(phase.start_date).getTime();
                    const phaseEnd = new Date(phase.end_date).getTime();
                    const checkStart = new Date(phaseToCheck.start_date).getTime();
                    const checkEnd = new Date(phaseToCheck.end_date).getTime();

                    return (
                        (checkStart >= phaseStart && checkStart <= phaseEnd) ||
                        (checkEnd >= phaseStart && checkEnd <= phaseEnd) ||
                        (checkStart <= phaseStart && checkEnd >= phaseEnd)
                    );
                });
            },

            getPhasePosition(phase) {
                if (!this.startDate || !this.endDate || !phase.start_date || !phase.end_date) {
                    return {
                        left: '0%',
                        width: '0%'
                    };
                }

                const totalDays = this.getTotalDays();
                const start = new Date(this.startDate);
                const phaseStart = new Date(phase.start_date);
                const phaseEnd = new Date(phase.end_date);

                const daysFromStart = Math.max(0, (phaseStart - start) / (1000 * 60 * 60 * 24));
                const phaseDuration = ((phaseEnd - phaseStart) / (1000 * 60 * 60 * 24)) + 1;

                const left = (daysFromStart / totalDays) * 100;
                const width = (phaseDuration / totalDays) * 100;

                return {
                    left: `${Math.max(0, left)}%`,
                    width: `${Math.min(100, width)}%`
                };
            },

            getTotalDays() {
                if (!this.startDate || !this.endDate) return 0;
                const start = new Date(this.startDate);
                const end = new Date(this.endDate);
                return Math.max(1, (end - start) / (1000 * 60 * 60 * 24) + 1);
            },

            formatDate(dateString) {
                if (!dateString) return '';
                const [year, month, day] = dateString.split('-').map(num => parseInt(num));
                const date = new Date(year, month - 1, day);
                return date.toLocaleDateString('es-ES', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            },

            addArea() {
                this.areas.push({
                    id: 'area-' + Date.now(),
                    name: ''
                });
            },

            removeArea(areaId) {
                this.areas = this.areas.filter(area => area.id !== areaId);
            },

            addLevel() {
                this.levels.push({
                    id: 'level-' + Date.now(),
                    name: ''
                });
            },

            removeLevel(levelId) {
                this.levels = this.levels.filter(level => level.id !== levelId);
            },

            submitForm() {
                // Validaciones básicas
                if (!this.competitionName) {
                    alert('Por favor ingresa el nombre de la competencia');
                    return;
                }

                if (!this.startDate || !this.endDate) {
                    alert('Por favor selecciona las fechas de la competencia');
                    return;
                }

                if (this.phases.length === 0) {
                    alert('Por favor agrega al menos una fase');
                    return;
                }

                // Validar que todas las fases sean válidas
                const hasInvalidPhases = this.phases.some(phase => phase.errors.length > 0);
                if (hasInvalidPhases) {
                    alert('Por favor corrige los errores en las fases antes de continuar');
                    return;
                }

                if (this.areas.length === 0) {
                    alert('Por favor agrega al menos un área');
                    return;
                }

                if (this.levels.length === 0) {
                    alert('Por favor agrega al menos un nivel');
                    return;
                }

                // Enviar formulario
                this.$el.submit();
            }
        }
    }
</script>

<style>
    [x-cloak] {
        display: none !important;
    }

    .phase-color-0 {
        background-color: #3b82f6;
    }

    /* blue-500 */
    .phase-color-1 {
        background-color: #8b5cf6;
    }

    /* purple-500 */
    .phase-color-2 {
        background-color: #ec4899;
    }

    /* pink-500 */
    .phase-color-3 {
        background-color: #f97316;
    }

    /* orange-500 */
    .phase-color-4 {
        background-color: #14b8a6;
    }

    /* teal-500 */
    .phase-color-5 {
        background-color: #6366f1;
    }

    /* indigo-500 */

    .calendar-day {
        @apply w-8 h-8 flex items-center justify-center text-sm border border-gray-200 rounded cursor-pointer transition-colors;
    }

    .calendar-day:hover:not(.disabled) {
        @apply bg-blue-100 border-blue-300;
    }

    .calendar-day.selected {
        background-color: #2563eb !important;
        /* blue-700 */
        color: #fff !important;
        border-color: #1d4ed8 !important;
        /* blue-800 */
        font-weight: 700;
        box-shadow: 0 0 0 3px #1d4ed8cc, 0 2px 8px #1d4ed822;
        z-index: 2;
    }

    .calendar-day.range-start {
        background-color: #091c47 !important;
        /* blue-800 */
        color: #fff !important;
        border-color: #1e40af !important;
        /* blue-900 */
        font-weight: 700;
        box-shadow: 0 0 0 3px #1d4ed8cc, 0 2px 8px #1d4ed822;
        z-index: 3;
    }

    .calendar-day.range-end {
        background-color: #091c47 !important;
        /* blue-800 */
        color: #fff !important;
        border-color: #1e40af !important;
        /* blue-900 */
        font-weight: 700;
        box-shadow: 0 0 0 3px #1d4ed8cc, 0 2px 8px #1d4ed822;
        z-index: 3;
    }

    .calendar-day.range {
        background-color: #dbeafe !important;
        /* blue-100 */
        border-color: #93c5fd !important;
        /* blue-300 */
        color: #1e40af !important;
        /* blue-900 */
        font-weight: 500;
        box-shadow: none;
        z-index: 1;
    }

    .calendar-day.other-month {
        @apply text-gray-400 bg-gray-50 cursor-not-allowed;
    }

    .calendar-day.other-month:hover {
        @apply bg-gray-50 border-gray-200;
    }

    .calendar-day.today {
        @apply border-blue-500 font-semibold bg-blue-50;
    }

    .calendar-day.disabled {
        @apply text-gray-300 cursor-not-allowed bg-gray-50;
        opacity: 0.5;
        filter: blur(0.5px);
        pointer-events: none;
        transition: opacity 0.2s, filter 0.2s;
    }

    .calendar-day.disabled:hover {
        @apply bg-gray-50 border-gray-200;
        opacity: 0.5;
        filter: blur(0.5px);
    }
</style>
