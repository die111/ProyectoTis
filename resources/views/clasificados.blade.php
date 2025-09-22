
@extends('layouts.app1')

@section('content')

    <section class="section-container">
        <div class="hero-content">
            <div class="hero-image-container">
                <img src="{{ asset('images/image2.jpg') }}"
                     alt="Paseo Autonómico de la Universidad Mayor de San Simón">
            </div>
            <div class="hero-text-container">
                <p>
                    El Paseo Autonómico de la Universidad Mayor de San Simón es uno de los espacios más
                    emblemáticos del campus, rodeado de palmeras y áreas verdes que reflejan la vida universitaria
                    y la tradición académica de la UMSS.
                </p>
            </div>
        </div>
    </section>

    <section class="section-container olympiads-section">
        <h2 class="olympiads-title">OLIMPIADAS CIENTÍFICAS UMSS</h2>
    </section>
    
    <section class="section-container">
        <div class="subjects-title-wrapper">
            <h2 class="subjects-title">CLASIFICADOS</h2>
        </div>

        {{-- AQUÍ VA TU MÓDULO (tabs de fases, filtros, tabla, etc.) --}}
        <div class="container my-4">
                {{-- ========== WIDGET CLASIFICADOS (SIN DATOS) ========== --}}
        <section class="clasificados-widget" aria-labelledby="titulo-clasificados">
                {{-- Tabs de fases --}}
            <div class="cw-tabs" role="tablist" aria-label="Fases">
                <button class="cw-tab is-active" data-tab="inicial" role="tab" aria-selected="true">Fase Inicial</button>
                <button class="cw-tab" data-tab="fase1" role="tab" aria-selected="false">Fase 1</button>
                <button class="cw-tab" data-tab="fase2" role="tab" aria-selected="false">Fase 2</button>
                <button class="cw-tab" data-tab="fase3" role="tab" aria-selected="false">Fase 3</button>
            </div>

                {{-- Filtros --}}
            <div class="cw-filters">
                <label for="cw-area" class="cw-label">Área</label>
                <select id="cw-area" class="cw-select" aria-label="Filtrar por área">
                    <option value="fisica">Física</option>
                    <option value="quimica">Química</option>
                    <option value="biologia">Biología</option>
                    <option value="matematicas">Matemáticas</option>
                    <option value="geografia">Geografía</option>
                {{-- Cuando definan las 8 áreas, aquí agregan las faltantes --}}
                </select>
            </div>

                {{-- Tabla (sin datos) --}}
            <div class="cw-table-wrap">
            <table class="cw-table" aria-describedby="cw-table-desc">
                <caption id="cw-table-desc" class="cw-sr-only">
                Tabla de clasificados (sin datos por el momento).
                </caption>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NOMBRE</th>
                    <th>APELLIDO PATERNO</th>
                    <th>APELLIDO MATERNO</th>
                    <th>UNIDAD EDUCATIVA</th>
                    <th>GRADO</th>
                    <th>NOTA</th>
                </tr>
            </thead>
            <tbody id="cw-tbody">
                <tr class="cw-empty">
                    <td colspan="7">No hay clasificados aún para esta fase/área.</td>
                </tr>
            </tbody>
            </table>
            </div>

            {{-- Paginación (deshabilitada porque no hay datos) --}}
            <nav class="cw-pagination" aria-label="Paginación">
                <button class="cw-page" disabled aria-disabled="true">‹</button>
                <button class="cw-page is-active" disabled aria-disabled="true">1</button>
                <button class="cw-page" disabled aria-disabled="true">2</button>
                <button class="cw-page" disabled aria-disabled="true">3</button>
                <button class="cw-page" disabled aria-disabled="true">›</button>
            </nav>
        </section>

            {{-- Estilos encapsulados --}}
        <style>
            .clasificados-widget{margin-top:1rem}
            .cw-sr-only{position:absolute;left:-9999px}
            .cw-tabs{display:flex;gap:3rem;justify-content:center;margin:0 0 .75rem}
            .cw-tab{background:none;border:0;border-bottom:3px solid #cfd4da;padding:.5rem 0;cursor:pointer;font-weight:700;color:#111}
            .cw-tab.is-active{border-bottom-color:#0C3E92;color:#0C3E92}
            .cw-filters{display:flex;justify-content:center;gap:.6rem;margin:0 0 .75rem;flex-wrap:wrap}
            .cw-label{align-self:center;font-weight:600}
            .cw-select{min-width:220px;border:1px solid #d0d5dd;border-radius:.6rem;padding:.55rem .7rem;background:#fff}
            .cw-table-wrap{overflow:auto;border:1px solid #d0d5dd;border-radius:.6rem}
            .cw-table{width:100%;border-collapse:collapse;font-size:.95rem}
            .cw-table thead th{background:#aab4bf;color:#fff;text-align:left;padding:.65rem .8rem;letter-spacing:.02em}
            .cw-table tbody td{padding:.6rem .8rem;border-bottom:1px solid #eef0f3;color:#444}
            .cw-table tbody tr:nth-child(odd){background:#f4f6f8}
            .cw-empty td{text-align:center;color:#6b7280;background:#f8fafc}
            .cw-pagination{display:flex;gap:.35rem;justify-content:center;padding:.75rem}
            .cw-page{border:1px solid #d0d5dd;background:#fff;border-radius:.5rem;padding:.35rem .6rem;cursor:not-allowed}
            .cw-page.is-active{background:#111;color:#fff;border-color:#111}
            @media (max-width:700px){
                .cw-tabs{gap:1rem;flex-wrap:wrap}
                .cw-table thead th,.cw-table tbody td{white-space:nowrap}
            }
        </style>

        {{-- JS mínimo para resaltar tab activo (sin datos) --}}
        <script>
            (function(){
            const tabs = document.querySelectorAll('.clasificados-widget .cw-tab');
            tabs.forEach(t=>{
                t.addEventListener('click', ()=>{
            tabs.forEach(x=>x.classList.remove('is-active'));
                t.classList.add('is-active');
                // Aquí, cuando tengan backend, disparan un fetch() según la fase/área.
                // Por ahora mantenemos el estado "sin datos".
                });
            });
            document.getElementById('cw-area').addEventListener('change', ()=>{
                // Cuando haya datos, aquí filtran. Por ahora no hace nada.
            });
        })();
        </script>
      
        </div>

    </section>
    
<style>
  /* Reduce espacio entre secciones seguidas */
  .section-container + .section-container {
    margin-top: .5rem !important;
    padding-top: 0 !important;
  }

  /* Ajusta los títulos dentro de esas secciones */
  .section-container h2 {
    margin: .25rem 0 .5rem !important;
  }
</style>


@endsection
