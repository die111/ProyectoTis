<!-- Sidebar -->
<div class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 transition-transform transform lg:translate-x-0 lg:static lg:inset-0" 
     id="sidebar"
     x-data="{ open: false }"
     :class="{ '-translate-x-full': !open, 'translate-x-0': open }">
    
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 bg-gray-800">
        <div class="flex items-center space-x-2">
            <i class="fas fa-trophy text-2xl text-yellow-400"></i>
            <span class="text-white text-lg font-bold">Oh! SanSi</span>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-4 bg-gray-800">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center">
                <span class="text-white font-medium text-sm">
                    {{ substr(Auth::user()->name, 0, 2) }}
                </span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">
                    {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-gray-300 truncate">
                    {{ Auth::user()->role }}
                </p>
                @if(Auth::user()->area)
                    <p class="text-xs text-gray-400 truncate">
                        Área: {{ Auth::user()->area }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-5 px-2 space-y-1">
        @php
            // Según el rol del usuario
            $menuItems = [];
            
            switch (Auth::user()->role) {
                case 'admin':
                    $menuItems = [
                        ['name' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                        ['name' => 'Usuarios', 'route' => 'admin.users.create', 'icon' => 'fas fa-users'],
                        // ['name' => 'Áreas', 'route' => '#', 'icon' => 'fas fa-layer-group'],
                        // ['name' => 'Olimpistas', 'route' => '#', 'icon' => 'fas fa-user-graduate'],
                        // ['name' => 'Reportes', 'route' => '#', 'icon' => 'fas fa-chart-bar'],
                    ];
                    break;
                    
                    // Usando submenúitems
                    // ['name' => 'Usuarios', 'route' => 'admin.users.index', 'icon' => 'fas fa-users', 'submenu' => $submenuItems],
                    // $submenuItems = [
                    //     ['name' => 'Crear Usuario', 'route' => 'admin.users.create'],
                    //     ['name' => 'Listar Usuarios', 'route' => 'admin.users.index'],
                    // ];
                    
                // case 'responsable_area':
                //     $menuItems = [
                //         ['name' => 'Dashboard', 'route' => 'responsable.dashboard', 'icon' => 'fas fa-tachometer-alt'],
                //         ['name' => 'Mi Área', 'route' => '#', 'icon' => 'fas fa-layer-group'],
                //         ['name' => 'Evaluadores', 'route' => '#', 'icon' => 'fas fa-user-check'],
                //         ['name' => 'Evaluaciones', 'route' => '#', 'icon' => 'fas fa-clipboard-check'],
                //     ];
                //     break;
                    
                // Agregar casos para otros roles
                    
                default:
                    $menuItems = [
                        ['name' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'fas fa-tachometer-alt'],
                    ];
            }
        @endphp
        
        @foreach($menuItems as $item)
            <a href="{{ route($item['route']) }}" 
               class="group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors duration-150 ease-in-out
                      {{ Route::currentRouteName() == $item['route'] 
                         ? 'bg-gray-800 text-white' 
                         : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                <i class="{{ $item['icon'] }} w-5 h-5 mr-3 
                          {{ Route::currentRouteName() == $item['route'] ? 'text-gray-300' : 'text-gray-400 group-hover:text-gray-300' }}"></i>
                {{ $item['name'] }}
            </a>
        @endforeach
    </nav>

    <!-- Logout -->
    <div class="absolute bottom-0 w-full p-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" 
                    class="group flex items-center w-full px-2 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white transition-colors duration-150 ease-in-out">
                <i class="fas fa-sign-out-alt w-5 h-5 mr-3 text-gray-400 group-hover:text-gray-300"></i>
                Cerrar Sesión
            </button>
        </form>
    </div>
</div>

<!-- Mobile sidebar overlay -->
<div class="fixed inset-0 z-40 lg:hidden" 
     x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     x-cloak
     :class="{'hidden': !sidebarOpen}">
    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
    </div>