@extends('layouts.guest')

@section('title', 'Documentos – Oh! SanSi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/documentos.css') }}">
@endpush

@section('content')
    <!-- Hero Section -->
    @include('home.partials.hero-section')

    <section class="section-container">
        <div class="documentos-header">
            <h2 class="documentos-title">CONVOCATORIAS</h2>
            <p class="documentos-subtitle">Información sobre competencias activas, áreas, categorías y fechas</p>
        </div>

        @if($competencias->isEmpty())
            <div class="no-convocatorias">
                <i class="fas fa-info-circle"></i>
                <p>No hay convocatorias activas en este momento.</p>
            </div>
        @else
            <div class="convocatoria-carousel">
                <button class="carousel-nav prev" aria-label="Anterior">&larr;</button>
                <div class="carousel-track">
                    @foreach($competencias as $competencia)
                        <article class="competencia-card carousel-slide" data-id="{{ $competencia->id }}">
                            <div class="competencia-header carousel-header">
                                <h3 class="competencia-nombre">{{ $competencia->name }}</h3>
                                <span class="competencia-badge badge-activa">ACTIVA</span>
                            </div>

                            <div class="competencia-description">
                                <p>{{ $competencia->description }}</p>
                            </div>

                            <div class="competencia-fechas">
                                <div class="fecha-item">
                                    <i class="fas fa-calendar-alt"></i>
                                    <div>
                                        <strong>Competencia:</strong>
                                        <span>{{ \Carbon\Carbon::parse($competencia->fechaInicio)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($competencia->fechaFin)->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            @if($competencia->phases->isNotEmpty())
                                <div class="fases-section">
                                    <h4 class="section-title">
                                        <i class="fas fa-tasks"></i> Fases y Etapas
                                    </h4>
                                    <div class="fases-grid">
                                        @foreach($competencia->phases as $phase)
                                            @php
                                                $now = now();
                                                $startDate = $phase->pivot->start_date ? \Carbon\Carbon::parse($phase->pivot->start_date) : null;
                                                $endDate = $phase->pivot->end_date ? \Carbon\Carbon::parse($phase->pivot->end_date) : null;
                                                $estadoLabel = 'Próximamente';
                                                $badgeClass = 'badge-pending';
                                                if ($startDate && $endDate) {
                                                    if ($now->lt($startDate)) {
                                                        $estadoLabel = 'Próximamente';
                                                        $badgeClass = 'badge-pending';
                                                    } elseif ($now->between($startDate, $endDate)) {
                                                        $estadoLabel = 'En Curso';
                                                        $badgeClass = 'badge-active';
                                                    } else {
                                                        $estadoLabel = 'Finalizada';
                                                        $badgeClass = 'badge-completed';
                                                    }
                                                }
                                            @endphp

                                            <div class="fase-item">
                                                <div class="fase-header">
                                                    <span class="fase-nombre">{{ $phase->name }}</span>
                                                    <span class="fase-badge {{ $badgeClass }}">{{ $estadoLabel }}</span>
                                                </div>
                                                @if($startDate && $endDate)
                                                    <div class="fase-fechas">
                                                        <i class="fas fa-clock"></i>
                                                        <span>{{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span>
                                                    </div>
                                                @endif
                                                @if($phase->pivot->classification_type)
                                                    <div class="fase-clasificacion">
                                                        <i class="fas fa-trophy"></i>
                                                        @if($phase->pivot->classification_type === 'cupo')
                                                            <span>Por Cupo: {{ $phase->pivot->classification_cupo ?? 'N/A' }} clasificados</span>
                                                        @elseif($phase->pivot->classification_type === 'nota')
                                                            <span>Por Nota Mínima: {{ $phase->pivot->classification_nota_minima ?? 'N/A' }}</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($competencia->categoryAreas->isNotEmpty())
                                <div class="areas-section">
                                    <h4 class="section-title">
                                        <i class="fas fa-list-alt"></i> Áreas y Categorías Habilitadas
                                    </h4>
                                    @php
                                        $grouped = $competencia->categoryAreas->groupBy(function($item) {
                                            return $item->categoria->nombre ?? 'Sin categoría';
                                        });
                                    @endphp

                                    <div class="categorias-list">
                                        @foreach($grouped as $categoriaNombre => $items)
                                            <div class="categoria-group">
                                                <h5 class="categoria-nombre">
                                                    <i class="fas fa-tag"></i> {{ $categoriaNombre }}
                                                </h5>
                                                <ul class="areas-list">
                                                    @foreach($items as $item)
                                                        <li class="area-item">
                                                            <i class="fas fa-check-circle"></i>
                                                            <span>{{ $item->area->name ?? 'N/A' }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div class="competencia-footer">
                                <a href="{{ route('documentos.download', $competencia->id) }}" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Descargar Convocatoria
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <button class="carousel-nav next" aria-label="Siguiente">&rarr;</button>
                <div class="carousel-dots" aria-hidden="true">
                    @foreach($competencias as $i => $c)
                        <button class="dot {{ $i===0 ? 'active' : '' }}" data-index="{{ $i }}"></button>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    const carousel = document.querySelector('.convocatoria-carousel');
    const slides = document.querySelectorAll('.carousel-slide');
    const prev = document.querySelector('.carousel-nav.prev');
    const next = document.querySelector('.carousel-nav.next');
    const dots = document.querySelectorAll('.carousel-dots .dot');
    let index = 0;
    const autoplayDelay = 6000; // 6s
    let autoplayTimer = null;

    function show(i){
        if(!slides.length) return;
        const newIndex = ((i % slides.length) + slides.length) % slides.length;
        slides.forEach((s, idx)=> s.classList.toggle('active', idx===newIndex));
        dots.forEach((d, idx)=> d.classList.toggle('active', idx===newIndex));
        index = newIndex;
    }

    function goNext(){ show(index + 1); }
    function goPrev(){ show(index - 1); }

    function startAutoplay(){
        stopAutoplay();
        autoplayTimer = setInterval(()=> { show(index + 1); }, autoplayDelay);
    }

    function stopAutoplay(){
        if(autoplayTimer){ clearInterval(autoplayTimer); autoplayTimer = null; }
    }

    prev?.addEventListener('click', ()=>{ goPrev(); startAutoplay(); });
    next?.addEventListener('click', ()=>{ goNext(); startAutoplay(); });
    dots.forEach(d => d.addEventListener('click', (e)=>{ show(Number(e.target.dataset.index)); startAutoplay(); }));

    // Pause autoplay on hover, resume on leave
    if(carousel){
        carousel.addEventListener('mouseenter', stopAutoplay);
        carousel.addEventListener('mouseleave', startAutoplay);
    }

    // Inicializar
    if(slides.length){ show(0); startAutoplay(); }
});
</script>
@endpush
