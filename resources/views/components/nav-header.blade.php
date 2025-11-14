<!-- Estilos -->
<link rel="stylesheet" href="{{ asset('css/home/nav-header.css') }}">

<header class="site-header">
    <!-- Logo -->
    <a href="{{ route('welcome') }}" class="logo-link">
        <div class="logo-image">
            <img src="{{ asset('images/logo_animado.gif') }}" alt="Oh! SanSi Logo">
        </div>
        <h1 class="logo-text">Oh! SanSi</h1>
    </a>

    <!-- Desktop Navigation -->
    <nav class="main-navigation">
        <ul>
            <li>
                <a href="{{ route('welcome') }}" class="{{ request()->routeIs('welcome') ? 'active' : '' }}">
                    INICIO
                </a>
            </li>
            <li>
                <a href="#" class="{{ request()->routeIs('documents.*') ? 'active' : '' }}">
                    DOCUMENTOS
                </a>
            </li>
            <li>
                <a href="{{ route('etapas.index') }}" class="{{ request()->routeIs('stages.*') ? 'active' : '' }}">
                    ETAPAS
                </a>
            </li>
            <li>
               <a href="{{ route('clasificados.index') }}" class="{{ request()->routeIs('clasificados.*') ? 'active' : '' }}">
                   CLASIFICADOS
               </a>
            </li>
            <li>
                 <a href="{{ route('contactos') }}" class="{{ request()->routeIs('contactos') ? 'active' : '' }}">
                    CONTACTOS
                </a>
            </li>
        </ul>
    </nav>

    <!-- Mobile Menu Toggle -->
    <div class="mobile-menu-toggle" onclick="toggleMobileMenu()">
        <span></span>
        <span></span>
        <span></span>
    </div>

    <!-- Login Button -->
    @guest
        <a href="{{ route('login') }}" class="login-button">
            <img src="{{ asset('images/icono.png') }}" alt="icono" class="login-icon"
                style="width: 28px; min-width: 28px; max-width: 28px;">
            <span>Iniciar Sesión</span>
        </a>
    @else
        <div class="flex items-center space-x-4">
            <span class="text-black font-medium">{{ Auth::user()->name }}</span>
            <!-- Notificaciones -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="focus:outline-none relative">
                    <i class="fas fa-bell text-gray-700 text-lg"></i>
                    @php
                        // Mantener la colección de no leídas para el dropdown/acciones.
                        $unreadNotifications = Auth::user()->unreadNotifications;
                    @endphp
                    {{-- Mostrar el badge en el header (solo si hay notificaciones no leídas) --}}
                    @if($unreadNotifications->count() > 0)
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5 animate-pulse">
                            {{ $unreadNotifications->count() > 99 ? '99+' : $unreadNotifications->count() }}
                        </span>
                    @endif
                </button>
                <!-- Dropdown -->
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-96 bg-white border border-gray-200 rounded-lg shadow-lg z-50" style="display: none;" x-transition>
                    <div class="p-4 border-b flex items-center justify-between">
                        <span class="font-semibold text-gray-700">Notificaciones</span>
                        @if($unreadNotifications->count() > 0)
                            <button onclick="marcarTodasLeidas()" class="text-xs text-blue-600 hover:text-blue-800">
                                Marcar todas como leídas
                            </button>
                        @endif
                    </div>
                    <ul class="max-h-96 overflow-y-auto">
                        @forelse(Auth::user()->notifications->take(10) as $notification)
                            <li class="px-4 py-3 text-sm {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }} border-b last:border-b-0 hover:bg-gray-50 cursor-pointer"
                                onclick="window.location.href='{{ route('notifications.show', $notification->id) }}'">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mr-3">
                                        @if(isset($notification->data['tipo']))
                                            @if($notification->data['tipo'] === 'success')
                                                <i class="fas fa-check-circle text-green-500"></i>
                                            @elseif($notification->data['tipo'] === 'error')
                                                <i class="fas fa-exclamation-circle text-red-500"></i>
                                            @else
                                                <i class="fas fa-info-circle text-blue-500"></i>
                                            @endif
                                        @else
                                            <i class="fas fa-bell text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800">{{ $notification->data['title'] ?? $notification->data['titulo'] ?? 'Notificación' }}</p>
                                        <p class="text-gray-600 mt-1">{{ $notification->data['message'] ?? $notification->data['mensaje'] ?? 'Tienes una nueva notificación' }}</p>
                                        <span class="block text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if(!$notification->read_at)
                                        <div class="flex-shrink-0 ml-2">
                                            <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                                        </div>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <li class="px-4 py-6 text-sm text-gray-500 text-center">
                                <i class="fas fa-bell-slash text-3xl text-gray-300 mb-2"></i>
                                <p>No tienes notificaciones</p>
                            </li>
                        @endforelse
                    </ul>
                    @if(Auth::user()->notifications->count() > 0)
                        <div class="p-3 border-t text-center">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-list mr-1"></i>Ver todas las notificaciones
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            @if(!request()->is('dashboard') && !request()->is('panel-control') && !request()->is('admin/*'))
                <a href="{{ route('dashboard') }}" class="login-button bg-green-600 hover:bg-green-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Volver al Panel</span>
                </a>
            @endif
        </div>
    @endguest
</header>

<!-- Mobile Menu -->
<nav class="mobile-menu" id="mobileMenu">
    <ul>
        <li>
            <a href="{{ route('welcome') }}">INICIO</a>
        </li>
        <li>
            <a href="#">DOCUMENTOS</a>
        </li>
        <li>
            <a href="#">ETAPAS</a>
        </li>
        <li>
            <a href="#">CLASIFICADOS</a>
        </li>
        <li>
             <a href="{{ route('contactos') }}" class="{{ request()->routeIs('contactos') ? 'active' : '' }}">
              CONTACTOS
             </a>
        </li>
        @guest
            <li>
                <a href="{{ route('login') }}">INICIAR SESIÓN</a>
            </li>
        @else
            <li>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="w-full text-left">CERRAR SESIÓN</button>
                </form>
            </li>
        @endguest
    </ul>
</nav>

<script>
    function marcarComoLeida(notificationId, url) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && url && url !== '#') {
                window.location.href = url;
            } else if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function marcarTodasLeidas() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobileMenu');
        const toggle = document.querySelector('.mobile-menu-toggle');

        if (mobileMenu.style.display === 'block') {
            mobileMenu.style.display = 'none';
            toggle.classList.remove('active');
        } else {
            mobileMenu.style.display = 'block';
            toggle.classList.add('active');
        }
    }

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobileMenu');
        const toggle = document.querySelector('.mobile-menu-toggle');
        const header = document.querySelector('.site-header');

        if (!header.contains(event.target) && mobileMenu.style.display === 'block') {
            mobileMenu.style.display = 'none';
            toggle.classList.remove('active');
        }
    });

    // Close mobile menu on window resize if screen becomes larger
    window.addEventListener('resize', function() {
        if (window.innerWidth > 992) {
            document.getElementById('mobileMenu').style.display = 'none';
            document.querySelector('.mobile-menu-toggle').classList.remove('active');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const header = document.querySelector('.site-header');
        let lastScrollTop = 0;

        window.addEventListener('scroll', function() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            lastScrollTop = scrollTop;
        });
    });
</script>
