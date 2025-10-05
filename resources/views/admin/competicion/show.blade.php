@extends('layouts.app')
@section('title', 'Detalle de Competencia')
@section('content')
<main class="min-h-screen bg-background p-6 md:p-12">
    <div class="mx-auto max-w-3xl">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2">Detalle de Competencia</h1>
            <p class="text-muted-foreground" style="color:#374151;">Consulta la información completa de la competencia seleccionada</p>
        </div>
        <div class="rounded-lg border border-border bg-card p-8 shadow-md">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-xs font-semibold text-muted-foreground uppercase mb-1">Nombre de la competicíon</dt>
                    <dd class="text-lg text-foreground">{{ $competicion->name }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-muted-foreground uppercase mb-1">Estado</dt>
                    <dd>
                        @if($competicion->state === 'activa')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Activa</span>
                        @elseif($competicion->state === 'completada')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-blue-500/20 text-blue-400 border border-blue-500/30">Completada</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-red-500/20 text-red-400 border border-red-500/30">Cancelada</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-muted-foreground uppercase mb-1">Fechas</dt>
                    <dd class="text-foreground">{{ $competicion->fechaInicio->format('d M Y') }} - {{ $competicion->fechaFin->format('d M Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-muted-foreground uppercase mb-1">Áreas</dt>
                    <dd class="text-foreground">
                        {{ $competicion->areas->pluck('name')->implode(', ') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold text-muted-foreground uppercase mb-1">Niveles</dt>
                    <dd class="text-foreground">
                        {{ $competicion->levels->pluck('nombre')->implode(', ') }}
                    </dd>
                </div>  
                <div>
                    <dt class="text-xs font-semibold text-muted-foreground uppercase mb-1">Fases</dt>
                    <dd class="text-foreground">
                        @if($competicion->phases->count())
                            <ul class="list-disc ml-5">
                                @foreach($competicion->phases as $phase)
                                    <li>
                                        {{ $phase->name }}
                                        <span class="text-xs text-muted-foreground">
                                            (@if($phase->pivot->start_date){{ \Carbon\Carbon::parse($phase->pivot->start_date)->format('d M Y') }}@else-@endif
                                            -
                                            @if($phase->pivot->end_date){{ \Carbon\Carbon::parse($phase->pivot->end_date)->format('d M Y') }}@else-@endif)
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted-foreground">Sin fases registradas</span>
                        @endif
                    </dd>
                </div>
            </dl>
            <div class="flex justify-end mt-8">
                <a href="{{ route('admin.competicion.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</main>
@endsection
