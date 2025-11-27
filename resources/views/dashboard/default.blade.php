@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Panel de Control')

@section('content')
@php
    $user = Auth::user();
    $role = $user->role;
    $permissions = $role ? $role->permissions : collect([]);
    
    // mapeo de clases y accesso rapidos
    $quickAccess = config('dashboard.quick_access', []);
    $colorClasses = config('dashboard.color_classes', []);
@endphp

<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Bienvenido, {{ $user->name }}</h2>
                <p class="text-gray-600 mt-1 font-medium">
                    {{ $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'Sin rol asignado' }}
                </p>
                @if($user->area)
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-building mr-1"></i>
                        Área: {{ $user->area->name }}
                    </p>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>
                <span id="bolivia-time">{{ \Carbon\Carbon::now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
    
    <!-- Quick Access Cards -->
    @php
        $userPermissions = $permissions->pluck('name')->toArray();
        $availableAccess = array_filter($quickAccess, function($access, $key) use ($userPermissions) {
            return in_array($key, $userPermissions);
        }, ARRAY_FILTER_USE_BOTH);
    @endphp

    @if(count($availableAccess) > 0)
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                Accesos Rápidos
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($availableAccess as $permissionName => $access)
                    <a href="{{ route($access['route']) }}" 
                       class="block bg-white rounded-lg shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-12 h-12 {{ $colorClasses[$access['color']] }} rounded-lg flex items-center justify-center text-white transition-colors">
                                    <i class="fas {{ $access['icon'] }} text-xl"></i>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 group-hover:text-gray-600 transition-colors"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 mb-1">{{ $access['title'] }}</h4>
                            <p class="text-sm text-gray-600">{{ $access['description'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-yellow-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-yellow-800 font-semibold mb-1">Sin accesos rápidos disponibles</h4>
                    <p class="text-yellow-700 text-sm">No tienes permisos asignados para acceder a funcionalidades específicas. Contacta al administrador si crees que esto es un error.</p>
                </div>
            </div>
        </div>
    @endif

    @if($role && $role->name === 'admin')
        @include('dashboard.partials.admin_extra')
    @endif
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