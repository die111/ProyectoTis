@php
    // Estadísticas rápidas para administrador
    $stats = [
        'total_olimpistas' => \App\Models\User::whereHas('role', function($q){ $q->where('name','estudiante'); })->count(),
        'total_evaluaciones' => \App\Models\Evaluation::count(),
        'areas_activas' => \App\Models\Area::where('is_active', true)->count(),
        'usuarios_activos' => \App\Models\User::where('is_active', true)->count(),
    ];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition-shadow duration-200">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Estudiantes</p>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_olimpistas']) }}</p>
            </div>
        </div>
    </div>

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

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
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
