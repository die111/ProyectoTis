@extends('layouts.app')
@section('title', 'Bitácora · Admin')

@section('content')
<!--  CABECERA / ACCIONES   -->
<div class="content-header mb-4">
    <h1 class="content-title">Bitácora</h1>
    <div></div>
</div>

<!--  BUSCADOR / FILTROS   -->
<form id="searchForm" class="search-panel" action="{{ route('admin.audits.index') }}" method="GET">
    <div class="search-input-wrapper">
        <input id="searchInput" name="q" type="text" value="{{ request('q') ?? '' }}" placeholder="Buscar usuario, ruta o campo..." aria-label="Buscar audit"/>
        <svg class="search-icon" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="11" cy="11" r="7"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
    </div>
    <div class="flex items-center gap-2">
        <select name="model" class="form-select" style="height:40px;border-radius:10px;padding:6px 10px;">
            <option value="">Todos los modelos</option>
            @if(!empty($models) && is_array($models))
                @foreach($models as $class => $label)
                    <option value="{{ $class }}" {{ request('model') == $class ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            @else
                <option value="App\\Models\\Inscription" {{ request('model') == 'App\\Models\\Inscription' ? 'selected' : '' }}>Inscripción</option>
                <option value="App\\Models\\Evaluation" {{ request('model') == 'App\\Models\\Evaluation' ? 'selected' : '' }}>Evaluación</option>
                <option value="App\\Models\\User" {{ request('model') == 'App\\Models\\User' ? 'selected' : '' }}>Usuario</option>
            @endif
        </select>
        <select name="action" class="form-select" style="height:40px;border-radius:10px;padding:6px 10px;">
            <option value="">Todas las acciones</option>
            <option value="created" {{ request('action')=='created' ? 'selected' : '' }}>Created</option>
            <option value="updated" {{ request('action')=='updated' ? 'selected' : '' }}>Updated</option>
            <option value="deleted" {{ request('action')=='deleted' ? 'selected' : '' }}>Deleted</option>
        </select>
        <button class="search-btn btn-pressable" type="submit">Filtrar</button>
    </div>
</form>

<!--  TABLA DE AUDITS  -->
<div class="table-card overflow-x-auto">
    <table class="w-full min-w-[700px] text-center align-middle">
        <thead class="grid-headers">
            <tr>
                <th class="cell header whitespace-nowrap">Fecha</th>
                <th class="cell header whitespace-nowrap">Usuario</th>
                <th class="cell header">Acción</th>
                <th class="cell header">Modelo</th>
                <th class="cell header">Cambios</th>
                <th class="cell header">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($audits as $i => $audit)
            <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-[#d7dde4]' }} hover:bg-blue-50 transition-colors">
                <td class="py-3 px-2 text-gray-900 align-middle">{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="py-3 px-2 text-gray-900 align-middle">
                    <div><strong>{{ optional($audit->user)->name ?? 'Sistema' }}</strong></div>
                    <div class="text-gray-500 small">{{ optional($audit->user)->email ?? '' }}</div>
                </td>
                <td class="py-3 px-2 align-middle"><span class="badge bg-info text-dark">{{ strtoupper($audit->action) }}</span></td>
                <td class="py-3 px-2 align-middle">
                    @php $modelName = class_basename($audit->auditable_type); @endphp
                    <div>{{ $modelName }} <small class="text-muted">(#{{ $audit->auditable_id }})</small></div>
                    <div class="text-muted small">{{ $audit->meta['route'] ?? '' }}</div>
                </td>
                <td class="py-3 px-2 align-middle text-left">
                    @php
                        $changed = [];
                        if (is_array($audit->new_values)) {
                            // Omitir claves triviales para mostrar sólo campos relevantes
                            $skip = ['id','created_at','updated_at'];
                            $keys = array_keys($audit->new_values);
                            $changed = array_values(array_diff($keys, $skip));
                        }
                    @endphp
                    @if(count($changed))
                        <small class="text-muted">Campos cambiados:</small>
                        <div class="mt-1">
                            @foreach(array_slice($changed,0,5) as $c)
                                <span class="badge bg-secondary me-1">{{ $c }}</span>
                            @endforeach
                            @if(count($changed) > 5)
                                <span class="text-muted">+{{ count($changed) - 5 }} más</span>
                            @endif
                        </div>
                    @else
                        <small class="text-muted">Sin cambios detectables</small>
                    @endif
                </td>
                <td class="py-2 px-2 align-middle">
                    <div class="flex flex-wrap gap-2 justify-center">
                        <a href="{{ route('admin.audits.show', $audit->id) }}" class="btn btn-primary btn-pressable px-3 py-1 text-sm">Ver</a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="table-footer mt-3">{{ $audits->withQueryString()->links() }}</div>
@endsection

@push('styles')
<style>
/* Reuse the roles index table styles to match look & feel */
:root{
    --primary-dark-blue:#091c47;
    --table-header-bg:rgba(58,70,81,.5);
    --table-row-alt-bg:#d7dde4;
    --table-bg:#eef0f3;
    --text-dark:#3a4651;
    --white:#fff;
}
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:24px;max-width:980px;margin-left:auto;margin-right:auto}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:'Roboto',sans-serif;font-weight:400;font-size:28px;color:var(--text-dark);margin:0}
.search-panel{display:flex;gap:12px;margin-bottom:16px;justify-content:flex-start;align-items:center;flex-wrap:wrap;max-width:980px;margin-left:auto;margin-right:auto}
.search-input-wrapper{position:relative;width:360px;max-width:92vw}
.search-input-wrapper input{width:100%;height:40px;background:#c4c4c4;border:1px solid #0b0b0b;border-radius:10px;padding:0 40px 0 12px;font-size:13px;color:var(--text-dark);font-weight:600}
.search-input-wrapper input::placeholder{color:rgba(58,70,81,.5);font-weight:400}
.search-icon{position:absolute;right:12px;top:50%;transform:translateY(-50%);width:20px;height:20px;color:rgba(58,70,81,.5);stroke:currentColor;stroke-width:2;fill:none;pointer-events:none}
.search-btn{height:40px;padding:0 14px;border-radius:30px;background:var(--primary-dark-blue);color:#fff;font-family:'Roboto',sans-serif;font-weight:500;font-size:13px}
.table-card{width:100%;max-width:100vw;margin:0 auto 16px auto;background:var(--table-bg);border-radius:10px;overflow-x:auto;border:1px solid #cfd6df}
table{border-collapse:separate;border-spacing:0;width:100%;background:var(--table-bg);} 
.grid-headers th {background: #949BA2 !important;color: #fff !important;height: 56px;font-size: 1.05rem}
td,th{vertical-align:middle;}
tbody tr{transition:background 0.15s;}tbody tr:nth-child(even){background:var(--table-row-alt-bg);}tbody tr:nth-child(odd){background:var(--white);} 
.table-footer{width:100%;max-width:92vw;margin:6px auto 0;display:flex;justify-content:flex-end;color:#445063;font-size:13px}
.btn-primary{background:var(--primary-dark-blue);color:#fff;border-radius:8px;padding:6px 10px}
.btn-pressable{transition:transform .05s ease,filter .15s ease}
.badge{display:inline-block;padding:.25em .6em;font-size:.75em;border-radius:.25rem}
.bg-info{background:#d1ecf1}
.bg-secondary{background:#e5e7eb}
</style>
@endpush
