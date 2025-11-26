@extends('layouts.app')
@section('title', 'Bitácora · Registro')

@section('content')
<!-- HEADER -->
<div class="content-header mb-4">
    <h1 class="content-title">Registro de auditoría</h1>
    <div></div>
</div>

<div class="table-card p-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="col-span-1 bg-white p-4 rounded">
            <h3 class="text-lg font-semibold mb-2">Detalle</h3>
            <p><strong>Registro:</strong> #{{ $audit->id }}</p>
            <p><strong>Fecha:</strong><br>{{ $audit->created_at->format('Y-m-d H:i:s') }}</p>
            <p><strong>Usuario:</strong><br>{{ optional($audit->user)->name ?? 'Sistema' }}<br><small class="text-muted">{{ optional($audit->user)->email ?? '' }}</small></p>
            <p><strong>Acción:</strong><br><span class="badge bg-info text-dark">{{ strtoupper($audit->action) }}</span></p>
            <p><strong>Modelo:</strong><br>{{ class_basename($audit->auditable_type) }} <small class="text-muted">(#{{ $audit->auditable_id }})</small></p>
            <p class="text-muted small">Nota: La bitácora puede contener registros de cualquier modelo del sistema.</p>
            <hr class="my-3">
            <h4 class="text-sm font-medium">Meta</h4>
            @if($audit->meta)
                <ul class="list-unstyled small text-muted">
                    <li><strong>IP:</strong> {{ $audit->meta['ip'] ?? '' }}</li>
                    <li><strong>Route:</strong> {{ $audit->meta['route'] ?? '' }}</li>
                    <li><strong>URL:</strong> {{ $audit->meta['url'] ?? '' }}</li>
                </ul>
            @else
                <p class="text-muted small">-</p>
            @endif
            <div class="mt-4">
                <a href="{{ route('admin.audits.index') }}" class="btn btn-secondary btn-pressable">Volver a Bitácora</a>
            </div>
        </div>

        <div class="col-span-2">
            @php
                $formatAuditValue = function($v) {
                    if (is_null($v)) return '-';
                    if (is_bool($v)) return $v ? 'true' : 'false';
                    if (is_array($v) || is_object($v)) return json_encode($v, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
                    return (string) $v;
                };
            @endphp

            @foreach($history as $h)
                <div class="bg-white p-4 rounded mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <strong>{{ $h->created_at->format('Y-m-d H:i:s') }}</strong>
                            &nbsp;—&nbsp; <span class="badge bg-info text-dark">{{ strtoupper($h->action) }}</span>
                        </div>
                        <div class="text-muted small">{{ optional($h->user)->name ?? 'Sistema' }}</div>
                    </div>

                    @php
                        $old = (array) ($h->old_values ?? []);
                        $new = (array) ($h->new_values ?? []);
                        $fields = array_values(array_unique(array_merge(array_keys($old), array_keys($new))));
                    @endphp

                    @if(empty($fields))
                        <p class="small text-muted">Sin cambios detectables</p>
                    @else
                        <div class="overflow-auto">
                            <table class="w-full small" style="border-collapse:collapse">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="padding:8px;border-bottom:1px solid #e5e7eb">Campo</th>
                                        <th class="text-left" style="padding:8px;border-bottom:1px solid #e5e7eb">Anterior</th>
                                        <th class="text-left" style="padding:8px;border-bottom:1px solid #e5e7eb">Nuevo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($fields as $f)
                                        <tr>
                                            <td style="vertical-align:top;padding:8px;border-bottom:1px solid #f1f3f4"><strong>{{ $f }}</strong></td>
                                            <td style="vertical-align:top;padding:8px;border-bottom:1px solid #f1f3f4;min-width:200px">
                                                <pre class="small bg-light p-2 rounded">{{ ($formatAuditValue)($old[$f] ?? null) }}</pre>
                                            </td>
                                            <td style="vertical-align:top;padding:8px;border-bottom:1px solid #f1f3f4;min-width:200px">
                                                <pre class="small bg-light p-2 rounded">{{ ($formatAuditValue)($new[$f] ?? null) }}</pre>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @endforeach
            <div class="table-footer mt-3">{{ $history->withQueryString()->links() }}</div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Reuse base table/role styles for consistent look */
:root{
    --primary-dark-blue:#091c47;
    --table-bg:#eef0f3;
    --text-dark:#3a4651;
    --white:#fff;
}
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:24px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:'Roboto',sans-serif;font-weight:400;font-size:28px;color:var(--text-dark);margin:0}
.table-card{width:100%;max-width:100vw;margin:0 auto 16px auto;background:var(--table-bg);border-radius:10px;overflow:hidden;border:1px solid #cfd6df}
.badge{display:inline-block;padding:.25em .6em;font-size:.75em;border-radius:.25rem}
.bg-info{background:#d1ecf1}
.btn-secondary{background:#f1f3f4;color:#111;border:1px solid #e5e7eb;padding:8px 12px;border-radius:8px}
.btn-pressable{transition:transform .05s ease,filter .15s ease}
.small{font-size:0.85rem}
.bg-light{background:#f7f7f8}
</style>
@endpush
