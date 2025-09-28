@extends('layouts.app')

@section('title', 'Dashboard Responsable')
@section('page-title', 'Panel de Administración')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Bienvenido, {{ Auth::user()->name }}</h2>
                <p class="text-gray-600 mt-1">{{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}</p>
                @if(Auth::user()->area)
                    <p class="text-sm text-gray-500 mt-1">Área: {{ Auth::user()->area }}</p>
                @endif
            </div>
            <div class="text-sm text-gray-500">
                <i class="fas fa-calendar mr-1"></i>
                <span id="bolivia-time">{{ \Carbon\Carbon::now()->setTimezone('America/La_Paz')->format('d/m/Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateBoliviaTime() {
        const now = new Date();
        const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
        
        const boliviaOffset = -4 * 60;
        const boliviaTime = new Date(utc + (3600000 * boliviaOffset/60));
        
        const day = boliviaTime.getDate().toString().padStart(2, '0');
        const month = (boliviaTime.getMonth() + 1).toString().padStart(2, '0');
        const year = boliviaTime.getFullYear();
        const hours = boliviaTime.getHours().toString().padStart(2, '0');
        const minutes = boliviaTime.getMinutes().toString().padStart(2, '0');
        
        const timeElement = document.getElementById('bolivia-time');
        if (timeElement) {
            timeElement.textContent = `${day}/${month}/${year} ${hours}:${minutes}`;
        }
    }

    // Update time on page load and every minute
    document.addEventListener('DOMContentLoaded', function() {
        updateBoliviaTime();
        setInterval(updateBoliviaTime, 60000);
    });
</script>
@endsection