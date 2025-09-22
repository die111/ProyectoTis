<!-- Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-primary-900 text-white transition-all duration-300 ease-in-out transform" id="sidebar">
    <div class="flex flex-col h-full">
        <!-- Top section -->
        <div class="flex-1 overflow-y-auto">
            <!-- Toggle button -->
            <div class="px-4 py-3 flex items-center justify-between border-b border-gray-700">
                <a href="#" class="flex items-center space-x-3" id="menuToggle">
                    <i class="fas fa-bars text-xl"></i>
                    <span class="font-medium text-lg">Menú</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="mt-5 px-2">
                <ul class="space-y-1">
                    @foreach($menuItems as $item)
                        <li>
                            <a href="{{ isset($item['route']) && $item['route'] !== '#' ? route($item['route']) : '#' }}" 
                               class="group flex items-center px-4 py-2 text-sm font-medium rounded-md transition-colors duration-150 {{ isset($item['active']) && $item['active'] ? 'bg-primary-800 text-white' : 'text-gray-300 hover:bg-primary-800 hover:text-white' }}">
                                <i class="{{ $item['icon'] }} w-6 h-6 mr-3 text-lg"></i>
                                <span>{{ $item['name'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <!-- Bottom section -->
        <div class="border-t border-gray-700 p-4 space-y-2">
            {{-- <a href="{{ route('#') }}" class="flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-colors duration-150"> --}}
                <i class="fas fa-user w-6 h-6 mr-3 text-lg"></i>
                <span>Perfil</span>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-colors duration-150">
                    <i class="fas fa-sign-out-alt w-6 h-6 mr-3 text-lg"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>

            <button id="hideSidebar" class="w-full flex items-center px-4 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-primary-800 hover:text-white transition-colors duration-150">
                <i class="fas fa-chevron-left w-6 h-6 mr-3 text-lg transition-transform duration-300"></i>
                <span>Ocultar</span>
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
    }

    #sidebar {
        background-color: var(--primary-900);
    }

    #sidebar.collapsed {
        width: 5rem;
    }

    #sidebar.collapsed .hide-on-collapse {
        display: none;
    }

    #sidebar.collapsed #hideSidebar i {
        transform: rotate(180deg);
    }

    @media (max-width: 768px) {
        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar.active {
            transform: translateX(0);
        }

        #sidebarOverlay.active {
            display: block;
        }
    }

    .main-content {
        margin-left: 16rem;
        transition: margin-left 0.3s ease-in-out;
    }

    .main-content.sidebar-collapsed {
        margin-left: 5rem;
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
        
        .main-content.sidebar-collapsed {
            margin-left: 0;
        }
    }
</style>

<style>
    :root {
        --primary-dark-blue: #091c47;
        --primary-light-gray: #f5f4f4;
        --secondary-gray: #eaeaea;
        --text-white: #ffffff;
        --text-dark: #000000;
        --text-gray: #3a4651;
        --font-poppins: 'Poppins', sans-serif;
        --font-ubuntu: 'Ubuntu', sans-serif;
        --font-roboto: 'Roboto', sans-serif;
        --sidebar-width: 235px;
        --sidebar-collapsed-width: 70px;
        --sidebar-transition: 0.3s ease-in-out;
    }

    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        height: 100vh;
        width: var(--sidebar-width);
        background-color: var(--primary-dark-blue);
        color: var(--text-white);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: width var(--sidebar-transition);
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        overflow-x: hidden;
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    .sidebar-top {
        flex-grow: 1;
        overflow-y: auto;
        padding: 1rem 0;
    }

    .sidebar-bottom {
        padding: 1rem 0;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .sidebar-item {
        display: flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        color: var(--text-white);
        transition: background-color var(--sidebar-transition);
        white-space: nowrap;
        cursor: pointer;
    }

    .sidebar-item:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .sidebar-item.active {
        background-color: rgba(255, 255, 255, 0.15);
        border-left: 4px solid var(--text-white);
    }

    .sidebar-icon {
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .sidebar.collapsed .sidebar-icon {
        margin-right: 0;
    }

    .sidebar-item span {
        transition: opacity var(--sidebar-transition);
    }

    .sidebar.collapsed .sidebar-item span {
        opacity: 0;
        width: 0;
        height: 0;
        overflow: hidden;
    }

    .sidebar-divider {
        margin: 1rem 0;
        border: none;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .logout-btn {
        width: 100%;
        background: none;
        border: none;
        text-align: left;
        font-family: inherit;
        font-size: inherit;
        color: inherit;
        cursor: pointer;
    }

    .logout-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .hide-button {
        transition: transform var(--sidebar-transition);
    }

    .sidebar.collapsed .hide-button .hide-icon {
        transform: rotate(180deg);
    }

    /* Mobile Styles */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.active {
            transform: translateX(0);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }
    }

    /* Ensure main content is pushed when sidebar is open */
    .page-wrapper {
        margin-left: var(--sidebar-width);
        transition: margin-left var(--sidebar-transition);
    }

    .page-wrapper.sidebar-collapsed {
        margin-left: var(--sidebar-collapsed-width);
    }
}

.header-container {
  max-width: 1440px;
  margin: 0 auto;
  padding: 0 88px;
  display: flex;
  align-items: center;
}

.header-top {
  background-color: var(--primary-light-gray);
}

.header-top .header-container {
  height: 104px;
  justify-content: space-between;
}

.logo-container {
  display: flex;
  align-items: center;
  gap: 15px;
}

.logo-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
}

