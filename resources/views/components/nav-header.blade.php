<style>
    :root {
        --color-primary: #091c47;
        --color-light-bg: #f5f4f4;
        --color-white: #ffffff;
        --color-black: #000000;
        --color-text-dark: #2d2d2d;
        --color-text-light: #fff8f8;
        --font-poppins: 'Poppins', sans-serif;
        --font-mulish: 'Mulish', sans-serif;
    }

    body {
        font-family: var(--font-poppins);
        margin: 0;
        background-color: var(--color-white);
        color: var(--color-black);
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    /* Header Styles */
    .site-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: var(--color-light-bg);
        height: 104px;
        padding: 0 50px;
        box-sizing: border-box;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        width: 100%;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo-link {
        display: flex;
        align-items: center;
        text-decoration: none;
        gap: 10px;
    }

    .logo-image {
        width: 150px;
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .logo-image img {
        max-width: 100%;
        height: auto;
    }

    .logo-text {
        color: var(--color-black);
        font-family: var(--font-poppins);
        font-weight: 700;
        font-size: 35px;
        line-height: 1.2;
        margin: 0;
    }

    .main-navigation ul {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
        gap: 38px;
    }

    .main-navigation a {
        color: var(--color-black);
        font-family: var(--font-poppins);
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        position: relative;
        padding-bottom: 5px;
        transition: color 0.3s ease;
    }

    .main-navigation a:hover {
        color: var(--color-primary);
    }

    .main-navigation a.active::after {
        content: '';
        position: absolute;
        bottom: -7px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--color-primary);
    }

    .login-button {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        background-color: var(--color-primary);
        color: var(--color-text-light);
        font-family: var(--font-poppins);
        font-weight: 600;
        font-size: 16px;
        padding: 16px 30px;
        border-radius: 20px;
        text-decoration: none;
        white-space: nowrap;
        transition: background-color 0.3s ease;
    }

    .login-button:hover {
        background-color: #0a1e4a;
    }

    /* Mobile menu toggle */
    .mobile-menu-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
        padding: 5px;
    }

    .mobile-menu-toggle span {
        display: block;
        width: 25px;
        height: 3px;
        background-color: var(--color-black);
        margin: 3px 0;
        transition: 0.3s;
    }

    /* Mobile menu */
    .mobile-menu {
        display: none;
        position: fixed;
        top: 104px;
        left: 0;
        right: 0;
        background-color: var(--color-light-bg);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        z-index: 999;
    }

    .mobile-menu ul {
        list-style: none;
        margin: 0;
        padding: 20px 0;
        display: flex;
        flex-direction: column;
    }

    .mobile-menu li {
        padding: 10px 50px;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .mobile-menu a {
        color: var(--color-black);
        font-family: var(--font-poppins);
        font-weight: 600;
        font-size: 16px;
        text-decoration: none;
    }

    .mobile-menu a:hover {
        color: var(--color-primary);
    }

    body {
        padding-top: 104px;
    }

    /* Responsive Styles */
    @media (max-width: 1200px) {
        .site-header {
            padding: 0 30px;
        }

        .main-navigation ul {
            gap: 20px;
        }

        .mobile-menu li {
            padding: 10px 30px;
        }
    }

    @media (max-width: 992px) {
        .main-navigation {
            display: none;
        }

        .mobile-menu-toggle {
            display: flex;
        }

        .site-header {
            justify-content: space-between;
        }

        .logo-text {
            font-size: 24px;
        }
    }

    @media (max-width: 768px) {
        .site-header {
            padding: 0 20px;
            height: 80px;
        }

        body {
            padding-top: 80px;
        }

        .mobile-menu {
            top: 80px;
        }

        .mobile-menu li {
            padding: 10px 20px;
        }

        .logo-text {
            font-size: 20px;
        }

    }

    @media (max-width: 480px) {
        .logo-text {
            font-size: 18px;
        }

        .login-button {
            padding: 12px 20px;
            font-size: 14px;
        }
    }
</style>

<header class="site-header">
    <!-- Logo -->
    <a href="{{ route('welcome') }}" class="logo-link">
        <div class="logo-image">
            <img src="{{ asset('images/logo.png') }}" alt="Oh! SanSi Logo">
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
