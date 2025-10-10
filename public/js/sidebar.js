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
        
        // Inicializar selección activa con un pequeño delay para Alpine.js
        setTimeout(() => {
            handleActiveSelection();
        }, 100);
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

    // Función para manejar la selección activa
    function handleActiveSelection() {
        // Obtener la URL actual
        const currentPath = window.location.pathname;
        
        // Remover clase activa de todos los elementos
        document.querySelectorAll('.sidebar-item').forEach(item => {
            item.classList.remove('sidebar-item-active');
        });
        
        // Encontrar y marcar el elemento activo basado en la URL actual
        let activeFound = false;
        document.querySelectorAll('.sidebar-item[href]').forEach(item => {
            const href = item.getAttribute('href');
            if (href && href !== '#' && (currentPath === href || currentPath.startsWith(href + '/'))) {
                item.classList.add('sidebar-item-active');
                activeFound = true;
                
                // Si es un elemento de submenú, expandir el menú padre
                const parentSubmenu = item.closest('ul[x-show]');
                if (parentSubmenu) {
                    const parentLi = parentSubmenu.closest('li[x-data]');
                    if (parentLi) {
                        // Buscar el componente Alpine.js y forzar la apertura
                        const alpineComponent = parentLi._x_dataStack && parentLi._x_dataStack[0];
                        if (alpineComponent) {
                            alpineComponent.open = true;
                        }
                    }
                }
            }
        });
        
        // Si no se encontró elemento activo por URL, usar localStorage
        if (!activeFound) {
            const activeItem = localStorage.getItem('activeMenuItem');
            if (activeItem) {
                const element = document.querySelector(`[data-menu="${activeItem}"]`);
                if (element) {
                    element.classList.add('sidebar-item-active');
                }
            }
        }
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

    // Agregar event listeners a elementos del sidebar
    document.querySelectorAll('.sidebar-item').forEach(item => {
        item.addEventListener('click', function(e) {
            // Solo procesar si es un enlace real (no #)
            const href = this.getAttribute('href');
            if (href && href !== '#') {
                // Remover clase activa de todos los elementos
                document.querySelectorAll('.sidebar-item').forEach(el => {
                    el.classList.remove('sidebar-item-active');
                });
                
                // Agregar clase activa al elemento clickeado
                this.classList.add('sidebar-item-active');
                
                // Guardar en localStorage
                const menuId = this.getAttribute('data-menu') || this.textContent.trim();
                localStorage.setItem('activeMenuItem', menuId);
            }
        });
    });

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
    handleActiveSelection();
});