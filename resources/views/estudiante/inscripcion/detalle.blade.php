@extends('layouts.app')
@section('title', 'Detalle de Inscripción')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Detalle de Inscripción</h1>
            <p class="text-gray-600">Competencia: <strong>{{ $competicion->name }}</strong></p>
            <p class="text-sm text-gray-500">Área: {{ $inscripcion->area?->name ?? '—' }} · Nivel: {{ $inscripcion->level?->name ?? 'N/A' }}</p>
        </div>
        <div>
            <a href="{{ route('estudiante.inscripcion.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Volver</a>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Notas por Fase</h2>

        @if($phases->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded p-4">No hay fases configuradas para esta competencia.</div>
        @else
            <div class="space-y-4">
                @foreach($phases as $index => $phase)
                    @php
                        $eval = $evaluaciones->firstWhere('stage.nombre', $phase->name);
                        $existingReclamo = $reclamos->firstWhere('fase', $phase->id);
                    @endphp
                    <div class="p-4 border rounded flex items-center justify-between">
                        <div>
                            <div class="font-medium">Fase {{ $index + 1 }} — {{ $phase->name }}</div>
                            @if($eval)
                                <div class="text-sm text-gray-700">Nota: <strong>{{ $eval->nota }}</strong> · Estado: {{ $eval->estado }}</div>
                                @if($eval->observaciones_evaluador)
                                    <div class="text-sm text-gray-600">Observaciones: {{ $eval->observaciones_evaluador }}</div>
                                @endif
                            @else
                                <div class="text-sm text-gray-500">No se subió la nota aún.</div>
                            @endif

                            @if($existingReclamo)
                                <div class="mt-2 text-sm">
                                    <span class="inline-block px-2 py-1 rounded text-sm font-medium bg-yellow-100 text-yellow-800">Reclamo: {{ ucfirst($existingReclamo->estado) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            @if($existingReclamo && $existingReclamo->estado === 'pendiente')
                                <button disabled class="px-3 py-1 bg-gray-300 text-gray-700 rounded">Reclamar</button>
                            @else
                                <button onclick="document.getElementById('fase_id').value='{{ $phase->id }}'; document.getElementById('evaluation_id').value='{{ $eval?->id ?? '' }}'; document.getElementById('mensaje').focus();" class="px-3 py-1 bg-blue-600 text-white rounded">Reclamar</button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Enviar Reclamo</h2>
        <form method="POST" action="{{ route('estudiante.inscripcion.reclamar', $inscripcion->id) }}">
            @csrf
            <input type="hidden" name="fase_id" id="fase_id" value="">
            <input type="hidden" name="evaluation_id" id="evaluation_id" value="">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Mensaje</label>
                <textarea name="mensaje" id="mensaje" rows="4" class="mt-1 block w-full border rounded p-2">{{ old('mensaje') }}</textarea>
                @error('mensaje')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('estudiante.inscripcion.index') }}" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</a>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Enviar Reclamo</button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4">Reclamos enviados</h2>
        @if($reclamos->isEmpty())
            <div class="text-gray-600">No has enviado reclamos para esta inscripción.</div>
        @else
            <div class="space-y-3">
                @foreach($reclamos as $r)
                    <div class="p-3 border rounded">
                        <div class="flex justify-between items-center">
                            <div class="text-sm font-medium">{{ $r->created_at->setTimezone(date_default_timezone_get())->format('d/m/Y H:i') }}</div>
                            <div class="text-sm text-gray-600">Estado: {{ $r->estado }}</div>
                        </div>
                        <div class="mt-2 text-sm text-gray-800">{{ $r->mensaje }}</div>
                        @if($r->respuesta)
                            <div class="mt-2 text-sm text-green-700">Respuesta: {{ $r->respuesta }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
