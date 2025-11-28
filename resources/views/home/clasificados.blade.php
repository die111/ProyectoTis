@extends('layouts.guest')

@section('content')
    <!-- Hero Section -->
    @include('home.partials.hero-section')

    <section class="section-container olympiads-section">
        <h2 class="olympiads-title" style="font-size:2rem; font-weight:bold; text-align:center; margin-bottom:1.2rem;">OLIMPIADAS CIENTÍFICAS UMSS</h2>
    </section>

    <section class="section-container">
        <div class="subjects-title-wrapper">
            <h2 class="subjects-title" style="font-size:1.1rem; font-weight:bold; text-align:center;">CLASIFICADOS</h2>
        </div>
        <div style="display:flex; justify-content:center; align-items:center; margin-top:1rem; gap:1rem;">
            <form method="GET" id="competenciaForm" style="display:flex; gap:1rem; align-items:center;">
                <select name="competencia_id" id="combo-competencia" style="min-width:220px; padding:.55rem .7rem; border-radius:.6rem; border:1px solid #d0d5dd;" onchange="document.getElementById('competenciaForm').submit();">
                    <option value="">Seleccione una competencia</option>
                    @foreach($competiciones as $competencia)
                        <option value="{{ $competencia->id }}" {{ $selectedId == $competencia->id ? 'selected' : '' }}>{{ $competencia->name }}</option>
                    @endforeach
                </select>
                <span id="competencia-id" style="font-weight:bold;">@if($selectedId)ID: {{ $selectedId }}@endif</span>
            </form>
        </div>
        @if($phases && count($phases))
            <div style="display:flex; justify-content:center; gap:1rem; margin-top:1.5rem; flex-wrap:wrap;">
                @foreach($phases as $phase)
                    <button type="button" style="background:#0C3E92; color:#fff; border:none; border-radius:.3rem; cursor:pointer; padding:.5rem 1.2rem; font-weight:600;">
                        Fase {{ $phase }}
                    </button>
                @endforeach
            </div>
        @endif
        @if($categoriasAreas && count($categoriasAreas))
            @php
                $grouped = $categoriasAreas->groupBy(function($item) {
                    return $item->categoria->nombre ?? 'Sin categoría';
                });
                $chunks = $grouped->chunk(ceil($grouped->count() / 3));
            @endphp
            <div style="margin-top:2rem; display:flex; justify-content:center; gap:2rem; flex-wrap:wrap;">
                @foreach($chunks as $chunk)
                    <div style="background:#f8fafc; border:1px solid #d0d5dd; border-radius:.8rem; min-width:250px; padding:1rem; box-shadow:0 2px 8px #0001; flex:1;">
                        @foreach($chunk as $categoria => $items)
                            <h3 style="font-size:1.1rem; font-weight:bold; margin-bottom:.7rem; text-align:center;">{{ $categoria }}</h3>
                            <ul style="list-style:none; padding:0; margin:0;">
                                @foreach($items as $ca)
                                    <li style="padding:.4rem 0; border-bottom:1px solid #eef0f3; color:#333; text-align:center; display:flex; align-items:center; justify-content:center; gap:.5rem;">
                                        Área: <span style="font-weight:600;">{{ $ca->area->name ?? '' }}</span>
                                        <button title="Imprimir área" style="background:#0C3E92; color:#fff; border:none; border-radius:.3rem; cursor:pointer; padding:.3rem .8rem; margin-left:.3rem;">
                                            Imprimir
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </section>
@endsection
