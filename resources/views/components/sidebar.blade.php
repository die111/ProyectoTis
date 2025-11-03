<!-- Sidebar -->
<aside class="fixed md:sticky top-[64px] md:top-[64px] left-0 z-50 w-64 bg-[#091c47] text-white transition-all duration-300 ease-in-out transform h-[calc(100vh-64px)] md:h-[calc(100vh-64px)]" id="sidebar">
    <div class="flex flex-col h-full">
        <!-- Top section -->
        <div class="flex-1 overflow-y-auto">
            <!-- Toggle button -->
            <div class="px-4 py-3 flex items-center justify-between border-b border-gray-700">
                <a href="#" class="flex items-center space-x-3" id="menuToggle">
                    <i class="fas fa-bars text-xl"></i>
                    <span class="font-medium text-lg hide-on-collapse">Menú</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="mt-5 px-2">
                <ul class="space-y-1">
                    @foreach($menuItems ?? [] as $item)
                        @if(isset($item['submenu']))
                            @php
                                $hasActiveSubmenu = false;
                                foreach($item['submenu'] as $sub) {
                                    if(isset($sub['active']) && $sub['active']) {
                                        $hasActiveSubmenu = true;
                                        break;
                                    }
                                }
                            @endphp
                            <li class="relative" x-data="{ open: {{ $hasActiveSubmenu ? 'true' : 'false' }} }">
                                <button type="button" @click="open = !open" class="sidebar-item group flex items-center px-4 py-2 w-full text-sm font-medium rounded-md transition-all duration-200 text-gray-300 hover:bg-primary-800 hover:text-white focus:outline-none {{ isset($item['active']) && $item['active'] ? 'sidebar-item-active' : '' }}" data-menu="{{ $item['name'] }}">
                                    <i class="{{ $item['icon'] }} w-6 h-6 mr-3 text-lg"></i>
                                    <span class="hide-on-collapse">{{ $item['name'] }}</span>
                                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas ml-auto transition-transform duration-200"></i>
                                </button>
                                <ul class="ml-8 mt-1 space-y-1" x-show="open" x-transition>
                                    @foreach($item['submenu'] as $sub)
                                        <li>
                                            <a href="{{ route($sub['route']) }}" class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ isset($sub['active']) && $sub['active'] ? 'sidebar-item-active' : 'text-gray-300 hover:bg-primary-800 hover:text-white' }}" data-menu="{{ $sub['name'] }}">
                                                <i class="{{ $sub['icon'] }} w-5 h-5 mr-2 text-lg"></i>
                                                <span class="hide-on-collapse">{{ $sub['name'] }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ isset($item['route']) && $item['route'] !== '#' ? route($item['route']) : '#' }}"
                                   class="sidebar-item group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-all duration-200 {{ isset($item['active']) && $item['active'] ? 'sidebar-item-active' : 'text-gray-300 hover:bg-primary-800 hover:text-white' }}" data-menu="{{ $item['name'] }}">
                                    <i class="{{ $item['icon'] }} w-6 h-6 mr-3 text-lg"></i>
                                    <span class="hide-on-collapse">{{ $item['name'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>
        </div>

        <!-- Bottom section -->
        <div class="border-t border-gray-700/50 p-4 space-y-2 mt-auto">
            <a href="{{ route('profile.show') }}" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-all duration-200">
                <i class="fas fa-user w-6 h-6 mr-3 text-lg"></i>
                <span class="hide-on-collapse">Perfil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="sidebar-item w-full flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-6 h-6 mr-3 text-lg"></i>
                    <span class="hide-on-collapse">Cerrar Sesión</span>
                </button>
            </form>

            <button id="hideSidebar" class="sidebar-item w-full flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-all duration-200">
                <i class="fas fa-chevron-left w-6 h-6 mr-3 text-lg transition-transform duration-300"></i>
                <span class="hide-on-collapse">Ocultar</span>
            </button>
        </div>
    </div>
</aside>

<!-- Mobile sidebar overlay -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 hidden transition-opacity duration-300" id="sidebarOverlay"></div>

<style>
    :root {
        --primary-900: #091c47;
        --primary-800: #112a66;
        --active-color: #5AA9E6;
        --hover-color: #1e40af;
    }

    #sidebar {
        background-color: var(--primary-900);
        z-index: 50; /* sobre footer */
    }

    /* Estilo para elementos activos */
    .sidebar-item-active {
        background-color: var(--active-color) !important;
        color: white !important;
    }

    .sidebar-item-active:hover {
        background-color: var(--active-color) !important;
    }

    /* Efectos de hover mejorados */
    .sidebar-item {
        transition: all 0.2s ease-in-out;
        position: relative;
        overflow: hidden;
    }

    .sidebar-item:hover {
        background-color: var(--hover-color) !important;
        color: white !important;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .sidebar-item:hover i {
        transform: scale(1.1);
        color: #ffffff;
    }

    /* Efecto de brillo en hover */
    .sidebar-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s ease-in-out;
        pointer-events: none;
    }

    .sidebar-item:hover::before {
        left: 100%;
    }

    /* Efectos especiales para botones principales */
    .sidebar-item:not(.sidebar-item-active):hover {
        border-left: 4px solid var(--active-color);
    }

    /* Animación para iconos */
    .sidebar-item i {
        transition: all 0.2s ease-in-out;
    }

    /* Efectos para elementos del submenú */
    .sidebar-item:hover .hide-on-collapse {
        font-weight: 600;
    }

    /* Estado colapsado escritorio */
    #sidebar.collapsed { width: 5rem; }
    #sidebar.collapsed .hide-on-collapse { opacity: 0; width: 0; overflow: hidden; white-space: nowrap; }
    #sidebar.collapsed #hideSidebar i { transform: rotate(180deg); }

    /* Móvil: off-canvas */
    @media (max-width: 768px) {
        #sidebar { transform: translateX(-100%); width: 16rem !important; top:64px; height:calc(100vh - 64px); }
        #sidebar.active { transform: translateX(0); }
        #sidebar.collapsed { width:16rem !important; }
        #sidebar.collapsed .hide-on-collapse { opacity:1 !important; width:auto !important; overflow:visible !important; }
        #sidebarOverlay.active { display:block; }
        
        /* Reducir efectos en móvil para mejor rendimiento */
        .sidebar-item:hover {
            transform: none;
        }
    }

    /* Transiciones */
    .hide-on-collapse { transition: opacity .3s ease-in-out, width .3s ease-in-out; }

    body.sidebar-open { overflow:hidden; }
    @media (max-width:768px){ body.sidebar-open { position:fixed; width:100%; } }

    /* Eliminado el margin-left previo de .main-content para que footer abarque todo */
</style>