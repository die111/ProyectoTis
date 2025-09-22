document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const hideSidebar = document.getElementById('hideSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');
    
    // Estado del sidebar
    const sidebarState = {
        isCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
        isMobile: window.innerWidth <= 768
    };

    // Inicializar el estado del sidebar
    function initializeSidebar() {
        if (sidebarState.isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('sidebar-collapsed');
            document.querySelectorAll('.hide-on-collapse').forEach(el => {
                el.style.display = 'none';
            });
        }
    }

    // Función para alternar el sidebar en desktop
    function toggleSidebar() {
        sidebarState.isCollapsed = !sidebarState.isCollapsed;
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('sidebar-collapsed');
        
        document.querySelectorAll('.hide-on-collapse').forEach(el => {
            el.style.display = sidebarState.isCollapsed ? 'none' : '';
        });
        
        localStorage.setItem('sidebarCollapsed', sidebarState.isCollapsed);
    }

    // Función para manejar el sidebar en móvil
    function toggleMobileSidebar() {
        const isActive = sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        document.body.style.overflow = isActive ? 'hidden' : '';
    }

    // Event Listeners
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (sidebarState.isMobile) {
                toggleMobileSidebar();
            } else {
                toggleSidebar();
            }
        });
    }

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMobileSidebar();
        });
    }

    if (hideSidebar) {
        hideSidebar.addEventListener('click', function(e) {
            e.preventDefault();
            if (!sidebarState.isMobile) {
                toggleSidebar();
            } else {
                toggleMobileSidebar();
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleMobileSidebar);
    }

    // Manejar el redimensionamiento de la ventana
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            const wasMobile = sidebarState.isMobile;
            sidebarState.isMobile = window.innerWidth <= 768;

            if (wasMobile && !sidebarState.isMobile) {
                // Cambio de móvil a desktop
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
                initializeSidebar();
            } else if (!wasMobile && sidebarState.isMobile) {
                // Cambio de desktop a móvil
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('sidebar-collapsed');
            }
        }, 250);
    });

    // Inicializar
    initializeSidebar();
});