@extends('layouts.app')
@section('title', 'Reclamos · Admin')

@section('content')
<!-- Header -->
<div class="content-header">
    <h1 class="content-title">Reclamos</h1>

    <div class="" aria-hidden="true"></div>

    <a href="#" class="create-btn btn-pressable opacity-0 pointer-events-none">
        <svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true">
            <path fill="currentColor" d="M11 11V5h2v6h6v2h-6v6h-2v-6H5v-2z"/>
        </svg>
        <span>Crear</span>
    </a>
</div>

<!-- Buscador -->
<form id="searchForm" class="search-panel" action="{{ route('admin.reclamos.index') }}" method="GET">
    <div class="search-input-wrapper">
        <input id="searchInput" name="q" type="text" value="{{ $query ?? '' }}" placeholder="Buscar por estudiante, competencia o fase..." aria-label="Buscar reclamos"/>
        <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </div>
    <button class="search-btn btn-pressable" type="submit">Buscar</button>
</form>

<!-- Tabla de reclamos -->
<div class="table-card overflow-x-auto">
    <table class="w-full min-w-[700px] text-center align-middle">
        <thead class="grid-headers">
            <tr>
                <th class="cell header whitespace-nowrap">Estudiante</th>
                <th class="cell header whitespace-nowrap">Competencia</th>
                <th class="cell header whitespace-nowrap">Fase</th>
                <th class="cell header whitespace-nowrap">Mensaje</th>
                <th class="cell header whitespace-nowrap">Estado</th>
                <th class="cell header whitespace-nowrap">Fecha</th>
                <th class="cell header whitespace-nowrap">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reclamos as $i => $r)
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-[#d7dde4]' }} hover:bg-blue-50 transition-colors">
                <td class="py-3 px-2 text-gray-900 align-middle">{{ optional($r->user)->name ?? '—' }} {{ optional($r->user)->last_name_father ?? '' }}</td>
                <td class="py-3 px-2 text-gray-700 align-middle">{{ optional($r->inscription->competition)->name ?? '—' }}</td>
                <td class="py-3 px-2 text-gray-700 align-middle">{{ $r->fase ?? '—' }}</td>
                <td class="py-3 px-2 text-gray-700 align-middle">{{ Illuminate\Support\Str::limit($r->mensaje, 80) }}</td>
                <td class="py-3 px-2 text-gray-700 align-middle">{{ ucfirst($r->estado) }}</td>
                <td class="py-3 px-2 text-gray-700 align-middle">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                <td class="py-2 px-2 align-middle">
                    <div class="flex flex-wrap gap-2 justify-center">
                        <a href="{{ route('admin.reclamos.show', $r->id) }}" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Ver</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-8 text-center text-gray-500">No hay reclamos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="table-footer">
    {{ $reclamos->withQueryString()->links() }}
</div>

@endsection

@push('styles')
<!-- Reuse roles table styles for consistent appearance -->
<style>
:root{
    --primary-dark-blue:#091c47;
    --table-header-bg:rgba(58,70,81,.5);
    --table-row-alt-bg:#d7dde4;
    --table-bg:#eef0f3;
    --text-dark:#3a4651;
    --white:#fff;
    --gray-200:#e5e7eb;
    --gray-500:#9aa0a6;
}
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:24px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:'Roboto',sans-serif;font-weight:400;font-size:32px;color:var(--text-dark);margin:0}
.create-btn{grid-column:3;justify-self:end;display:flex;align-items:center;gap:10px;background:var(--primary-dark-blue);color:#fff;padding:10px 18px;border-radius:15px;font-family:'Ubuntu',sans-serif;font-size:16px}
.btn-pressable{transition:transform .05s ease,filter .15s ease;box-shadow:0 1px 0 rgba(0,0,0,.12)}
.btn-pressable:hover{filter:brightness(1.05)}
.btn-pressable:active{transform:translateY(1px) scale(.99);filter:brightness(.95)}
.search-panel{display:flex;gap:16px;margin-bottom:16px;justify-content:center;align-items:center;flex-wrap:wrap}
.search-input-wrapper{position:relative;width:360px;max-width:92vw}
.search-input-wrapper input{width:100%;height:40px;background:#c4c4c4;border:1px solid #0b0b0b;border-radius:10px;padding:0 40px 0 12px;font-size:13px;color:var(--text-dark);font-weight:600}
.search-input-wrapper input::placeholder{color:rgba(58,70,81,.5);font-weight:400}
.search-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:rgba(58,70,81,.5);stroke:currentColor;stroke-width:2;fill:none;pointer-events:none}
.search-btn{height:40px;padding:0 14px;border-radius:30px;background:var(--primary-dark-blue);color:#fff;font-family:'Roboto',sans-serif;font-weight:500;font-size:13px;letter-spacing:1.25px}
.table-card{width:100%;max-width:100vw;margin:0 auto 16px auto;background:var(--table-bg);border-radius:10px;overflow-x:auto;border:1px solid #cfd6df;padding:8px}
table{border-collapse:separate;border-spacing:0;width:100%;background:var(--table-bg);}
.grid-headers th {background: #949BA2 !important;color: #fff !important;height: 56px;font-size: 1.1rem}
td,th{vertical-align:middle;padding:8px}
tbody tr{transition:background 0.15s}
tbody tr:nth-child(even){background:var(--table-row-alt-bg)}
tbody tr:nth-child(odd){background:var(--white)}
.table-footer{width:100%;max-width:92vw;margin:6px auto 0;display:flex;justify-content:flex-end;color:#445063;font-size:13px}
.btn{height:36px;padding:0 14px;border-radius:10px;font-family:'Ubuntu',sans-serif}
.btn-primary{background:var(--primary-dark-blue);color:#fff}
.btn-secondary{background:#f1f3f4;color:#111;border:1px solid var(--gray-200)}
</style>
@endpush
