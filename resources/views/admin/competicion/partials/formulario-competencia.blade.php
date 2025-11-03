<div
    x-data="competitionForm({
        modo: '{{ $modo }}',
        competencia: @json($modo === 'editar' ? $competicion : null),
        fasesCatalog: @json($fasesCatalog->values()),
        levelsCatalog: @json($levelsCatalog->values()),
        areasCatalog: @json($areasCatalog->values())
    })"
    x-init="init()"
>
<form method="POST" action="{{ $modo === 'editar' ? route('admin.competicion.update', $competicion->id) : route('admin.competicion.store') }}" @submit.prevent="submitForm">
    @csrf
    @if($modo === 'editar')
        @method('PUT')
    @endif

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
                    </script>
                    </div>
                    <script>
                    function competitionForm({ modo, competencia, fasesCatalog, levelsCatalog, areasCatalog }) {
        submitForm() {
            // Validar fechas antes de enviar
            if (!this.startDate || !this.endDate) {
                alert('Por favor selecciona el rango de fechas de la competencia.');
                return;
            }
            // Sincronizar los campos ocultos
            const form = this.$el.querySelector('form');
            form.querySelector('input[name=fechaInicio]').value = this.startDate;
            form.querySelector('input[name=fechaFin]').value = this.endDate;
            form.submit();
        },
                        // Reutiliza la lógica de crear, pero inicializa datos si es edición
                        return {
                            competitionName: modo === 'editar' && competencia ? competencia.name : '',
                            competitionDescription: modo === 'editar' && competencia ? competencia.description : '',
                            startDate: modo === 'editar' && competencia ? (competencia.fechaInicio ? competencia.fechaInicio.substring(0, 10) : '') : '',
                            endDate: modo === 'editar' && competencia ? (competencia.fechaFin ? competencia.fechaFin.substring(0, 10) : '') : '',
                            phases: modo === 'editar' && competencia ? (competencia.phases || []).map((f, idx) => ({
                                id: idx + 1,
                                phase_id: f.id,
                                name: f.name,
                                start_date: f.pivot && f.pivot.start_date ? f.pivot.start_date.substring(0, 10) : '',
                                end_date: f.pivot && f.pivot.end_date ? f.pivot.end_date.substring(0, 10) : '',
                                errors: []
                            })) : [],
                            areas: [],
                            levels: [],
                            selectedLevels: modo === 'editar' && competencia ? (competencia.levels || []).map(l => l.id.toString()) : [],
                            selectedAreas: modo === 'editar' && competencia ? (competencia.areas || []).map(a => a.id.toString()) : [],
                            editingPhase: null,
                            currentDate: null,
                            init() {
                                if (modo === 'editar' && this.startDate) {
                                    const [y, m] = this.startDate.split('-');
                                    this.currentDate = new Date(Number(y), Number(m) - 1, 1);
                                } else {
                                    this.currentDate = new Date();
                                }
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
                                    const isShared = isFirstCalendar && day <= 31;
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
                            previousMonth() {
                                this.currentDate = new Date(this.currentYear, this.currentMonth - 1, 1);
                            },
                            nextMonth() {
                                this.currentDate = new Date(this.currentYear, this.currentMonth + 1, 1);
                            },
                            selectDate(date, isCurrentMonth) {
                                if (!isCurrentMonth) return;
                                if (this.startDate && this.endDate) {
                                    this.startDate = date;
                                    this.endDate = '';
                                    this.phases = [];
                                    return;
                                }
                                if (!this.startDate) {
                                    this.startDate = date;
                                    return;
                                }
                                if (!this.endDate) {
                                    const start = new Date(this.startDate);
                                    const selected = new Date(date);
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
                                return currentDate > start && currentDate < end;
                            },
                            isToday(date) {
                                const today = new Date().toISOString().split('T')[0];
                                return date === today;
                            },
                            getMonthName(date) {
                                if (date instanceof Date) {
                                    return date.toLocaleDateString('es-ES', {
                                        year: 'numeric',
                                        month: 'long'
                                    });
                                }
                                // Si recibe un número de mes
                                if (typeof date === 'number') {
                                    const d = new Date(this.currentYear, date, 1);
                                    return d.toLocaleDateString('es-ES', {
                                        year: 'numeric',
                                        month: 'long'
                                    });
                                }
                                return '';
                            },
                            // Métodos utilitarios y de timeline
                            formatDate(date) {
                                if (!date) return '';
                                const d = new Date(date);
                                return d.toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: '2-digit' });
                            },
                            getTotalDays() {
                                if (!this.startDate || !this.endDate) return 0;
                                const start = new Date(this.startDate);
                                const end = new Date(this.endDate);
                                return Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
                            },
                            addPhase() {
                                const id = this.phases.length ? Math.max(...this.phases.map(f => f.id)) + 1 : 1;
                                this.phases.push({
                                    id,
                                    phase_id: '',
                                    name: '',
                                    start_date: '',
                                    end_date: '',
                                    errors: []
                                });
                            },
                            removePhase(id) {
                                this.phases = this.phases.filter(f => f.id !== id);
                            },
                            setEditingPhase(id) {
                                this.editingPhase = id;
                            },
                            getPhasePosition(phase) {
                                if (!this.startDate || !this.endDate || !phase.start_date || !phase.end_date) return '';
                                const total = (new Date(this.endDate) - new Date(this.startDate)) / (1000 * 60 * 60 * 24);
                                const start = (new Date(phase.start_date) - new Date(this.startDate)) / (1000 * 60 * 60 * 24);
                                const end = (new Date(phase.end_date) - new Date(this.startDate)) / (1000 * 60 * 60 * 24);
                                const left = (start / total) * 100;
                                const width = ((end - start + 1) / (total + 1)) * 100;
                                return `left: ${left}%; width: ${width}%; background-color: #2563eb;`;
                            },
                            updatePhaseValidation(phase) {
                                phase.errors = [];
                                if (!phase.start_date || !phase.end_date) return;
                                if (phase.start_date > phase.end_date) {
                                    phase.errors.push('La fecha de inicio debe ser menor o igual a la fecha de fin.');
                                }
                                if (phase.start_date < this.startDate || phase.end_date > this.endDate) {
                                    phase.errors.push('Las fechas de la fase deben estar dentro del rango de la competencia.');
                                }
                            },
                        };
                    }
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
            {{ $modo === 'editar' ? 'Actualizar' : 'Crear Competencia' }}
        </button>
    </div>
    <!-- Campos ocultos para rango de fechas -->
    <input type="hidden" name="fechaInicio" :value="startDate">
    <input type="hidden" name="fechaFin" :value="endDate">
    </form>
