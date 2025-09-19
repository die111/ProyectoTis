<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Oh! SanSi - Olimpiada en Ciencias y Tecnología</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Mulish:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

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
            background-color: var(--color-white);
            color: var(--color-black);
        }

        .section-container {
            padding: 60px 50px;
        }

        @media (max-width: 992px) {
            .section-container {
                padding: 40px 30px;
            }
        }

        @media (max-width: 768px) {
            .section-container {
                padding: 30px 20px;
            }
        }

        /* Hero Section */
        .hero-content {
            display: flex;
            align-items: center;
            gap: 27px;
        }

        .hero-image-container {
            flex: 0 0 379px;
        }

        .hero-image-container img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px;
        }

        .hero-text-container {
            flex: 1;
        }

        .hero-text-container p {
            color: var(--color-text-dark);
            font-family: var(--font-mulish);
            font-weight: 400;
            font-size: 21px;
            line-height: 1.5;
            margin: 0;
        }

        @media (max-width: 992px) {
            .hero-content {
                flex-direction: column;
                text-align: center;
            }
            .hero-image-container {
                flex: 0 0 auto;
                max-width: 500px;
            }
        }

        /* Olympiads Section */
        .olympiads-section {
            text-align: center;
            background-color: var(--color-white);
        }

        .olympiads-title {
            color: var(--color-black);
            font-family: var(--font-poppins);
            font-weight: 700;
            font-size: 20px;
            line-height: 26px;
            margin: 0 0 37px 0;
        }

        .olympiads-description {
            max-width: 1392px;
            margin: 0 auto;
            color: var(--color-text-dark);
            font-family: var(--font-mulish);
            font-weight: 400;
            font-size: 21px;
            line-height: 1.5;
        }

        /* Subjects Section */
        .subjects-section {
            background-color: var(--color-light-bg);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .subjects-title-wrapper {
            text-align: center;
            margin-bottom: 40px;
        }

        .subjects-title {
            display: inline-block;
            position: relative;
            color: var(--color-black);
            font-family: var(--font-poppins);
            font-weight: 700;
            font-size: 20px;
            line-height: 26px;
            margin: 0;
            padding-bottom: 16px;
        }

        .subjects-title::before {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 224px;
            height: 1px;
            background-color: var(--color-black);
        }

        .subjects-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 94px;
            height: 4px;
            background-color: var(--color-black);
        }

        .carousel-wrapper {
            position: relative;
            width: 100%;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            display: flex;
            align-items: center;
        }

        .carousel-track {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
            padding: 40px 0;
            width: 100%;
        }

        .subject-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            text-align: center;
            flex-shrink: 0;
        }

        .subject-icon {
            height: 80px;
            width: 80px;
            background-color: var(--color-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
        }

        .subject-name {
            font-family: var(--font-poppins);
            font-weight: 600;
            font-size: 16px;
            color: var(--color-black);
        }

        /* Footer */
        .site-footer {
            background-color: var(--color-primary);
            color: var(--color-white);
            padding: 40px 50px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 30px;
        }

        .footer-info h4 {
            font-family: var(--font-poppins);
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            margin: 0 0 12px 0;
        }

        .footer-info p {
            font-family: var(--font-poppins);
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            margin: 0;
        }

        .footer-credits {
            text-align: right;
        }

        .footer-credits p {
            font-family: var(--font-poppins);
            font-weight: 400;
            font-size: 16px;
            line-height: 24px;
            margin: 0;
            margin-bottom: 16px;
        }

        .footer-credits p:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 992px) {
            .footer-content {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }
            .footer-credits {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header/Navigation -->
    <x-nav-header />

    <!-- Contenido principal variable -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-info">
                <h4>UNIVERSIDAD MAYOR DE SAN SIMÓN</h4>
                <p>Dirección: Av. Oquendo y Jordan, Cochabamba - Bolivia<br>Teléfono: (591)</p>
            </div>
            <div class="footer-credits">
                <p>Copyright © {{ date('Y') }} FullCoders - Todos los derechos reservados</p>
                <p>Proyecto desarrollado en colaboración con la<br>Universidad Mayor de San Simón</p>
                <p>Web diseñada y gestionada por FullCoders</p>
            </div>
        </div>
    </footer>
</body>
</html>