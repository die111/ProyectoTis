<!DOCTYPE html>
<html lang="es" x-data="{ sidebarOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Oh! SanSi - Olimpiada en Ciencias y Tecnología')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>

    @stack('styles')
</head>

<body class="bg-gray-100 font-sans antialiased">
    <!-- Navbar -->
    <x-nav-header :guest="false" />

    <div class="flex min-h-screen flex-col">
        <!-- Ajuste: añadir min-h para que la fila ocupe al menos el alto de la pantalla menos el header -->
        <div class="flex flex-1 min-h-[calc(100vh-64px)]">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-h-screen main-content bg-gray-100">

                <!-- Page Header -->
                @if (isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="px-4 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Main Content Area -->
                <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                    <!-- Breadcrumb -->
                    @if (isset($breadcrumb))
                        <nav class="mb-4" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                                {{ $breadcrumb }}
                            </ol>
                        </nav>
                    @endif

                    <!-- Alerts Tradicionales -->
                    {{-- <x-alert-clasic/> --}}

                    <!-- Contenido Variable -->
                    @yield('content')
                    {{ $slot ?? '' }}
                </main>

            </div>
        </div>
    </div>
    <!-- Footer fuera del contenedor flex principal -->
    @include('components.footer')
    @include('components.global-swal')

    @stack('scripts')
</body>

</html>