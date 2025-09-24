@extends('layouts.app')

@section('title', 'Dashboard Administrador')
@section('page-title', 'Panel de Administración')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Bienvenido, {{ Auth::user()->name }}</h2>
                <p class="text-gray-600 mt-1">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
                @if(Auth::user()->area)
                    <p class="text-sm text-gray-500 mt-1">Área: {{ Auth::user()->area }}</p>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>
                <span id="bolivia-time">{{ \Carbon\Carbon::now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Olimpistas -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Olimpistas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_olimpistas']) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Evaluaciones -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-green-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Evaluaciones</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_evaluaciones']) }}</p>
                </div>
            </div>
        </div>

        <!-- Áreas Activas -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-layer-group text-purple-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Áreas Activas</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['areas_activas']) }}</p>
                </div>
            </div>
        </div>

        <!-- Usuarios Activos -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-orange-600 text-xl"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Usuarios Activos</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['usuarios_activos']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions and Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Actions Panel -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                Acciones Rápidas
            </h3>
            <div class="space-y-3">

                
                <a href="#" class="flex items-center p-4 rounded-lg border-2 border-dashed border-gray-200 hover:border-green-300 hover:bg-green-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-green-200">
                        <i class="fas fa-user-graduate text-green-500"></i>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-green-600">Registrar Olimpista</span>
                </a>
                
                <a href="#" class="flex items-center p-4 rounded-lg border-2 border-dashed border-gray-200 hover:border-purple-300 hover:bg-purple-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-purple-200">
                        <i class="fas fa-layer-group text-purple-500"></i>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-purple-600">Gestionar Áreas</span>
                </a>
                
                <a href="#" class="flex items-center p-4 rounded-lg border-2 border-dashed border-gray-200 hover:border-orange-300 hover:bg-orange-50 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200">
                        <i class="fas fa-chart-bar text-orange-500"></i>
                    </div>
                    <span class="font-medium text-gray-700 group-hover:text-orange-600">Ver Reportes</span>
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-clock text-indigo-500 mr-2"></i>
                Actividad Reciente
            </h3>
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">Sistema iniciado correctamente</p>
                        <p class="text-xs text-gray-500">Hace 2 minutos</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">Base de datos actualizada</p>
                        <p class="text-xs text-gray-500">Hace 1 hora</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">Backup programado ejecutado</p>
                        <p class="text-xs text-gray-500">Hace 3 horas</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-purple-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900">Nuevo usuario registrado</p>
                        <p class="text-xs text-gray-500">Hace 5 horas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-server text-gray-600 mr-2"></i>
            Estado del Sistema
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="flex items-center p-4 bg-green-50 rounded-lg">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <p class="text-sm font-medium text-green-800">Base de Datos</p>
                    <p class="text-xs text-green-600">Online - Funcionando correctamente</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-green-50 rounded-lg">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <p class="text-sm font-medium text-green-800">Servidor Web</p>
                    <p class="text-xs text-green-600">Online - Rendimiento óptimo</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-green-50 rounded-lg">
                <div class="w-3 h-3 bg-green-500 rounded-full mr-3 animate-pulse"></div>
                <div>
                    <p class="text-sm font-medium text-green-800">Sistema</p>
                    <p class="text-xs text-green-600">Estable - Sin errores</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateBoliviaTime() {
        const now = new Date();
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        
        const boliviaOffset = -4 * 60;
        const boliviaTime = new Date(utc + (3600000 * boliviaOffset/60));
        
        const day = boliviaTime.getDate().toString().padStart(2, '0');
        const month = (boliviaTime.getMonth() + 1).toString().padStart(2, '0');
        const year = boliviaTime.getFullYear();
        const hours = boliviaTime.getHours().toString().padStart(2, '0');
        const minutes = boliviaTime.getMinutes().toString().padStart(2, '0');
        
        const timeElement = document.getElementById('bolivia-time');
        if (timeElement) {
            timeElement.textContent = `${day}/${month}/${year} ${hours}:${minutes}`;
        }
    }

    // Update time on page load and every minute
    document.addEventListener('DOMContentLoaded', function() {
        updateBoliviaTime();
        setInterval(updateBoliviaTime, 60000);
    });
</script>
@endsection