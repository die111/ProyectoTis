@extends('layouts.app')

@section('title', 'Detalle de Notificación')

@section('content')
<div class="p-6">
    <div class="max-w-4xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('notifications.index') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-bell mr-2"></i>Notificaciones
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-500">Detalle</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        @php
            $data = $notification->data;
            $isUnread = is_null($notification->read_at);
            
            // Debug temporal - quitar después
            // dd($data);
        @endphp

        <!-- Tarjeta de Notificación -->
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white">
                <div class="flex items-start justify-between">
                    <div class="flex items-start gap-4">
                        <div class="w-14 h-14 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="fas {{ $data['icon'] ?? 'fa-bell' }} text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold mb-2">
                                {{ $data['title'] ?? 'Notificación' }}
                            </h1>
                            <div class="flex items-center gap-3 text-blue-100 text-sm">
                                <span>
                                    <i class="far fa-clock mr-1"></i>
                                    {{ $notification->created_at->format('d/m/Y H:i') }}
                                </span>
                                <span>•</span>
                                <span>
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if($isUnread)
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium">
                        <i class="fas fa-circle text-xs mr-1"></i>Nueva
                    </span>
                    @else
                    <span class="px-3 py-1 bg-white/10 backdrop-blur-sm rounded-full text-sm">
                        <i class="fas fa-check text-xs mr-1"></i>Leída
                    </span>
                    @endif
                </div>
            </div>

            <!-- Contenido -->
            <div class="p-6">
                <!-- Mensaje Principal -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>Mensaje
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <p class="text-gray-700 leading-relaxed">
                            {{ $data['message'] ?? 'Sin mensaje' }}
                        </p>
                    </div>
                </div>  
                
                {{-- Sección de Acciones de Inscripción --}}
                @if(isset($data['inscription_id']))
                    @php
                        $inscripcion = \App\Models\Inscription::with(['user', 'competition', 'area'])->find($data['inscription_id']);
                    @endphp
                    
                    @if($inscripcion)
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">
                            <i class="fas fa-user-graduate text-blue-600 mr-2"></i>Información de la Inscripción
                        </h2>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <dl class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm font-medium text-gray-600">Estudiante:</dt>
                                    <dd class="text-sm text-gray-900 font-semibold">{{ $inscripcion->user->name }} {{ $inscripcion->user->last_name_father }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm font-medium text-gray-600">Competencia:</dt>
                                    <dd class="text-sm text-gray-900">{{ $inscripcion->competition->name }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm font-medium text-gray-600">Área:</dt>
                                    <dd class="text-sm text-gray-900">{{ $inscripcion->area->name ?? 'N/A' }}</dd>
                                </div>
                                <div class="flex items-center justify-between">
                                    <dt class="text-sm font-medium text-gray-600">Estado:</dt>
                                    <dd class="text-sm">
                                        @if($inscripcion->estado === 'pendiente')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-medium">
                                                <i class="fas fa-clock mr-1"></i>Pendiente
                                            </span>
                                        @elseif($inscripcion->estado === 'confirmada')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium">
                                                <i class="fas fa-check mr-1"></i>Confirmada
                                            </span>
                                        @elseif($inscripcion->estado === 'rechazada')
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full font-medium">
                                                <i class="fas fa-times mr-1"></i>Rechazada
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                                
                                {{-- Mostrar motivo del rechazo si está rechazada --}}
                                @if($inscripcion->estado === 'rechazada' && $inscripcion->motivo_rechazo)
                                <div class="col-span-2 mt-2">
                                    <dt class="text-sm font-medium text-gray-600 mb-2">Motivo del rechazo:</dt>
                                    <dd class="text-sm text-gray-900 bg-red-50 border border-red-200 rounded p-3">
                                        {{ $inscripcion->motivo_rechazo }}
                                    </dd>
                                </div>
                                @endif
                                
                                {{-- Mostrar observaciones del estudiante si existen --}}
                                @if($inscripcion->observaciones_estudiante)
                                <div class="col-span-2 mt-2">
                                    <dt class="text-sm font-medium text-gray-600 mb-2">Observaciones del estudiante:</dt>
                                    <dd class="text-sm text-gray-900 bg-blue-50 border border-blue-200 rounded p-3">
                                        {{ $inscripcion->observaciones_estudiante }}
                                    </dd>
                                </div>
                                @endif
                            </dl>
                            
                            {{-- Botones de Acción solo si está pendiente y el usuario tiene permiso de inscripción --}}
                            @if($inscripcion->estado === 'pendiente' && auth()->user()->hasPermissionTo('inscripcion'))
                            <div class="mt-4 flex gap-3">
                                <button 
                                    onclick="aceptarInscripcion({{ $inscripcion->id }})"
                                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Aceptar Inscripción
                                </button>
                                <button 
                                    onclick="mostrarModalRechazo({{ $inscripcion->id }})"
                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Rechazar Inscripción
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                @endif

                <!-- Información de Lectura -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">
                        <i class="fas fa-history text-blue-600 mr-2"></i>Estado
                    </h2>
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm font-medium text-gray-600">Estado de lectura:</dt>
                                <dd class="text-sm">
                                    @if($isUnread)
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-medium">
                                            <i class="fas fa-envelope mr-1"></i>No leída
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-medium">
                                            <i class="fas fa-envelope-open mr-1"></i>Leída
                                        </span>
                                    @endif
                                </dd>
                            </div>

                            <div class="flex items-center justify-between">
                                <dt class="text-sm font-medium text-gray-600">Recibida:</dt>
                                <dd class="text-sm text-gray-900">{{ $notification->created_at->format('d/m/Y H:i:s') }}</dd>
                            </div>

                            @if(!$isUnread)
                            <div class="flex items-center justify-between">
                                <dt class="text-sm font-medium text-gray-600">Leída:</dt>
                                <dd class="text-sm text-gray-900">{{ $notification->read_at->format('d/m/Y H:i:s') }}</dd>
                            </div>
                            @endif
                            
                            {{-- Id de notificación: --}}
                            {{-- <div class="flex items-center justify-between">
                                <dt class="text-sm font-medium text-gray-600">ID de notificación:</dt>
                                <dd class="text-sm text-gray-900 font-mono">{{ $notification->id }}</dd>
                            </div> --}}
                        </dl>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="flex gap-3">
                    <a href="{{ route('notifications.index') }}" 
                       class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Volver a notificaciones
                    </a>
                    
                    @if(isset($data['route']) && $data['route'])
                    <a href="{{ $data['route'] }}" 
                       class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors text-center">
                        <i class="fas fa-external-link-alt mr-2"></i>Ir al contenido
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón eliminar (opcional) -->
        <div class="mt-4 text-center">
            <button 
                onclick="eliminarNotificacion()" 
                class="text-red-600 hover:text-red-800 text-sm font-medium">
                <i class="fas fa-trash mr-1"></i>Eliminar esta notificación
            </button>
        </div>
    </div>
</div>

{{-- Modal de Rechazo --}}
<div id="modalRechazo" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-times-circle text-red-600 mr-2"></i>Rechazar Inscripción
            </h3>
            <p class="text-sm text-gray-600 mb-4">
                Por favor, indica el motivo del rechazo de esta inscripción:
            </p>
            <textarea 
                id="motivoRechazo" 
                rows="4" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500"
                placeholder="Escribe el motivo del rechazo..."></textarea>
            <div class="mt-4 flex gap-3">
                <button 
                    onclick="cerrarModalRechazo()"
                    class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition-colors">
                    Cancelar
                </button>
                <button 
                    onclick="confirmarRechazo()"
                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>Rechazar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let inscripcionIdActual = null;

function mostrarModalRechazo(inscripcionId) {
    inscripcionIdActual = inscripcionId;
    document.getElementById('modalRechazo').classList.remove('hidden');
    document.getElementById('motivoRechazo').value = '';
}

function cerrarModalRechazo() {
    document.getElementById('modalRechazo').classList.add('hidden');
    inscripcionIdActual = null;
}

function aceptarInscripcion(inscripcionId) {
    if (!confirm('¿Estás seguro de que deseas aceptar esta inscripción?')) {
        return;
    }
    
    actualizarEstadoInscripcion(inscripcionId, 'confirmada', null);
}

function confirmarRechazo() {
    const motivo = document.getElementById('motivoRechazo').value.trim();
    
    if (!motivo) {
        alert('Por favor, ingresa el motivo del rechazo');
        return;
    }
    
    actualizarEstadoInscripcion(inscripcionIdActual, 'rechazada', motivo);
    cerrarModalRechazo();
}

function actualizarEstadoInscripcion(inscripcionId, estado, observaciones) {
    // Mostrar indicador de carga
    const btnAceptar = document.querySelector(`button[onclick="aceptarInscripcion(${inscripcionId})"]`);
    const btnRechazar = document.querySelector(`button[onclick="mostrarModalRechazo(${inscripcionId})"]`);
    
    if (btnAceptar) btnAceptar.disabled = true;
    if (btnRechazar) btnRechazar.disabled = true;
    
    fetch(`/dashboard/admin/inscripcion/solicitud/${inscripcionId}/estado`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            estado: estado,
            observaciones: observaciones
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mostrar mensaje de éxito
            alert(data.message || 'Estado actualizado correctamente');
            // Recargar la página para ver los cambios
            window.location.reload();
        } else {
            alert(data.message || 'Error al actualizar el estado');
            if (btnAceptar) btnAceptar.disabled = false;
            if (btnRechazar) btnRechazar.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al procesar la solicitud');
        if (btnAceptar) btnAceptar.disabled = false;
        if (btnRechazar) btnRechazar.disabled = false;
    });
}

function eliminarNotificacion() {
    if (!confirm('¿Estás seguro de que deseas eliminar esta notificación?')) {
        return;
    }
    
    // Implementar lógica de eliminación si se necesita
    alert('Funcionalidad de eliminación pendiente de implementar');
}
</script>
@endsection
