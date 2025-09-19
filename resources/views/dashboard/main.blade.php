    @extends('layouts.app')

    @section('title', 'Dashboard Administrador')

    @section('content')
        <div class="space-y-6">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Panel de Administración</h1>
                        <p class="text-gray-600 mt-1">Bienvenido, {{ Auth::user()->name }}</p>
                    </div>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-calendar mr-1"></i>
                        <span
                            id="bolivia-time">{{ \Carbon\Carbon::now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <h1>Vista dashboard general</h1>

        @section('scripts')
            <script>
                function updateBoliviaTime() {
                    const now = new Date();
                    const utc = now.getTime() + (now.getTimezoneOffset() * 60000);

                    const boliviaOffset = -4 * 60;
                    const boliviaTime = new Date(utc + (3600000 * boliviaOffset / 60));

                    const day = boliviaTime.getDate().toString().padStart(2, '0');
                    const month = (boliviaTime.getMonth() + 1).toString().padStart(2, '0');
                    const year = boliviaTime.getFullYear();
                    const hours = boliviaTime.getHours().toString().padStart(2, '0');
                    const minutes = boliviaTime.getMinutes().toString().padStart(2, '0');

                    document.getElementById('bolivia-time').textContent = `${day}/${month}/${year} ${hours}:${minutes}`;
                }

                updateBoliviaTime();
                setInterval(updateBoliviaTime, 60000);
            </script>
        @endsection
