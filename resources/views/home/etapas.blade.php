@extends('layouts.guest')
@section('title','Etapas – Oh! SanSi')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/etapas.css') }}">
@endpush
@section('content')
<!-- Hero Section -->
    @include('home.partials.hero-section')
<section id="etapas">
  <div class="etapas-container">
    <div class="title-wrapper">
      <h2 class="etapas-title">ETAPAS</h2>
      <div class="title-decorator">
        <div class="line-thick"></div>
      </div>
    </div>
    
    <div class="phases-grid">
      <article class="phase-card">
        <img src="{{ asset('images/fases/image_20.png') }}" alt="Icono de fase de inscripción" class="phase-icon">
        <div class="phase-content">
          <h3 class="phase-title">Fase de Inscripción</h3>
          <p class="phase-date">De 12/09/2025 al 23/09/2025</p>
          <p class="phase-status phase-status--completed">CONCLUIDO</p>
        </div>
      </article>

      <article class="phase-card">
        <img src="{{ asset('images/fases/image_21.png') }}" alt="Icono de fase de evaluación" class="phase-icon">
        <div class="phase-content">
          <h3 class="phase-title">Fase de Evaluación</h3>
          <p class="phase-date">De 24/09/2025 al 15/10/2025</p>
          <p class="phase-status phase-status--active">EN PROCESO</p>
        </div>
      </article>

      <article class="phase-card">
        <img src="{{ asset('images/fases/image_22.png') }}" alt="Icono de fase de premiación" class="phase-icon">
        <div class="phase-content">
          <h3 class="phase-title">Fase de Premiación</h3>
          <p class="phase-date">De 20/10/2025 al 25/10/2025</p>
          <p class="phase-status phase-status--pending">PENDIENTE</p>
        </div>
      </article>
    </div>
  </div>
</section>
@endsection

<style>
  body {
  margin: 0;
  font-family: 'Poppins', sans-serif;
  background-color: #ffffff;
}

*,
*::before,
*::after {
  box-sizing: border-box;
}

#etapas {
  background-color: #ffffff;
  padding: 60px 20px;
  overflow: hidden;
}

.etapas-container {
  max-width: 1440px;
  margin: 0 auto;
}

.title-wrapper {
  text-align: center;
  margin-bottom: 98px;
}

.etapas-title {
  font-family: 'Poppins', sans-serif;
  font-weight: 700;
  font-size: 20px;
  line-height: 26px;
  color: #000000;
  margin: 0 0 11px 0;
  text-transform: uppercase;
  letter-spacing: 2px;
}

.title-decorator {
  position: relative;
  display: inline-block;
  width: 224px;
  height: 1px;
  background-color: #000000;
}

.title-decorator .line-thick {
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  width: 94px;
  height: 4px;
  background-color: #000000;
  box-shadow: 0px 4px 4px 0px rgba(0, 0, 0, 0.25);
}

.phases-grid {
  display: flex;
  justify-content: space-around;
  align-items: flex-start;
  flex-wrap: wrap;
  gap: 40px;
}

.phase-card {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  flex-basis: 331px;
  flex-grow: 0;
  transition: transform 0.3s ease;
}

.phase-card:hover {
  transform: translateY(-10px);
}

.phase-icon {
  width: 195px;
  height: 195px;
  object-fit: contain;
  margin-bottom: 10px;
}

.phase-content {
  font-family: 'Poppins', sans-serif;
  font-weight: 500;
  font-size: 20px;
  line-height: 26px;
  color: #000000;
}

.phase-title {
  margin: 0;
  font-weight: 600;
  margin-bottom: 8px;
}

.phase-date {
  margin: 0;
  font-size: 16px;
  color: #666666;
  margin-bottom: 26px;
}

.phase-status {
  margin: 0;
  font-weight: 700;
  font-size: 18px;
  padding: 8px 20px;
  border-radius: 20px;
  display: inline-block;
}

/* Estados de fase */
.phase-status--completed {
  background-color: #e8f5e9;
  color: #2e7d32;
}

.phase-status--active {
  background-color: #fff3e0;
  color: #e65100;
  animation: pulse 2s ease-in-out infinite;
}

.phase-status--pending {
  background-color: #f5f5f5;
  color: #757575;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
}

@media (max-width: 1200px) {
  .phases-grid {
    justify-content: center;
  }
}

@media (max-width: 768px) {
  #etapas {
    padding: 40px 20px;
  }
  
  .title-wrapper {
    margin-bottom: 60px;
  }
  
  .etapas-title {
    font-size: 18px;
  }
  
  .phase-card {
    flex-basis: 100%;
    max-width: 400px;
  }
  
  .phase-icon {
    width: 150px;
    height: 150px;
  }
  
  .phase-content {
    font-size: 18px;
  }
  
  .phase-date {
    font-size: 14px;
  }
  
  .phase-status {
    font-size: 16px;
  }
}

@media (max-width: 480px) {
  .etapas-title {
    font-size: 16px;
  }
  
  .title-decorator {
    width: 180px;
  }
  
  .title-decorator .line-thick {
    width: 70px;
  }
  
  .phase-icon {
    width: 120px;
    height: 120px;
  }
  
  .phase-title {
    font-size: 16px;
  }
  
  .phase-date {
    font-size: 13px;
  }
  
  .phase-status {
    font-size: 14px;
    padding: 6px 16px;
  }
}
</style>