</div>
<script>
function competitionForm({ modo, competencia, fasesCatalog, levelsCatalog, areasCatalog }) {
    // Reutiliza la lógica de crear, pero inicializa datos si es edición
    return {
        competitionName: modo === 'editar' && competencia ? competencia.name : '',
        competitionDescription: modo === 'editar' && competencia ? competencia.description : '',
        startDate: modo === 'editar' && competencia ? (competencia.fechaInicio ? competencia.fechaInicio.substring(0, 10) : '') : '',
        endDate: modo === 'editar' && competencia ? (competencia.fechaFin ? competencia.fechaFin.substring(0, 10) : '') : '',
        phases: modo === 'editar' && competencia ? (competencia.phases || []).map((f, idx) => ({
            id: idx + 1,
            phase_id: f.id,
            name: f.name,
            start_date: f.pivot && f.pivot.start_date ? f.pivot.start_date.substring(0, 10) : '',
            end_date: f.pivot && f.pivot.end_date ? f.pivot.end_date.substring(0, 10) : '',
            errors: []
        })) : [],
        areas: [],
        levels: [],
        selectedLevels: modo === 'editar' && competencia ? (competencia.levels || []).map(l => l.id.toString()) : [],
        selectedAreas: modo === 'editar' && competencia ? (competencia.areas || []).map(a => a.id.toString()) : [],
        editingPhase: null,
        currentDate: null,
        init() {
            if (modo === 'editar' && this.startDate) {
                const [y, m] = this.startDate.split('-');
                this.currentDate = new Date(Number(y), Number(m) - 1, 1);
            } else {
                this.currentDate = new Date();
            }
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
                const isShared = isFirstCalendar && day <= 31;
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
        previousMonth() {
            this.currentDate = new Date(this.currentYear, this.currentMonth - 1, 1);
        },
        nextMonth() {
            this.currentDate = new Date(this.currentYear, this.currentMonth + 1, 1);
        },
        selectDate(date, isCurrentMonth) {
            if (!isCurrentMonth) return;
            if (this.startDate && this.endDate) {
                this.startDate = date;
                this.endDate = '';
                this.phases = [];
                return;
            }
            if (!this.startDate) {
                this.startDate = date;
                return;
            }
            if (!this.endDate) {
                const start = new Date(this.startDate);
                const selected = new Date(date);
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
            return currentDate > start && currentDate < end;
        },
        isToday(date) {
            const today = new Date().toISOString().split('T')[0];
            return date === today;
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
        // Métodos utilitarios y de timeline
        formatDate(date) {
            if (!date) return '';
            const d = new Date(date);
            return d.toLocaleDateString('es-ES', { year: 'numeric', month: 'short', day: '2-digit' });
        },
        getTotalDays() {
            if (!this.startDate || !this.endDate) return 0;
            const start = new Date(this.startDate);
            const end = new Date(this.endDate);
            return Math.floor((end - start) / (1000 * 60 * 60 * 24)) + 1;
        },
        addPhase() {
            const id = this.phases.length ? Math.max(...this.phases.map(f => f.id)) + 1 : 1;
            this.phases.push({
                id,
                phase_id: '',
                name: '',
                start_date: '',
                end_date: '',
                errors: []
            });
        },
        removePhase(id) {
            this.phases = this.phases.filter(f => f.id !== id);
        },
        setEditingPhase(id) {
            this.editingPhase = id;
        },
        getPhasePosition(phase) {
            if (!this.startDate || !this.endDate || !phase.start_date || !phase.end_date) return '';
            const total = (new Date(this.endDate) - new Date(this.startDate)) / (1000 * 60 * 60 * 24);
            const start = (new Date(phase.start_date) - new Date(this.startDate)) / (1000 * 60 * 60 * 24);
            const end = (new Date(phase.end_date) - new Date(this.startDate)) / (1000 * 60 * 60 * 24);
            const left = (start / total) * 100;
            const width = ((end - start + 1) / (total + 1)) * 100;
            return `left: ${left}%; width: ${width}%; background-color: #2563eb;`;
        },
        updatePhaseValidation(phase) {
            phase.errors = [];
            if (!phase.start_date || !phase.end_date) return;
            if (phase.start_date > phase.end_date) {
                phase.errors.push('La fecha de inicio debe ser menor o igual a la fecha de fin.');
            }
            if (phase.start_date < this.startDate || phase.end_date > this.endDate) {
                phase.errors.push('Las fechas de la fase deben estar dentro del rango de la competencia.');
            }
        },
    };
}
</script>
