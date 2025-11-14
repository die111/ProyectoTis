<!-- Calendario Visual para Selección de Rango de Fechas -->
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
                                'disabled': !day.isCurrentMonth,
                                'past': day.isCurrentMonth && isPast(day.date)
                            }"
                            @click="selectDate(day.date, day.isCurrentMonth)" x-text="day.day"
                            :disabled="!day.isCurrentMonth || isPast(day.date)"></button>
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
                                'disabled': !day.isCurrentMonth,
                                'past': day.isCurrentMonth && isPast(day.date)
                            }"
                            @click="selectDate(day.date, day.isCurrentMonth)" x-text="day.day"
                            :disabled="!day.isCurrentMonth || isPast(day.date)"></button>
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
