@extends('layouts.app')
@section('title', 'Nueva Inscripción')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center mb-4">
            <a href="{{ route('estudiante.inscripcion.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Nueva Inscripción</h1>
                <p class="text-gray-600">Completa el formulario para inscribirte a la competencia</p>
            </div>
        </div>
    </div>

    <!-- Información de la Competencia -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-start justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">{{ $competencia->name }}</h2>
                <p class="text-blue-100 mb-4">{{ $competencia->description }}</p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>
                            <strong>Inicio:</strong> {{ $competencia->fechaInicio->format('d/m/Y') }}
                        </span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-check mr-2"></i>
                        <span>
                            <strong>Fin:</strong> {{ $competencia->fechaFin->format('d/m/Y') }}
                        </span>
                    </div>
                </div>

                @if($competencia->phases->isNotEmpty())
                    <div class="mt-4">
                        <p class="font-semibold mb-2">Fases de la competencia:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($competencia->phases as $phase)
                                <span class="px-3 py-1 bg-white bg-opacity-20 rounded-full text-sm">
                                    {{ $phase->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="hidden md:block">
                <i class="fas fa-trophy text-6xl text-white opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Formulario de Inscripción -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-6">Datos de Inscripción</h3>
        
        <form id="formInscripcion" method="POST" action="{{ route('estudiante.inscripcion.inscribir', $competencia->id) }}">
            @csrf
            
            <!-- Categoría (primero) -->
            <div class="mb-6">
                <label for="categoria_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Categoría <span class="text-red-500">*</span>
                </label>
                <select id="categoria_id" name="categoria_id" required 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Seleccione una categoría</option>
                    @foreach($categorias as $categoria)
                        <option value="{{ $categoria->id }}" data-nombre="{{ $categoria->nombre }}">{{ $categoria->nombre }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-sm text-gray-500">Selecciona la categoría correspondiente</p>
            </div>

            <!-- Área (dependiente de la categoría) -->
            <div class="mb-6">
                <label for="area_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Área de Competencia <span class="text-red-500">*</span>
                </label>
                <select id="area_id" name="area_id" required disabled
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    <option value="">Seleccione una categoría primero</option>
                </select>
                <p class="mt-1 text-sm text-gray-500">Selecciona el área en la que deseas competir</p>
            </div>

            <!-- Nombre del Grupo (oculto inicialmente) -->
            <div id="grupoNombreContainer" class="mb-6 hidden">
                <label for="name_grupo" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nombre del Grupo <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name_grupo" name="name_grupo" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                       placeholder="Ingrese el nombre del equipo">
                <p class="mt-1 text-sm text-gray-500">Nombre que identificará a tu equipo</p>
            </div>

            <!-- Observaciones -->
            <div class="mb-6">
                <label for="observaciones_estudiante" class="block text-sm font-semibold text-gray-700 mb-2">
                    Observaciones
                </label>
                <textarea id="observaciones_estudiante" name="observaciones_estudiante" rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                          placeholder="Información adicional que consideres relevante (opcional)"></textarea>
                <p class="mt-1 text-sm text-gray-500">Puedes agregar cualquier información adicional</p>
            </div>

            <!-- Información importante -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Importante:</strong> Una vez enviada tu inscripción, quedará en estado "Pendiente" hasta que sea revisada y confirmada por los administradores.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('estudiante.inscripcion.index') }}" 
                   class="px-6 py-3 bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold rounded-lg transition-colors duration-200">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                    <i class="fas fa-check mr-2"></i>
                    Confirmar Inscripción
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Animación suave para mostrar/ocultar el campo de grupo */
    #grupoNombreContainer {
        transition: all 0.3s ease-in-out;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Mapeo categoria_id => [ {id, name}, ... ] creado desde los pares enviados por el controlador
    @php
        $mapping = [];
        if (isset($categoryAreas)) {
            $mapping = collect($categoryAreas)
                ->groupBy('categoria_id')
                ->map(function($group) {
                    return $group->map(function($item) {
                        return ['id' => $item->area->id, 'name' => $item->area->name];
                    })->values();
                })->toArray();
        }
    @endphp
    const categoryAreasMap = @json($mapping);

    // Cuando cambie la categoría: poblar el select de áreas y manejar campo de grupo
    document.getElementById('categoria_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const categoriaId = this.value;
        const nombre = selectedOption ? selectedOption.getAttribute('data-nombre') : null;
        const container = document.getElementById('grupoNombreContainer');
        const grupoNombreInput = document.getElementById('name_grupo');

        // Mostrar u ocultar campo de grupo según nombre de categoría
        if (nombre && nombre.toLowerCase() === 'grupal') {
            container.classList.remove('hidden');
            grupoNombreInput.required = true;
        } else {
            container.classList.add('hidden');
            grupoNombreInput.required = false;
            grupoNombreInput.value = '';
        }

        // Poblar áreas relacionadas a la categoría seleccionada
        const areaSelect = document.getElementById('area_id');
        // Limpiar opciones
        areaSelect.innerHTML = '';
        // Manejar claves numéricas/strings en el mapping
        const areasForCat = (categoryAreasMap[categoriaId] || categoryAreasMap[Number(categoriaId)]) || [];
        console.log('categoria change ->', categoriaId, 'areasForCat:', areasForCat);

        if (!categoriaId || areasForCat.length === 0) {
            areaSelect.setAttribute('disabled', 'disabled');
            areaSelect.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = '';
            opt.text = 'No hay áreas disponibles para esta categoría';
            areaSelect.appendChild(opt);
            return;
        }

        // Agregar opción por defecto
        areaSelect.innerHTML = '';
        const placeholder = document.createElement('option');
        placeholder.value = '';
        placeholder.text = 'Seleccione un área';
        areaSelect.appendChild(placeholder);

        // Agregar las áreas asociadas
        areasForCat.forEach(function(area) {
            const opt = document.createElement('option');
            opt.value = area.id;
            opt.text = area.name;
            areaSelect.appendChild(opt);
        });

        areaSelect.removeAttribute('disabled');
        areaSelect.disabled = false;
        console.log('areaSelect enabled, options count:', areaSelect.options.length);
        // poner foco para facilitar selección
        try { areaSelect.focus(); } catch(e) {}

        // Actualizar estado del botón de envío
        const submitBtn = document.querySelector('#formInscripcion button[type="submit"]');
        submitBtn.disabled = areaSelect.disabled || !areaSelect.value;
    });

    // Cuando cambie el select de área, ajustar el estado del botón de envío
    document.getElementById('area_id').addEventListener('change', function() {
        const submitBtn = document.querySelector('#formInscripcion button[type="submit"]');
        submitBtn.disabled = this.disabled || !this.value;
    });

    // Manejar el envío del formulario
    document.getElementById('formInscripcion').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = {
            area_id: formData.get('area_id'),
            categoria_id: formData.get('categoria_id'),
            name_grupo: formData.get('name_grupo'),
            observaciones_estudiante: formData.get('observaciones_estudiante'),
            _token: formData.get('_token')
        };

        // Deshabilitar el botón de envío
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Procesando...';

        fetch(this.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': data._token,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Inscripción exitosa!',
                    text: data.message,
                    confirmButtonColor: '#3B82F6'
                }).then(() => {
                    window.location.href = '{{ route("estudiante.inscripcion.index") }}';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonColor: '#EF4444'
                });
                // Rehabilitar el botón
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Confirmar Inscripción';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la inscripción',
                confirmButtonColor: '#EF4444'
            });
            // Rehabilitar el botón
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Confirmar Inscripción';
        });
    });
</script>
@endpush
