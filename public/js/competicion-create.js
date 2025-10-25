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
            if (this.isPast(date)) return;

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

            // Incluir todas las fechas entre start y end (excluyendo start y end)
            return currentDate > start && currentDate < end;
        },

        isToday(date) {
            const today = new Date().toISOString().split('T')[0];
            return date === today;
        },

        isPast(date) {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const dateObj = new Date(date);
            return dateObj < today;
        },

        addPhase() {
            if (!this.startDate || !this.endDate) return;

            const newPhase = {
                id: 'phase-' + Date.now(),
                name: 'Fase ' + (this.phases.length + 1),
                phase_id: '',
                start_date: this.startDate,
                end_date: this.calculateDefaultEndDate(),
                clasificados: '',
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
                return false;
            }

            if (!this.startDate || !this.endDate) {
                alert('Por favor selecciona las fechas de la competencia');
                return false;
            }

            if (this.phases.length === 0) {
                alert('Por favor agrega al menos una fase');
                return false;
            }

            // Validar que todas las fases sean válidas
            const hasInvalidPhases = this.phases.some(phase => phase.errors.length > 0);
            if (hasInvalidPhases) {
                alert('Por favor corrige los errores en las fases antes de continuar');
                return false;
            }

            // Permitir envío del formulario
            return true;
        }
    }
}

// Función global para validar el formulario
function validateForm() {
    // Verificar que se haya seleccionado al menos un área
    const areaInputs = document.querySelectorAll('input[name^="area_ids["]');
    if (areaInputs.length === 0) {
        alert('Por favor selecciona al menos un área');
        return false;
    }

    // Verificar que se hayan configurado las fases
    const phaseInputs = document.querySelectorAll('input[name^="phases["]');
    if (phaseInputs.length === 0) {
        alert('Por favor configura al menos una fase');
        return false;
    }

    return true;
}
