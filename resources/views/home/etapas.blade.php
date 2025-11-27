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
      <!-- Combo box de competiciones -->
      <div class="competicion-select-wrapper" style="margin-top: 32px; text-align: center;">
        <label for="competicion-select" style="font-weight: 500; margin-right: 8px;">Competición:</label>
        <select id="competicion-select" name="competicion" style="padding: 8px 16px; border-radius: 8px; border: 1px solid #ccc; font-family: 'Poppins', sans-serif;">
          <option value="" selected disabled>Seleccione una competición</option>
          @foreach($competiciones as $competicion)
            <option value="{{ $competicion->id }}" 
              data-inicio="{{ $competicion->inscripcion_inicio }}" 
              data-fin="{{ $competicion->inscripcion_fin }}"
              data-eval-inicio="{{ $competicion->evaluacion_inicio }}"
              data-eval-fin="{{ $competicion->evaluacion_fin }}"
              data-prem-inicio="{{ $competicion->premiacion_inicio }}"
              data-prem-fin="{{ $competicion->premiacion_fin }}"
            >{{ $competicion->name }}</option>
          @endforeach
        </select>
      </div>
    </div>
    
    <div class="phases-grid">
      <article class="phase-card" id="inscripcion-card" style="display: none;">
        <img src="{{ asset('images/fases/image_20.png') }}" alt="Icono de fase de inscripción" class="phase-icon">
        <div class="phase-content">
          <h3 class="phase-title">Fase de Inscripción</h3>
          <p class="phase-date" id="inscripcion-date"></p>
          <p class="phase-status phase-status--completed" id="inscripcion-status"></p>
        </div>
      </article>

      <article class="phase-card" id="evaluacion-card" style="display: none;">
        <img src="{{ asset('images/fases/image_21.png') }}" alt="Icono de fase de evaluación" class="phase-icon">
        <div class="phase-content">
          <h3 class="phase-title">Fase de Evaluación</h3>
          <p class="phase-date" id="evaluacion-date"></p>
          <p class="phase-status phase-status--active" id="evaluacion-status"></p>
        </div>
      </article>

      <article class="phase-card" id="premiacion-card" style="display: none;">
        <img src="{{ asset('images/fases/image_22.png') }}" alt="Icono de fase de premiación" class="phase-icon">
        <div class="phase-content">
          <h3 class="phase-title">Fase de Premiación</h3>
          <p class="phase-date" id="premiacion-date"></p>
          <p class="phase-status phase-status--pending" id="premiacion-status"></p>
        </div>
      </article>
    </div>
  </div>
</section>
@endsection

{{-- Se movió el CSS a public/css/etapas.css --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const select = document.getElementById('competicion-select');
  const inscDate = document.getElementById('inscripcion-date');
  const evalDate = document.getElementById('evaluacion-date');
  const premDate = document.getElementById('premiacion-date');
  const inscCard = document.getElementById('inscripcion-card');
  const evalCard = document.getElementById('evaluacion-card');
  const premCard = document.getElementById('premiacion-card');
  const inscStatus = document.getElementById('inscripcion-status');
  const evalStatus = document.getElementById('evaluacion-status');
  const premStatus = document.getElementById('premiacion-status');

  function ocultarFases() {
    inscCard.style.display = 'none';
    evalCard.style.display = 'none';
    premCard.style.display = 'none';
    inscDate.textContent = '';
    evalDate.textContent = '';
    premDate.textContent = '';
    inscStatus.textContent = '';
    evalStatus.textContent = '';
    premStatus.textContent = '';
    inscStatus.className = 'phase-status phase-status--completed';
    evalStatus.className = 'phase-status phase-status--active';
    premStatus.className = 'phase-status phase-status--pending';
  }

  function getStatus(inicio, fin, el) {
    if (!inicio || !fin) {
      el.className = el.className.replace(/phase-status--\w+/g, '');
      return '';
    }
    const hoy = new Date();
    const ini = new Date(inicio);
    const fini = new Date(fin);
    if (hoy < ini) {
      el.className = 'phase-status phase-status--pending';
      return 'PENDIENTE';
    }
    if (hoy >= ini && hoy <= fini) {
      el.className = 'phase-status phase-status--active';
      return 'EN PROCESO';
    }
    if (hoy > fini) {
      el.className = 'phase-status phase-status--completed';
      return 'CONCLUIDO';
    }
    el.className = 'phase-status';
    return '';
  }

  ocultarFases();

  select.addEventListener('change', function() {
    if (!select.value) {
      ocultarFases();
      return;
    }
    const selected = select.options[select.selectedIndex];
    function soloFecha(fecha) {
      if (!fecha || fecha === '...') return '...';
      return fecha.split(' ')[0];
    }
    const inicio = soloFecha(selected.getAttribute('data-inicio'));
    const fin = soloFecha(selected.getAttribute('data-fin'));
    const evalInicio = soloFecha(selected.getAttribute('data-eval-inicio'));
    const evalFin = soloFecha(selected.getAttribute('data-eval-fin'));
    const premInicio = soloFecha(selected.getAttribute('data-prem-inicio'));
    const premFin = soloFecha(selected.getAttribute('data-prem-fin'));
    inscDate.textContent = `De ${inicio} al ${fin}`;
    evalDate.textContent = `De ${evalInicio} al ${evalFin}`;
    premDate.textContent = `De ${premInicio} al ${premFin}`;
    inscStatus.textContent = getStatus(selected.getAttribute('data-inicio'), selected.getAttribute('data-fin'), inscStatus);
    evalStatus.textContent = getStatus(selected.getAttribute('data-eval-inicio'), selected.getAttribute('data-eval-fin'), evalStatus);
    premStatus.textContent = getStatus(selected.getAttribute('data-prem-inicio'), selected.getAttribute('data-prem-fin'), premStatus);
    inscCard.style.display = '';
    evalCard.style.display = '';
    premCard.style.display = '';
  });
});
</script>
@endpush