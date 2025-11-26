@extends('layouts.app')
@section('title', 'Reclamo · Detalle')

@section('content')
<!-- Header -->
<div class="-mx-4 lg:-mx-6 w-full">
    <div class="px-4 lg:px-6">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-gray-900 mb-2">Detalle de Reclamo</h1>
                <p class="text-gray-600">Información detallada del reclamo enviado por el estudiante.</p>
            </div>
            <a href="{{ route('admin.reclamos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i> Atrás
            </a>
        </div>
    </div>
</div>

<div class="table-card p-6">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
        <div class="mb-4">
            <h2 class="text-lg font-semibold">Estudiante</h2>
            <div>{{ optional($reclamo->user)->name }} {{ optional($reclamo->user)->last_name_father }}</div>
            <div class="text-sm text-gray-500">Inscripción: {{ $reclamo->inscription_id }}</div>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Competencia</h2>
            <div>{{ optional($reclamo->inscription->competition)->name ?? '—' }}</div>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Fase</h2>
            <div>{{ $reclamo->fase ?? '—' }}</div>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Mensaje</h2>
            <div class="whitespace-pre-wrap">{{ $reclamo->mensaje }}</div>
        </div>

        <div class="mb-4">
            <h2 class="text-lg font-semibold">Estado</h2>
            <div>{{ ucfirst($reclamo->estado) }}</div>
        </div>

        @if($reclamo->respuesta)
            <div class="mb-4">
                <h2 class="text-lg font-semibold">Respuesta</h2>
                <div class="whitespace-pre-wrap">{{ $reclamo->respuesta }}</div>
            </div>
        @endif

        <div class="flex justify-end gap-3 mt-6">
            @if($reclamo->estado === 'pendiente')
                <form action="{{ route('admin.reclamos.show', $reclamo->id) }}" method="POST" onsubmit="return confirm('Marcar como atendido?')">
                    @csrf
                    <input type="hidden" name="accion" value="atender">
                    <button class="px-4 py-2 bg-green-600 text-white rounded-md">Marcar como atendido</button>
                </form>
            @endif
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Minimal styles to align detail with roles look */
.content-header{display:grid;grid-template-columns:1fr auto 1fr;align-items:center;margin-bottom:24px}
.content-title{grid-column:2;justify-self:center;text-align:center;font-family:'Roboto',sans-serif;font-weight:400;font-size:32px;color:#3a4651;margin:0}
.table-card{width:100%;max-width:100vw;margin:0 auto 16px auto;background:#eef0f3;border-radius:10px;overflow:hidden;border:1px solid #cfd6df}
</style>
@endpush
