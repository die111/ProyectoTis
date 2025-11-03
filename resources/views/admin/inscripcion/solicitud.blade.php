@extends('layouts.app')

@section('title', 'Solicitudes de Inscripción')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Solicitudes de Inscripción
            </h1>
            <p class="text-gray-600">
                Gestiona las solicitudes de inscripción de los estudiantes a las competencias
            </p>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap gap-4">
                <button onclick="filtrarPor('todas')" class="filtro-btn active px-4 py-2 rounded-lg font-medium transition-colors" data-filter="todas">
                    <i class="fas fa-list mr-2"></i>Todas
                </button>
                <button onclick="filtrarPor('pendiente')" class="filtro-btn px-4 py-2 rounded-lg font-medium transition-colors" data-filter="pendiente">
                    <i class="fas fa-clock mr-2"></i>Pendientes
                </button>
                <button onclick="filtrarPor('confirmada')" class="filtro-btn px-4 py-2 rounded-lg font-medium transition-colors" data-filter="confirmada">
                    <i class="fas fa-check-circle mr-2"></i>Confirmadas
                </button>
                <button onclick="filtrarPor('rechazada')" class="filtro-btn px-4 py-2 rounded-lg font-medium transition-colors" data-filter="rechazada">
                    <i class="fas fa-times-circle mr-2"></i>Rechazadas
                </button>
            </div>
        </div>

        @if($inscripciones->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-inbox text-yellow-500 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">No hay solicitudes</h3>
                <p class="text-yellow-700">Aún no se han recibido solicitudes de inscripción.</p>
            </div>
        @else
            <!-- Tabla de Solicitudes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estudiante</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nivel</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($inscripciones as $inscripcion)
                                <tr class="hover:bg-gray-50 inscripcion-row" data-estado="{{ $inscripcion->estado }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $inscripcion->user->name }} {{ $inscripcion->user->last_name_father }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $inscripcion->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $inscripcion->competition->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $inscripcion->area->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $inscripcion->level->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($inscripcion->es_grupal)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Grupal
                                            </span>
                                            @if($inscripcion->grupo_nombre)
                                                <div class="text-xs text-gray-500 mt-1">{{ $inscripcion->grupo_nombre }}</div>
                                            @endif
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Individual
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($inscripcion->estado === 'pendiente')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Pendiente
                                            </span>
                                        @elseif($inscripcion->estado === 'confirmada')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Confirmada
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Rechazada
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $inscripcion->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="abrirModalDetalle({{ $inscripcion->id }})" 
                                                class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($inscripcion->estado === 'pendiente')
                                            <button onclick="cambiarEstado({{ $inscripcion->id }}, 'confirmada')" 
                                                    class="text-green-600 hover:text-green-900 mr-3" title="Aprobar">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button onclick="cambiarEstado({{ $inscripcion->id }}, 'rechazada')" 
                                                    class="text-red-600 hover:text-red-900" title="Rechazar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal de Detalle -->
<div id="modalDetalle" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detalle de Inscripción</h3>
                <button onclick="cerrarModalDetalle()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="contenidoDetalle" class="space-y-4">
                <!-- Se llenará dinámicamente con JavaScript -->
            </div>

            <div class="mt-6 flex justify-end space-x-3">
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
    .filtro-btn {
        background-color: #f3f4f6;
        color: #6b7280;
    }
    .filtro-btn:hover {
        background-color: #e5e7eb;
    }
    .filtro-btn.active {
        background-color: #3b82f6;
        color: white;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const inscripciones = @json($inscripciones);

    function filtrarPor(estado) {
        // Actualizar botones activos
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        event.target.closest('.filtro-btn').classList.add('active');

        // Filtrar filas
        const filas = document.querySelectorAll('.inscripcion-row');
        filas.forEach(fila => {
            if (estado === 'todas' || fila.dataset.estado === estado) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    }

    function abrirModalDetalle(id) {
        const inscripcion = inscripciones.find(i => i.id === id);
        if (!inscripcion) return;

        const html = `
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-700">Estudiante:</p>
                    <p class="text-sm text-gray-900">${inscripcion.user.name} ${inscripcion.user.last_name_father}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Email:</p>
                    <p class="text-sm text-gray-900">${inscripcion.user.email}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Competencia:</p>
                    <p class="text-sm text-gray-900">${inscripcion.competition.name}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Área:</p>
                    <p class="text-sm text-gray-900">${inscripcion.area.name}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Nivel:</p>
                    <p class="text-sm text-gray-900">${inscripcion.level.name}</p>
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
                ${inscripcion.observaciones ? `
                <div class="col-span-2">
                    <p class="text-sm font-semibold text-gray-700">Observaciones:</p>
                    <p class="text-sm text-gray-900">${inscripcion.observaciones}</p>
                </div>
                ` : ''}
                <div>
                    <p class="text-sm font-semibold text-gray-700">Estado:</p>
                    <p class="text-sm text-gray-900">${inscripcion.estado.charAt(0).toUpperCase() + inscripcion.estado.slice(1)}</p>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-700">Fecha de Inscripción:</p>
                    <p class="text-sm text-gray-900">${new Date(inscripcion.created_at).toLocaleString('es-ES')}</p>
                </div>
            </div>

            ${inscripcion.estado === 'pendiente' ? `
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm font-semibold text-gray-700 mb-3">Acciones:</p>
                <div class="flex space-x-3">
                    <button onclick="cambiarEstado(${id}, 'confirmada')" 
                            class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                        <i class="fas fa-check mr-2"></i>Aprobar
                    </button>
                    <button onclick="cambiarEstado(${id}, 'rechazada')" 
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                        <i class="fas fa-times mr-2"></i>Rechazar
                    </button>
                </div>
            </div>
            ` : ''}
        `;

        document.getElementById('contenidoDetalle').innerHTML = html;
        document.getElementById('modalDetalle').classList.remove('hidden');
    }

    function cerrarModalDetalle() {
        document.getElementById('modalDetalle').classList.add('hidden');
    }

    function cambiarEstado(id, nuevoEstado) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas ${nuevoEstado === 'confirmada' ? 'aprobar' : 'rechazar'} esta inscripción?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: nuevoEstado === 'confirmada' ? '#10b981' : '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Sí, continuar',
            cancelButtonText: 'Cancelar',
            input: nuevoEstado === 'rechazada' ? 'textarea' : null,
            inputLabel: nuevoEstado === 'rechazada' ? 'Motivo del rechazo (opcional)' : null,
            inputPlaceholder: 'Ingresa el motivo del rechazo...'
        }).then((result) => {
            if (result.isConfirmed) {
                const requestBody = {
                    estado: nuevoEstado
                };
                
                // Solo incluir observaciones si hay un valor real (string no vacío)
                if (result.value && typeof result.value === 'string' && result.value.trim() !== '') {
                    requestBody.observaciones = result.value.trim();
                }
                
                fetch(`/dashboard/admin/inscripcion/solicitud/${id}/estado`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: data.message,
                            confirmButtonColor: '#3b82f6'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message,
                            confirmButtonColor: '#ef4444'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar la solicitud',
                        confirmButtonColor: '#ef4444'
                    });
                });
            }
        });
    }

    // Cerrar modal al hacer clic fuera
    document.getElementById('modalDetalle').addEventListener('click', function(e) {
        if (e.target === this) {
            cerrarModalDetalle();
        }
    });
</script>
@endpush
