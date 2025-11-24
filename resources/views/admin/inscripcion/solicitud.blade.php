@extends('layouts.app')

@section('title', 'Solicitudes de Inscripción')

@section('content')
<div class="p-6">
    <div class="w-full">
        <!-- Header -->
        <div class="mb-6 text-center">
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
                                                    {{ optional($inscripcion->user)->name ?? '—' }} {{ optional($inscripcion->user)->last_name_father ?? '' }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ optional($inscripcion->user)->email ?? '—' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ optional($inscripcion->competition)->name ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ optional($inscripcion->area)->name ?? '—' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ optional($inscripcion->level)->name ?? '—' }}</div>
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
                                        <a href="{{ route('admin.inscripcion.solicitud.show', $inscripcion->id) }}" 
                                           class="text-blue-600 hover:text-blue-900 mr-3" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
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

    // Redirigir a la vista de detalle si viene desde una notificación
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const inscripcionId = urlParams.get('inscripcion_id');
        
        if (inscripcionId) {
            // Redirigir directamente a la vista de detalle
            window.location.href = `{{ route('admin.inscripcion.solicitud') }}/${inscripcionId}`;
        }
    });
</script>
@endpush
