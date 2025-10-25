@extends('layouts.app')

@section('title', 'Agregar Fase')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/competicion-create.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/competicion-create.js') }}"></script>
@endpush

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
                        @include('components.calendar-date-range-picker')

                        <!-- Timeline Visualization -->
                        @include('components.phases-timeline')
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
                        <template x-for="(item, index) in seleccionados" :key="index">
                            <input type="hidden" :name="`area_ids[${index}]`" :value="item.areaId">
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
                        <input type="hidden" :name="`phases[${index}][clasificados]`" :value="phase.clasificados">
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
