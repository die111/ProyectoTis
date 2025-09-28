<!-- Sidebar -->
<aside class="fixed top-[64px] left-0 z-40 w-64 bg-[#091c47] text-white transition-all duration-300 ease-in-out transform h-[calc(100vh-64px)]" id="sidebar">
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
                            <li class="relative" x-data="{ open: false }">
                                <button type="button" @click="open = !open" class="group flex items-center px-4 py-2 w-full text-sm font-medium rounded-md transition-colors duration-150 text-gray-300 hover:bg-primary-800 hover:text-white focus:outline-none">
                                    <i class="{{ $item['icon'] }} w-6 h-6 mr-3 text-lg"></i>
                                    <span class="hide-on-collapse">{{ $item['name'] }}</span>
                                    <i :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fas ml-auto transition-transform duration-200"></i>
                                </button>
                                <ul class="ml-8 mt-1 space-y-1" x-show="open" x-transition>
                                    @foreach($item['submenu'] as $sub)
                                        <li>
                                            <a href="{{ route($sub['route']) }}" class="group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ isset($sub['active']) && $sub['active'] ? 'bg-primary-800 text-white' : 'text-gray-300 hover:bg-primary-800 hover:text-white' }}">
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
                                   class="group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ isset($item['active']) && $item['active'] ? 'bg-primary-800 text-white' : 'text-gray-300 hover:bg-primary-800 hover:text-white' }}">
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
            <a href="#" class="sidebar-item flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-colors duration-150">
                <i class="fas fa-user w-6 h-6 mr-3 text-lg"></i>
                <span class="hide-on-collapse">Perfil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="sidebar-item w-full flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-colors duration-150">
                    <i class="fas fa-sign-out-alt w-6 h-6 mr-3 text-lg"></i>
                    <span class="hide-on-collapse">Cerrar Sesión</span>
                </button>
            </form>

            <button id="hideSidebar" class="sidebar-item w-full flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-colors duration-150">
                <i class="fas fa-chevron-left w-6 h-6 mr-3 text-lg transition-transform duration-300"></i>
                <span class="hide-on-collapse">Ocultar</span>
            </button>
        </div>
    </div>
</aside>

<!-- Mobile sidebar overlay -->
<div class="fixed inset-0 bg-gray-900 bg-opacity-50 z-30 hidden transition-opacity duration-300" id="sidebarOverlay"></div>

<style>
    :root {
        --primary-900: #091c47;
        --primary-800: #112a66;
    }

    #sidebar {
        background-color: var(--primary-900);
    }

    /* Desktop collapsed state */
    #sidebar.collapsed {
        width: 5rem;
    }

    #sidebar.collapsed .hide-on-collapse {
        opacity: 0;
        width: 0;
        overflow: hidden;
        white-space: nowrap;
    }

    #sidebar.collapsed #hideSidebar i {
        transform: rotate(180deg);
    }

    /* Mobile styles */
    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
            width: 16rem !important; /* Forzar ancho completo en móvil */
            top: 64px; /* Altura del header */
            height: calc(100vh - 64px); /* Altura total menos header */
        }

        #sidebar.active {
            transform: translateX(0);
        }

        #sidebar.collapsed {
            width: 16rem !important; /* En móvil nunca colapsar */
        }

        #sidebar.collapsed .hide-on-collapse {
            opacity: 1 !important;
            width: auto !important;
            overflow: visible !important;
        }

        #sidebar .fas,
        #sidebar .fa {
            display: inline-block !important;
            width: 1.5rem !important;
            text-align: center !important;
        }

        #sidebar a,
        #sidebar button {
            display: flex !important;
            align-items: center !important;
        }

        #sidebarOverlay.active {
            display: block;
        }
    }

    /* Main content adjustments */
    .main-content {
        margin-left: 16rem;
        transition: margin-left 0.3s ease-in-out;
    }

    .main-content.sidebar-collapsed {
        margin-left: 5rem;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0 !important;
        }
        
        .main-content.sidebar-collapsed {
            margin-left: 0 !important;
        }
    }

    /* Smooth transitions */
    .hide-on-collapse {
        transition: opacity 0.3s ease-in-out, width 0.3s ease-in-out;
    }

    body.sidebar-open {
        overflow: hidden;
    }

    @media (max-width: 768px) {
        body.sidebar-open {
            position: fixed;
            width: 100%;
        }
    }
</style>