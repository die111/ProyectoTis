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
        
        @stack('styles')
    </head>
    <body class="bg-gray-50 font-sans antialiased">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Top Navigation -->
                <header class="bg-white shadow-sm border-b border-gray-200 lg:hidden">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button @click="sidebarOpen = !sidebarOpen" 
                                class="text-gray-500 hover:text-gray-600 focus:outline-none">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-trophy text-yellow-500"></i>
                            <span class="text-gray-900 font-medium">Oh! SanSi</span>
                        </div>
                    </div>
                </header>

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

                    <!-- Page Content -->
                    @yield('content')
                    {{ $slot ?? '' }}
                </main>

                <!-- Footer -->
                <footer class="bg-white border-t border-gray-200 px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <p>© {{ date('Y') }} Universidad Mayor de San Simón - Oh! SanSi</p>
                        <p>Versión 1.0</p>
                    </div>
                </footer>
            </div>
        </div>

        <!-- Scripts -->
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
        </script>
        
        @stack('scripts')
    </body>
    </html>