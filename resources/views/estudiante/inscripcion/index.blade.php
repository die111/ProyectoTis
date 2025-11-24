@extends('layouts.app')
@section('title', 'Inscripción a Competencias')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Inscripción a Competencias</h1>
        <p class="text-gray-600">Inscríbete a las competencias activas disponibles</p>
    </div>

    <!-- Pestañas -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex -mb-px space-x-8" aria-label="Tabs">
            <button id="tabCompetencias" class="tab-button active border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                Competencias Disponibles
            </button>
            <button id="tabMisInscripciones" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Mis Inscripciones
            </button>
        </nav>
    </div>

    <!-- Contenido: Competencias Disponibles -->
    <div id="contentCompetencias" class="tab-content">
        @if($competenciasActivas->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-info-circle text-yellow-500 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">No hay competencias activas</h3>
                <p class="text-yellow-700">Por el momento no hay competencias disponibles para inscripción.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($competenciasActivas as $competencia)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                            <h3 class="text-xl font-bold text-white mb-1">{{ $competencia->name }}</h3>
                            <p class="text-blue-100 text-sm">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $competencia->fechaInicio->format('d/m/Y') }} - {{ $competencia->fechaFin->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <div class="p-4">
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($competencia->description, 100) }}</p>
                            
                            @if($competencia->area)
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $competencia->area->name }}
                                    </span>
                                </div>
                            @endif

                            @if($competencia->phases->isNotEmpty())
                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-gray-700 mb-1">Fases:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($competencia->phases->take(3) as $phase)
                                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                {{ $phase->name }}
                                            </span>
                                        @endforeach
                                        @if($competencia->phases->count() > 3)
                                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                +{{ $competencia->phases->count() - 3 }} más
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <button onclick="window.location.href='{{ route('estudiante.inscripcion.create', $competencia->id) }}'" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-file-signature mr-2"></i>
                                Inscribirse
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Contenido: Mis Inscripciones -->
    <div id="contentMisInscripciones" class="tab-content hidden">
        @if($misInscripciones->isEmpty())
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                <i class="fas fa-clipboard-list text-gray-400 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No tienes inscripciones</h3>
                <p class="text-gray-600">Aún no te has inscrito a ninguna competencia.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nivel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Inscripción</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($misInscripciones as $inscripcion)
                            <tr class="hover:bg-gray-50 inscripcion-row" data-inscripcion-id="{{ $inscripcion->id }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $inscripcion->competition->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $inscripcion->area->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $inscripcion->level->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($inscripcion->estado === 'pendiente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pendiente
                                        </span>
                                    @elseif($inscripcion->estado === 'confirmada')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Confirmada
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Rechazada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $inscripcion->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button onclick="window.location.href='{{ route('estudiante.inscripcion.detalle', $inscripcion->id) }}'" 
                                            class="text-blue-600 hover:text-blue-800 font-medium">
                                        <i class="fas fa-eye mr-1"></i>Ver Detalle
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Detalle de Inscripción -->
<div id="modalDetalleInscripcion" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detalle de Inscripción</h3>
                <button onclick="cerrarModalDetalle()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="contenidoDetalleInscripcion" class="space-y-4">
                <!-- Se llenará dinámicamente con JavaScript -->
            </div>

            <div class="mt-6 flex justify-end">
                <button onclick="cerrarModalDetalle()" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md transition-colors">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tab-button.active {
        border-color: #3B82F6;
        color: #3B82F6;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const inscripciones = @json($misInscripciones);

    // Cambio de pestañas
    document.getElementById('tabCompetencias').addEventListener('click', function() {
        document.getElementById('contentCompetencias').classList.remove('hidden');
        document.getElementById('contentMisInscripciones').classList.add('hidden');
        this.classList.add('active', 'border-blue-500', 'text-blue-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tabMisInscripciones').classList.remove('active', 'border-blue-500', 'text-blue-600');
        document.getElementById('tabMisInscripciones').classList.add('border-transparent', 'text-gray-500');
    });

    document.getElementById('tabMisInscripciones').addEventListener('click', function() {
        document.getElementById('contentMisInscripciones').classList.remove('hidden');
        document.getElementById('contentCompetencias').classList.add('hidden');
        this.classList.add('active', 'border-blue-500', 'text-blue-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tabCompetencias').classList.remove('active', 'border-blue-500', 'text-blue-600');
        document.getElementById('tabCompetencias').classList.add('border-transparent', 'text-gray-500');
    });

    // Función para ver detalle de inscripción (incluye notas por fase y reclamos)
    function verDetalleInscripcion(id) {
        const inscripcion = inscripciones.find(i => i.id === id);
        if (!inscripcion) return;

        let html = `
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Competencia:</p>
                        <p class="text-sm text-gray-900">${inscripcion.competition ? inscripcion.competition.name : '—'}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Área:</p>
                        <p class="text-sm text-gray-900">${inscripcion.area ? inscripcion.area.name : '—'}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Nivel:</p>
                        <p class="text-sm text-gray-900">${inscripcion.level ? inscripcion.level.name : 'N/A'}</p>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Tipo:</p>
                        <p class="text-sm text-gray-900">${inscripcion.es_grupal ? 'Grupal' : 'Individual'}</p>
                    </div>
                    ${inscripcion.grupo_nombre ? `
                    <div class="col-span-2">
                        <p class="text-sm font-semibold text-gray-700">Nombre del Grupo:</p>
                        <p class="text-sm text-gray-900">${inscripcion.grupo_nombre}</p>
                    </div>
                    ` : ''}
                    <div class="col-span-2">
                        <p class="text-sm font-semibold text-gray-700">Estado:</p>
                        <p class="text-sm">
                            ${inscripcion.estado === 'pendiente' ? 
                                '<span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-medium"><i class="fas fa-clock mr-1"></i>Pendiente</span>' : 
                                inscripcion.estado === 'confirmada' ? 
                                '<span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium"><i class="fas fa-check mr-1"></i>Confirmada</span>' : 
                                '<span class="px-3 py-1 bg-red-100 text-red-800 rounded-full font-medium"><i class="fas fa-times mr-1"></i>Rechazada</span>'}
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm font-semibold text-gray-700">Fecha de Inscripción:</p>
                        <p class="text-sm text-gray-900">${new Date(inscripcion.created_at).toLocaleString('es-BO')}</p>
                    </div>
                </div>

                <div id="inscripcion-evaluaciones-${inscripcion.id}" class="mt-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Notas por Fase:</p>
                    <div class="bg-gray-50 border border-gray-200 rounded p-3">
                        <p class="text-sm text-gray-600">Cargando notas...</p>
                    </div>
                </div>

                <div id="inscripcion-reclamo-${inscripcion.id}" class="mt-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">¿Deseas reclamar una nota?</p>
                    <form id="form-reclamo-${inscripcion.id}" method="POST" action="/inscripcion/${inscripcion.id}/reclamar">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="fase_id" value="">
                        <input type="hidden" name="evaluation_id" value="">
                        <div class="mb-2">
                            <textarea name="mensaje" rows="3" class="w-full border rounded p-2" placeholder="Describe tu reclamo o indica que no se subió la nota"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded">Enviar Reclamo</button>
                        </div>
                    </form>
                </div>
            </div>
        `;

        // Agregar observaciones del estudiante si existen
        if (inscripcion.observaciones_estudiante) {
            html += `
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Tus Observaciones:</p>
                    <div class="bg-blue-50 border border-blue-200 rounded p-3">
                        <p class="text-sm text-gray-900">${inscripcion.observaciones_estudiante}</p>
                    </div>
                </div>
            `;
        }

        // Agregar motivo de rechazo si existe
        if (inscripcion.estado === 'rechazada' && inscripcion.motivo_rechazo) {
            html += `
                <div class="mt-4">
                    <p class="text-sm font-semibold text-gray-700 mb-2">Motivo del Rechazo:</p>
                    <div class="bg-red-50 border border-red-200 rounded p-3">
                        <p class="text-sm text-gray-900">${inscripcion.motivo_rechazo}</p>
                    </div>
                </div>
            `;
        }

        document.getElementById('contenidoDetalleInscripcion').innerHTML = html;
        document.getElementById('modalDetalleInscripcion').classList.remove('hidden');

        // Cargar evaluaciones vía AJAX
        fetch(`/inscripcion/${inscripcion.id}/evaluaciones`)
            .then(r => r.json())
            .then(data => {
                const container = document.getElementById(`inscripcion-evaluaciones-${inscripcion.id}`);
                if (!data.phases || data.phases.length === 0) {
                    container.innerHTML = `<div class="bg-gray-50 border border-gray-200 rounded p-3"><p class="text-sm text-gray-600">No hay fases o notas disponibles.</p></div>`;
                    return;
                }

                let htmlFases = '<div class="space-y-3">';
                data.phases.forEach(p => {
                    htmlFases += `<div class="p-3 border rounded">`;
                    htmlFases += `<div class="flex justify-between items-center mb-2"><div class="font-medium text-sm">Fase ${p.fase_numero} - ${p.fase_nombre}</div>`;
                    if (p.evaluacion) {
                        htmlFases += `<div class="text-sm text-gray-700">Nota: <strong>${p.evaluacion.nota}</strong> — Estado: ${p.evaluacion.estado}</div>`;
                    } else {
                        htmlFases += `<div class="text-sm text-gray-500">No se subió la nota aún</div>`;
                    }
                    htmlFases += `</div>`;

                    if (p.evaluacion && p.evaluacion.observaciones_evaluador) {
                        htmlFases += `<div class="text-sm text-gray-600 mb-2">Observaciones del evaluador: ${p.evaluacion.observaciones_evaluador}</div>`;
                    }

                    // Botones para rellenar el form de reclamo (si existe nota o no)
                    htmlFases += `<div class="flex gap-2">`;
                    htmlFases += `<button type="button" class="px-3 py-1 bg-blue-500 text-white rounded text-sm" onclick="abrirReclamoForm(${inscripcion.id}, ${p.fase_id}, ${p.evaluacion ? p.evaluacion.id : 'null'})">Reclamar</button>`;
                    htmlFases += `</div>`;

                    htmlFases += `</div>`;
                });
                htmlFases += '</div>';
                container.innerHTML = htmlFases;
            })
            .catch(() => {
                const container = document.getElementById(`inscripcion-evaluaciones-${inscripcion.id}`);
                container.innerHTML = `<div class="bg-gray-50 border border-gray-200 rounded p-3"><p class="text-sm text-gray-600">Error al cargar las notas.</p></div>`;
            });
    }

    function cerrarModalDetalle() {
        document.getElementById('modalDetalleInscripcion').classList.add('hidden');
    }

    // Abrir formulario de reclamo y llenar campos ocultos
    function abrirReclamoForm(inscripcionId, faseId, evaluationId) {
        const form = document.getElementById(`form-reclamo-${inscripcionId}`);
        if (!form) return;
        form.querySelector('input[name="fase_id"]').value = faseId || '';
        form.querySelector('input[name="evaluation_id"]').value = evaluationId || '';
        // Scroll al form
        document.getElementById(`inscripcion-reclamo-${inscripcionId}`).scrollIntoView({ behavior: 'smooth' });
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalDetalleInscripcion').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalDetalle();
        }
    });

    // Auto-abrir desde notificaciones
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const inscripcionId = urlParams.get('inscripcion_id');
        
        if (inscripcionId) {
            // Cambiar a la pestaña de Mis Inscripciones
            document.getElementById('tabMisInscripciones').click();
            
            // Esperar un momento para que la pestaña cambie
            setTimeout(() => {
                // Resaltar la fila
                const fila = document.querySelector(`tr[data-inscripcion-id="${inscripcionId}"]`);
                if (fila) {
                    fila.classList.add('bg-yellow-50', 'border-2', 'border-yellow-400');
                    fila.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                // Abrir el modal de detalle
                verDetalleInscripcion(parseInt(inscripcionId));
                
                // Limpiar el parámetro de la URL
                window.history.replaceState({}, document.title, window.location.pathname);
            }, 300);
        }
    });

    // Mostrar mensajes de sesión (si existen)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3B82F6'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#EF4444'
        });
    @endif
</script>
@endpush
