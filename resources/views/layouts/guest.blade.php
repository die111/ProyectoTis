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
    <link rel="stylesheet" href="{{ asset('css/figma-design.css') }}">
    @stack('styles')
    

    <style>
        /* Estilos para la página welcome */
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        .max-w-7xl {
            width: 100%;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <x-nav-header :guest="true" />

    <!-- Contenido principal -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />
    <script src="{{ asset('js/auth.js') }}"></script>
    @stack('scripts')
</body>
</html>