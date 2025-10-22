document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const hideSidebar = document.getElementById('hideSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mainContent = document.querySelector('.main-content');

    // Recuperar el estado del sidebar del localStorage
    const sisSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    // Aplicar el estado inicial
    if (isSidebarCollapsed) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('sidebar-collapsed');
        document.querySelectorAll('.hide-on-collapse').forEach(el => el.style.display = 'none');
    }

    let isSidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    // Inicializar el estado del sidebar
    if (isSidebarCollapsed) {
        sidebar.classList.add('collapsed');
        mainContent.classList.add('sidebar-collapsed');
    }

    // Function to toggle sidebar
    function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('sidebar-collapsed');
        
        // Guardar el estado en localStorage
        isSidebarCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isSidebarCollapsed);
    }

    // Function to handle mobile sidebar
    function toggleMobileSidebar() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        document.body.classList.toggle('overflow-hidden');
    }

    // Event listeners
    if (menuToggle) {
        menuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            if (window.innerWidth <= 768) {
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
            if (window.innerWidth > 768) {
                toggleSidebar();
            } else {
                toggleMobileSidebar();
            }
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            toggleMobileSidebar();
        });
    }

    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.classList.remove('overflow-hidden');
            }
        }, 250);
    });
});