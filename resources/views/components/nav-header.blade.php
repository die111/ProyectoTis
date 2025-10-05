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
                <a href="#" class="{{ request()->routeIs('stages.*') ? 'active' : '' }}">
                    ETAPAS
                </a>
            </li>
            <li>
                <a href="#" class="{{ request()->routeIs('classified.*') ? 'active' : '' }}">
                    CLASIFICADOS
                </a>
            </li>
            <li>
                <a href="#" class="{{ request()->routeIs('contact.*') ? 'active' : '' }}">
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
            <img src="{{ asset('images/icono.jpg') }}" alt="icono" class="login-icon">
            <span>Iniciar Sesión</span>
        </a>
    @else
        <div class="flex items-center space-x-4">
            <span class="text-black font-medium">{{ Auth::user()->name }}</span>
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
            <a href="#">CONTACTOS</a>
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
