@extends('layouts.guest')

@section('title', 'Etapas – Oh! SanSi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/etapas.css') }}">
<style>
    body {
        background-color: #ffffff;
    }
    .etp-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    .olimpiadas-title {
        text-align: center;
        margin-bottom: 1rem;
    }
    .olimpiadas-desc {
        text-align: justify;
        max-width: 900px;
        margin: 0 auto 2rem;
        line-height: 1.6;
    }
    .etapas-title {
        text-align: center;
        margin: 3rem 0;
        font-weight: bold;
    }
    .etapas-grid {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin: 2rem 0;
    }
    .etapa-card {
        text-align: center;
        flex: 1;
        max-width: 300px;
    }
    .etapa-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
    }
    .etapa-icon svg {
        width: 100%;
        height: 100%;
    }
    .etapa-title {
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    .etapa-dates {
        color: #666;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }
    .etapa-status {
        display: inline-block;
        padding: 0.25rem 1rem;
        border-radius: 4px;
        font-weight: bold;
    }
    .status-concluido { background: #e8f5e9; color: #2e7d32; }
    .status-proceso { background: #e3f2fd; color: #1565c0; }
    .status-pendiente { background: #fff3e0; color: #f57c00; }
    .footer-container {
        background: #002147;
        color: white;
        padding: 1rem;
        text-align: center;
        position: fixed;
        bottom: 0;
        width: 100%;
    }
</style>
@endpush

@section('content')
<x-nav-header />

<div class="etp-container">
    <h1 class="olimpiadas-title">OLIMPIADAS CIENTÍFICAS UMSS</h1>
    <p class="olimpiadas-desc">
        El Paseo Autonómico de la Universidad Mayor de San Simón es una de los espacios más relevantes del campus: el reflejo de palmeras y áreas verdes que reflejan la vida universitaria y la tradición académica de la UMSS.
    </p>

    <h2 class="etapas-title">ETAPAS</h2>
    
    <div class="etapas-grid">
        <div class="etapa-card">
            <div class="etapa-icon">
                <svg width="50" height="50" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="11" fill="#8dd3c7"/>
                    <path fill="#fff" d="M6 12h12v2H6zm0-3h12v2H6zm0-3h12v2H6z"/>
                </svg>
            </div>
            <h3 class="etapa-title">Fase de Inscripción</h3>
            <p class="etapa-dates">De 12/09/2025 al 21/09/2025</p>
            <span class="etapa-status status-concluido">CONCLUIDO</span>
        </div>

        <div class="etapa-card">
            <div class="etapa-icon">
                <svg width="50" height="50" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="11" fill="#fc8d62"/>
                    <path fill="#fff" d="M7 14h10v2H7zm0-4h10v2H7zm0-4h6v2H7z"/>
                </svg>
            </div>
            <h3 class="etapa-title">Fase de Evaluación</h3>
            <p class="etapa-dates">De 12/09/2025 al 21/09/2025</p>
            <span class="etapa-status status-proceso">EN PROCESO</span>
        </div>

        <div class="etapa-card">
            <div class="etapa-icon">
                <svg width="50" height="50" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="11" fill="#66c2a5"/>
                    <path fill="#fff" d="M9 11V4h6v7h4l-7 7-7-7h4z"/>
                </svg>
            </div>
            <h3 class="etapa-title">Fase de Premiación</h3>
            <p class="etapa-dates">De 12/09/2025 al 21/09/2025</p>
            <span class="etapa-status status-pendiente">PENDIENTE</span>
        </div>
    </div>
</div>

<footer class="footer-container">
    <h2>UNIVERSIDAD MAYOR DE SAN SIMÓN</h2>
    <p>Dirección: Av. Oquendo y Jordan, Cochabamba - Bolivia</p>
    <p>Teléfono: (591)</p>
    <p>Copyright © 2025 FullCoders - Todos los derechos reservados</p>
</footer>
@endsection