.brand-name {
  font-family: var(--font-poppins);
  font-weight: 700;
  font-size: 40px;
  color: var(--text-dark);
}

.main-nav {
  display: flex;
  align-items: center;
  gap: 80px;
}

.main-nav a {
  font-family: var(--font-poppins);
  font-weight: 700;
  font-size: 16px;
  transition: color 0.3s ease;
}

.main-nav a:hover {
  color: var(--primary-dark-blue);
}

.header-bottom {
  background-color: var(--primary-dark-blue);
}

.header-bottom .header-container {
  height: 74px;
  justify-content: flex-end;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 11px;
  color: var(--text-white);
  font-family: var(--font-ubuntu);
  font-size: 16px;
}

.user-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 14px;
}

/* Main Section */
.main-section {
  display: flex;
  flex-grow: 1;
  width: 100%;
  max-width: 1440px;
  margin: 0 auto;
  position: relative;
}

/* Sidebar Styles */
.sidebar {
  width: var(--sidebar-width);
  background-color: var(--primary-dark-blue);
  color: var(--text-white);
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  flex-shrink: 0;
  padding: 91px 0 0;
  position: fixed;
  top: 178px;
  left: 0;
  height: calc(100vh - 178px);
  transition: all 0.3s ease;
  z-index: 50;
  overflow-y: auto;
}

.sidebar.collapsed {
  width: var(--sidebar-collapsed-width);
}

.sidebar.collapsed .sidebar-item span {
  opacity: 0;
  visibility: hidden;
}

.sidebar.collapsed .sidebar-divider {
  margin: 8px 10px;
}

.sidebar-top {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.sidebar-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 13px 30px;
  font-family: var(--font-ubuntu);
  font-size: 16px;
  line-height: 24px;
  transition: all 0.3s ease;
  cursor: pointer;
  border: none;
  background: transparent;
  width: 100%;
  text-align: left;
  color: var(--text-white);
}

.sidebar-item:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.sidebar-item.active {
  background-color: rgba(255, 255, 255, 0.15);
  border-right: 4px solid var(--text-white);
}

.sidebar.collapsed .sidebar-item {
  padding: 13px 23px;
  justify-content: center;
}

