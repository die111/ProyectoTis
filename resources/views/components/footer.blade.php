<footer class="custom-footer @auth footer-with-sidebar @else footer-centered @endauth">
    <div class="custom-footer-container">
        <div class="custom-footer-content">
            <div class="footer-section footer-left-section">
                <h4 class="footer-title">UNIVERSIDAD MAYOR DE SAN SIMÓN</h4>
                <p class="footer-text">Dirección: Av. Oquendo y Jordan, Cochabamba - Bolivia<br>Teléfono: (591)</p>
            </div>
            <div class="footer-section footer-right-section">
                <p class="footer-text">Copyright © {{ date('Y') }} FullCoders - Todos los derechos reservados</p>
                <p class="footer-text footer-text-spacing">Proyecto desarrollado en colaboración con la<br>Universidad Mayor de San Simón</p>
                <p class="footer-text footer-text-spacing">Web diseñada y gestionada por FullCoders</p>
            </div>
        </div>
    </div>
</footer>

<style>
    /* Custom Footer Styles - Unified for both guest and authenticated users */
    .custom-footer {
        background-color: #091C47 !important;
        color: #ffffff !important;
        padding: 2rem 0 !important;
        width: 100% !important;
        margin-top: auto;
        position: relative;
        z-index: 10;
        font-family: 'Poppins', sans-serif;
        font-size: 16px;
        line-height: 24px;
    }

    /* Container behavior for centered footer (guest) */
    .footer-centered .custom-footer-container {
        width: 100%;
        margin: 0 auto;
        padding: 0 2rem;
    }

    /* Container behavior for footer with sidebar (authenticated) */
    .footer-with-sidebar {
        margin-left: 0 !important;
    }
    
    .footer-with-sidebar .custom-footer-container {
        width: 100%;
        margin: 0 auto;
        padding: 0 2rem; 
    }

    /* When sidebar is collapsed */
    @media (max-width: 768px) {
        .footer-with-sidebar .custom-footer-container {
            padding: 0 1rem;
        }
    }

    .custom-footer-content {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    @media (min-width: 768px) {
        .custom-footer-content {
            flex-direction: row;
            justify-content: space-between;
            align-items: flex-start;
        }
    }

    .footer-section {
        flex: 1;
    }

    .footer-left-section {
        text-align: left;
    }

    .footer-right-section {
        text-align: left;
    }

    @media (min-width: 768px) {
        .footer-right-section {
            text-align: right;
        }
    }

    .footer-title {
        font-weight: 700;
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
        color: #ffffff;
    }

    @media (min-width: 640px) {
        .footer-title {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }
    }

    .footer-text {
        font-size: 0.875rem;
        color: #ffffff;
        margin: 0;
        line-height: 1.6;
    }

    @media (min-width: 640px) {
        .footer-text {
            font-size: 1rem;
        }
    }

    .footer-text-spacing {
        margin-top: 0.5rem;
    }

    /* Responsive adjustments */
    @media (min-width: 640px) {
        .footer-centered .custom-footer-container {
            padding: 0 2rem;
        }

        .footer-with-sidebar .custom-footer-container {
            padding-left: 2rem;
            padding-right: 2rem;
        }
    }

    @media (min-width: 1024px) {
        .footer-centered .custom-footer-container {
            padding: 0 3rem;
        }

        .footer-with-sidebar .custom-footer-container {
            padding-left: 3rem;
            padding-right: 3rem;
        }
    }

    /* Mobile centering removed - always left/right alignment */
    @media (max-width: 767px) {
        .custom-footer-content {
            gap: 1rem;
        }
    }
</style>
