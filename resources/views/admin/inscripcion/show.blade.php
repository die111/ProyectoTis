@extends('layouts.app')
@section('title', 'Detalle de Inscripción')

@section('content')
<div class="p-6">
    <div class="w-full">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.inscripcion.solicitud') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-clipboard-list mr-2"></i>Solicitudes de Inscripción
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500">Detalle de Inscripción</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Tarjeta Principal -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header con gradiente -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-1">{{ $inscripcion->competition->name }}</h1>
                        <p class="text-blue-100">
                            <i class="fas fa-user mr-2"></i>
                            {{ $inscripcion->user->name }} {{ $inscripcion->user->last_name_father }}
                        </p>
                    </div>
                    <div>
                        @if($inscripcion->estado === 'pendiente')
                            <span class="px-4 py-2 bg-yellow-500 text-white rounded-full font-medium text-lg">
                                <i class="fas fa-clock mr-2"></i>Pendiente
                            </span>
                        @elseif($inscripcion->estado === 'confirmada')
                            <span class="px-4 py-2 bg-green-500 text-white rounded-full font-medium text-lg">
                                <i class="fas fa-check mr-2"></i>Confirmada
                            </span>
                        @else
                            <span class="px-4 py-2 bg-red-500 text-white rounded-full font-medium text-lg">
                                <i class="fas fa-times mr-2"></i>Rechazada
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Contenido -->
            <div class="p-6">
                <!-- Información del Estudiante -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-graduate text-blue-600 mr-2"></i>
                        Información del Estudiante
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Nombre Completo:</p>
                            <p class="text-sm text-gray-900 font-semibold">{{ $inscripcion->user->name }} {{ $inscripcion->user->last_name_father }} {{ $inscripcion->user->last_name_mother }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Email:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Código de Usuario:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->user->user_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Teléfono:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->user->telephone_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Información de la Inscripción -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Detalles de la Inscripción
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Competencia:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->competition->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Área:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->area->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Nivel:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->level->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Categoría:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->categoria->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Fase:</p>
                            <p class="text-sm text-gray-900">Fase {{ $inscripcion->fase }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Tipo de Inscripción:</p>
                            <p class="text-sm">
                                @if($inscripcion->es_grupal)
                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-users mr-1"></i>Grupal
                                    </span>
                                @else
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        <i class="fas fa-user mr-1"></i>Individual
                                    </span>
                                @endif
                            </p>
                        </div>
                        @if($inscripcion->grupo_nombre)
                        <div class="col-span-2">
                            <p class="text-sm font-medium text-gray-600">Nombre del Grupo:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->grupo_nombre }}</p>
                        </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-600">Fecha de Inscripción:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Última Actualización:</p>
                            <p class="text-sm text-gray-900">{{ $inscripcion->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Observaciones del Estudiante -->
                @if($inscripcion->observaciones_estudiante)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-comment text-blue-600 mr-2"></i>
                        Observaciones del Estudiante
                    </h2>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                        <p class="text-sm text-gray-900">{{ $inscripcion->observaciones_estudiante }}</p>
                    </div>
                </div>
                @endif

                <!-- Motivo del Rechazo -->
                @if($inscripcion->estado === 'rechazada' && $inscripcion->motivo_rechazo)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                        Motivo del Rechazo
                    </h2>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <p class="text-sm text-gray-900">{{ $inscripcion->motivo_rechazo }}</p>
                    </div>
                </div>
                @endif

                <!-- Acciones según el estado -->
                @if($inscripcion->estado === 'pendiente')
                <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tasks text-blue-600 mr-2"></i>
                        Acciones
                    </h2>
                    <div class="flex gap-3">
                        <button onclick="cambiarEstado({{ $inscripcion->id }}, 'confirmada')" 
                                class="flex-1 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-check mr-2"></i>Aprobar Inscripción
                        </button>
                        <button onclick="cambiarEstado({{ $inscripcion->id }}, 'rechazada')" 
                                class="flex-1 px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-times mr-2"></i>Rechazar Inscripción
                        </button>
                    </div>
                </div>
                @endif

                <!-- Botón Volver -->
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.inscripcion.solicitud') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a Solicitudes
                    </a>

                    @if($inscripcion->estado === 'confirmada')
                    <button class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Generar Reporte
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-gradient-to-r {
        background: linear-gradient(to right, #2563eb, #1d4ed8);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function cambiarEstado(id, nuevoEstado) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: `¿Deseas ${nuevoEstado === 'confirmada' ? 'aprobar' : 'rechazar'} esta inscripción?`,
            icon: 'question',
            input: nuevoEstado === 'rechazada' ? 'textarea' : null,
            inputLabel: nuevoEstado === 'rechazada' ? 'Motivo del rechazo (obligatorio)' : null,
            inputPlaceholder: nuevoEstado === 'rechazada' ? 'Escribe el motivo del rechazo...' : null,
            inputAttributes: {
                'aria-label': 'Motivo del rechazo',
                'required': 'required'
            },
            inputValidator: (value) => {
                if (nuevoEstado === 'rechazada' && !value) {
                    return 'Debes proporcionar un motivo para el rechazo'
                }
            },
            showCancelButton: true,
            confirmButtonColor: nuevoEstado === 'confirmada' ? '#16a34a' : '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: nuevoEstado === 'confirmada' ? 'Sí, aprobar' : 'Sí, rechazar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const requestBody = {
                    estado: nuevoEstado
                };

                // Solo incluir observaciones si hay un valor real (string no vacío)
                if (result.value && result.value.trim()) {
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
</script>
@endpush
