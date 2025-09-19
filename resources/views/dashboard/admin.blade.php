    @extends('layouts.app')

    @section('title', 'Dashboard Administrador')

    @section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Panel de Administración</h1>
                <p class="text-gray-600 mt-1">Bienvenido, {{ Auth::user()->name }}</p>
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
            <div class="bg-white rounded-lg shadow p-6">
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
            <div class="bg-white rounded-lg shadow p-6">
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
            <div class="bg-white rounded-lg shadow p-6">
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
            <div class="bg-white rounded-lg shadow p-6">
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

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Actions Panel -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.users.create') }}" 
                    class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user-plus text-blue-500 mr-3"></i>
                        <span class="font-medium">Crear Usuario</span>
                    </a>
                    {{-- <a href="{{ route('olimpistas.create') }}"  --}}
                    <a
                    class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-user-graduate text-green-500 mr-3"></i>
                        <span class="font-medium">Registrar Olimpista</span>
                    </a>
                    {{-- <a href="{{ route('areas.index') }}"  --}}
                    <a
                    class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-layer-group text-purple-500 mr-3"></i>
                        <span class="font-medium">Gestionar Áreas</span>
                    </a>
                    {{-- <a href="{{ route('reportes.index') }}"  --}}
                    <a
                    class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chart-bar text-orange-500 mr-3"></i>
                        <span class="font-medium">Ver Reportes</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actividad Reciente</h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Sistema iniciado correctamente</p>
                            <p class="text-xs text-gray-500">Hace 2 minutos</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2 mr-3"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Base de datos actualizada</p>
                            <p class="text-xs text-gray-500">Hace 1 hora</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2 mr-3"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-900">Backup programado ejecutado</p>
                            <p class="text-xs text-gray-500">Hace 3 horas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Estado del Sistema</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-600">Base de Datos: Online</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-600">Servidor: Funcionando</span>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm text-gray-600">Sistema: Estable</span>
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
        
        document.getElementById('bolivia-time').textContent = `${day}/${month}/${year} ${hours}:${minutes}`;
    }

    updateBoliviaTime();
    setInterval(updateBoliviaTime, 60000);
</script>
@endsection