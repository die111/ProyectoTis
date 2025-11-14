@extends('layouts.app')

@section('title', 'Mis Notificaciones')

@section('content')
<div class="p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                    <i class="fas fa-bell mr-2 text-blue-600"></i>Mis Notificaciones
                </h1>
                <p class="text-gray-600">
                    Todas tus notificaciones y actualizaciones del sistema
                </p>
            </div>
            
            @if($unreadCount > 0)
            <button 
                onclick="marcarTodasLeidas()" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                <i class="fas fa-check-double mr-2"></i>Marcar todas como leídas
            </button>
            @endif
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('notifications.index', ['filter' => 'all']) }}" 
                   class="filtro-btn px-4 py-2 rounded-lg font-medium transition-colors {{ $currentFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-list mr-2"></i>Todas
                    <span class="ml-2 px-2 py-0.5 text-xs bg-white/20 rounded-full">{{ $notifications->total() }}</span>
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
                   class="filtro-btn px-4 py-2 rounded-lg font-medium transition-colors {{ $currentFilter === 'unread' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-envelope mr-2"></i>No leídas
                    @if($unreadCount > 0)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-red-500 text-white rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
                   class="filtro-btn px-4 py-2 rounded-lg font-medium transition-colors {{ $currentFilter === 'read' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    <i class="fas fa-envelope-open mr-2"></i>Leídas
                </a>
            </div>
        </div>

        @if($notifications->isEmpty())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-8 text-center">
                <i class="fas fa-bell-slash text-blue-500 text-5xl mb-4"></i>
                <h3 class="text-lg font-semibold text-blue-800 mb-2">No hay notificaciones</h3>
                <p class="text-blue-700">
                    @if($currentFilter === 'unread')
                        No tienes notificaciones sin leer.
                    @elseif($currentFilter === 'read')
                        No tienes notificaciones leídas.
                    @else
                        Aún no has recibido ninguna notificación.
                    @endif
                </p>
            </div>
        @else
            <!-- Lista de Notificaciones -->
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    @php
                        $data = $notification->data;
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    
                    <a href="{{ route('notifications.show', $notification->id) }}" 
                       class="block bg-white rounded-lg shadow-sm border {{ $isUnread ? 'border-blue-300 bg-blue-50/30' : 'border-gray-200' }} hover:shadow-md transition-all duration-200">
                        <div class="p-5">
                            <div class="flex items-start gap-4">
                                <!-- Icono -->
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 rounded-full {{ $isUnread ? 'bg-blue-100' : 'bg-gray-100' }} flex items-center justify-center">
                                        <i class="fas {{ $data['icon'] ?? 'fa-bell' }} text-lg {{ $isUnread ? 'text-blue-600' : 'text-gray-500' }}"></i>
                                    </div>
                                </div>
                                
                                <!-- Contenido -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3 mb-2">
                                        <h3 class="text-base font-semibold {{ $isUnread ? 'text-gray-900' : 'text-gray-700' }}">
                                            {{ $data['title'] ?? 'Notificación' }}
                                            @if($isUnread)
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-600 text-white">
                                                    Nueva
                                                </span>
                                            @endif
                                        </h3>
                                        <span class="text-xs text-gray-500 whitespace-nowrap">
                                            <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ $data['message'] ?? 'Sin mensaje' }}
                                    </p>
                                    
                                    @if(isset($data['type']))
                                    <div class="mt-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $data['type'] }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Indicador y acción -->
                                <div class="flex-shrink-0 flex flex-col items-end gap-2">
                                    @if($isUnread)
                                        <div class="w-3 h-3 bg-blue-600 rounded-full"></div>
                                    @endif
                                    <i class="fas fa-chevron-right text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Paginación -->
            @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
            @endif
        @endif
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

<script>
function marcarTodasLeidas() {
    if (!confirm('¿Marcar todas las notificaciones como leídas?')) {
        return;
    }
    
    fetch('/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