.icon-wrapper {
  position: relative;
  width: 26px;
  height: 26px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar-icon {
  width: 24px;
  height: 24px;
  flex-shrink: 0;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sidebar-item span {
  transition: all 0.3s ease;
  white-space: nowrap;
}

.sidebar-divider {
  border: none;
  height: 1px;
  background-color: var(--text-white);
  margin: 8px 30px;
  transition: margin 0.3s ease;
}

.sidebar-nav ul {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.sidebar-bottom {
  padding-bottom: 15px;
}

.logout-btn {
  border: none;
  background: transparent;
  color: var(--text-white);
  font-family: var(--font-ubuntu);
  font-size: 16px;
  cursor: pointer;
}

.logout-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.hide-button {
  color: var(--text-dark);
  background-color: var(--text-white);
  border-radius: 0 20px 20px 0;
  margin-right: -13px;
  padding-left: 20px;
}

.hide-icon {
  transition: transform 0.3s ease;
}

.sidebar.collapsed .hide-icon {
  transform: rotate(180deg);
}

/* Main Content */
.main-content {
  flex-grow: 1;
  margin-left: var(--sidebar-width);
  padding: 46px 54px;
  background-color: #fff;
  transition: margin-left 0.3s ease;
  min-height: calc(100vh - 178px);
}

.sidebar.collapsed + .main-content {
  margin-left: var(--sidebar-collapsed-width);
}

.content-header {
  background-color: var(--secondary-gray);
  padding: 33px 30px;
  height: 106px;
  display: flex;
  align-items: center;
  margin: -46px -54px 20px -54px;
}

.content-header h1 {
  margin: 0;
  font-family: var(--font-roboto);
  font-weight: 400;
  font-size: 32px;
  color: var(--text-gray);
}

.content-body {
  padding-top: 20px;
}

/* Mobile Sidebar Overlay */
.sidebar-overlay {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 40;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.sidebar-overlay.active {
  display: block;
  opacity: 1;
}

/* Footer */
.site-footer {
  background-color: var(--primary-dark-blue);
  color: var(--text-white);
  padding: 43px 0;
  font-family: var(--font-poppins);
  font-size: 16px;
  line-height: 24px;
  flex-shrink: 0;
}

.footer-container {
  max-width: 1440px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  padding: 0 15px 0 29px;
}

.footer-left {
  text-align: left;
}

.university-name {
  margin: 0 0 12px;
  font-weight: 400;
}

.address-info {
  margin: 0;
  font-weight: 400;
}

.footer-right {
  text-align: right;
}

.footer-right p {
  margin: 0;
  font-weight: 400;
}

.footer-right p:not(:last-child) {
  margin-bottom: 24px;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .header-container {
    padding: 0 40px;
  }
  .main-nav {
    gap: 40px;
  }
}

@media (max-width: 992px) {
  .header-top .header-container {
    flex-direction: column;
    height: auto;
    padding-top: 20px;
    padding-bottom: 20px;
    gap: 20px;
  }
  .main-nav {
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px 40px;
  }
  
  .footer-container {
    flex-direction: column;
    gap: 30px;
    align-items: center;
    text-align: center;
    padding: 0 20px;
  }
  .footer-left,
  .footer-right {
    text-align: center;
  }
}

@media (max-width: 768px) {
  /* Mobile sidebar behavior */
  .sidebar {
    position: fixed;
    top: 0;
    left: -100%;
    width: var(--sidebar-width);
    height: 100vh;
    padding-top: 20px;
    z-index: 1000;
    transition: left 0.3s ease;
  }
  
  .sidebar.mobile-open {
    left: 0;
  }
  
  .main-content {
    margin-left: 0;
    padding: 20px;
  }
  
  .content-header {
    margin: -20px -20px 20px -20px;
    padding: 20px;
    height: auto;
  }
  
  .content-header h1 {
    font-size: 24px;
  }
  
  .brand-name {
    font-size: 28px;
  }
  
  .header-container {
    padding: 0 20px;
  }
}

@media (max-width: 480px) {
  .brand-name {
    font-size: 24px;
  }
  
  .main-nav {
    gap: 15px 20px;
  }
  
  .main-nav a {
    font-size: 14px;
  }
  
  .user-profile {
    font-size: 14px;
    gap: 8px;
  }
  
  .user-avatar {
    width: 35px;
    height: 35px;
    font-size: 12px;
  }
}

/* Custom scrollbar for sidebar */
.sidebar::-webkit-scrollbar {
  width: 4px;
}

.sidebar::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
}

.sidebar::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
}

.sidebar::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}
</style>