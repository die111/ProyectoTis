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

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content Wrapper -->
            <div class="flex-1 flex flex-col min-h-screen main-content bg-gray-100">

                <!-- Page Header -->
                @if(isset($header))
                    <header class="bg-white shadow-sm">
                        <div class="px-4 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Main Content Area -->
                <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                    <!-- Breadcrumb -->
                    @if(isset($breadcrumb))
                        <nav class="mb-4" aria-label="Breadcrumb">
                            <ol class="flex items-center space-x-2 text-sm text-gray-500">
                                {{ $breadcrumb }}
                            </ol>
                        </nav>
                    @endif

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                            </div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                                <div>
                                    <p class="font-medium">Se encontraron los siguientes errores:</p>
                                    <ul class="mt-1 list-disc list-inside">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Contenido Variable -->
                    @yield('content')
                    {{ $slot ?? '' }}
                </main>

            </div>
        </div>

        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // Auto-hide alerts after 5 seconds
            setTimeout(() => {
                const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
                alerts.forEach(alert => {
                    if (alert.parentNode) {
                        alert.style.transition = 'opacity 0.5s ease-out';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }
                });
            }, 5000);

            // SweetAlert2 global alerts
            @if(session('swal_success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('swal_success') }}',
                    confirmButtonColor: '#0C3E92',
                });
            @endif
            @if(session('swal_error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('swal_error') }}',
                    confirmButtonColor: '#0C3E92',
                });
            @endif
        </script>

        @stack('scripts')
    </body>
    </html>