@extends('layouts.app')

@section('title','Etapas – Oh! SanSi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/etapas.css') }}">
@endpush

@section('content')
<div class="etp-container">
  <header class="etp-hero">
    <h1>Olimpiadas Científicas UMSS</h1>
    <p>Etapas de la competición @if($competicion) {{ $competicion->anio }} @endif</p>
  </header>

  <section class="etp-list">
    @forelse($etapas as $etapa)
      <article class="etp-card">
        <div class="etp-icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" width="44" height="44"><path d="M3 6h18v2H3zM3 11h18v2H3zM3 16h18v2H3z"/></svg>
        </div>
        <div class="etp-info">
          <h3 class="etp-title">{{ $etapa->nombre }}</h3>
          <p class="etp-dates">
            {{ optional($etapa->fecha_inicio)->format('d/m/Y') }} – {{ optional($etapa->fecha_fin)->format('d/m/Y') }}
          </p>
          <span class="badge {{ $etapa->estado_badge }}">{{ $etapa->estado_label }}</span>
        </div>
      </article>
    @empty
      <p>No hay etapas configuradas para esta competición.</p>
    @endforelse
  </section>

  <footer class="etp-footer">
    <small>© {{ date('Y') }} FullCoders · Universidad Mayor de San Simón</small>
  </footer>
</div>
@endsection
