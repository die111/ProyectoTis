<div class="space-y-6">
    <!-- Filtros -->
    <div class="flex flex-wrap gap-3 mb-6">
        <button data-filter="all"
            class="filter-btn bg-gray-200 text-gray-800 hover:bg-gray-300 focus:bg-primary focus:text-white px-4 py-2 rounded-md font-semibold transition-colors shadow-sm border border-gray-300 active">
            Todas ({{ $competiciones->count() }})
        </button>
        <button data-filter="activa"
            class="filter-btn bg-emerald-100 text-emerald-700 hover:bg-emerald-200 focus:bg-emerald-500 focus:text-white px-4 py-2 rounded-md font-semibold transition-colors shadow-sm border border-emerald-200">
            <svg class="h-4 w-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            Activas ({{ $competiciones->where('state', 'activa')->count() }})
        </button>
        <button data-filter="completada"
            class="filter-btn bg-blue-100 text-blue-700 hover:bg-blue-200 focus:bg-blue-500 focus:text-white px-4 py-2 rounded-md font-semibold transition-colors shadow-sm border border-blue-200">
            <svg class="h-4 w-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            Completadas ({{ $competiciones->where('state', 'completada')->count() }})
        </button>
        <button data-filter="cancelada"
            class="filter-btn bg-red-100 text-red-700 hover:bg-red-200 focus:bg-red-500 focus:text-white px-4 py-2 rounded-md font-semibold transition-colors shadow-sm border border-red-200">
            <svg class="h-4 w-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            Canceladas ({{ $competiciones->where('state', 'cancelada')->count() }})
        </button>
    </div>
    <style>
        .filter-btn.active,
        .filter-btn:focus {
            outline: none;
            box-shadow: 0 0 0 2px #2563eb33;
        }
    </style>

    <!-- Lista de Competencias -->
    <div class="grid gap-4">
        @forelse($competiciones as $competition)
        <div data-status="{{ $competition->state }}" class="rounded-lg border border-border bg-card hover:border-primary/50 transition-colors">
            <div class="p-6">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div class="flex-1">
                        <h3 class="flex items-center gap-2 text-foreground mb-2 text-xl font-semibold">
                            <svg class="h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"></path>
                                <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"></path>
                                <path d="M4 22h16"></path>
                                <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"></path>
                                <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"></path>
                                <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"></path>
                            </svg>
                            {{ $competition->name }}
                        </h3>
                        @if($competition->state === 'activa')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            Activa
                        </span>
                        @elseif($competition->state === 'completada')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                            Completada
                        </span>
                        @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm bg-red-500/20 text-red-400 border border-red-500/30">
                            <svg class="h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                <line x1="9" y1="9" x2="15" y2="15"></line>
                            </svg>
                            Cancelada
                        </span>
                        @endif
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.competicion.show', $competition) }}" 
                           class="btn btn-primary inline-flex items-center justify-center rounded-md text-sm font-medium px-3 py-2">
                            Ver Detalles
                        </a>
                        <a href="{{ route('admin.competicion.edit', $competition) }}" 
                           class="btn btn-primary inline-flex items-center justify-center rounded-md text-sm font-medium px-3 py-2">
                            Editar
                        </a>
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Fechas -->
                    <div class="flex items-start gap-2">
                        <svg class="h-4 w-4 text-primary mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <div>
                            <p class="text-xs text-muted-foreground">Fechas</p>
                            <p class="text-sm text-foreground">
                                {{ $competition->fechaInicio->format('d M Y') }} - {{ $competition->fechaFin->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- Fases -->
                    <div class="flex items-start gap-2">
                        <svg class="h-4 w-4 text-primary mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <div>
                            <p class="text-xs text-muted-foreground">Fases</p>
                            <p class="text-sm text-foreground">
                                @if($competition->phases->count() > 0)
                                    {{ $competition->phases->count() }} fases
                                @else
                                    <span class="text-xs text-muted-foreground">Sin fases registradas</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Categorías -->
                    <div class="flex items-start gap-2">
                        <svg class="h-4 w-4 text-primary mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6H9"></path>
                            <path d="M14 6H4"></path>
                            <path d="M20 12H4"></path>
                            <path d="M20 18H9"></path>
                        </svg>
                        <div>
                            <p class="text-xs text-muted-foreground">Categorías</p>
                            <p class="text-sm text-foreground">
                                @php
                                    $categoriaNames = $competition->categorias->pluck('nombre');
                                    $firstCategorias = $categoriaNames->take(2);
                                    $remainingCategorias = $categoriaNames->count() - 2;
                                @endphp
                                @if($firstCategorias->count() > 0)
                                    {{ $firstCategorias->implode(', ') }}
                                    @if($remainingCategorias > 0)
                                        <span class="text-xs text-muted-foreground">+{{ $remainingCategorias }} más</span>
                                    @endif
                                @elseif($remainingCategorias > 0)
                                    <span class="text-xs text-muted-foreground">+{{ $remainingCategorias }} más</span>
                                @else
                                    <span class="text-xs text-muted-foreground">Sin categorías registradas</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Áreas -->
                    <div class="flex items-start gap-2">
                        <svg class="h-4 w-4 text-primary mt-0.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <div>
                            <p class="text-xs text-muted-foreground">Áreas</p>
                            <p class="text-sm text-foreground">
                                @php
                                    $areaNames = $competition->areas->pluck('name');
                                    $firstAreas = $areaNames->take(2);
                                    $remainingAreas = $areaNames->count() - 2;
                                @endphp
                                @if($firstAreas->count() > 0)
                                    {{ $firstAreas->implode(', ') }}
                                    @if($remainingAreas > 0)
                                        <span class="text-xs text-muted-foreground">+{{ $remainingAreas }} más</span>
                                    @endif
                                @elseif($remainingAreas > 0)
                                    <span class="text-xs text-muted-foreground">+{{ $remainingAreas }} más</span>
                                @else
                                    <span class="text-xs text-muted-foreground">Sin áreas registradas</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if($competition->categoryAreas && $competition->categoryAreas->count())
                <div class="mt-4">
                    <p class="text-xs text-muted-foreground mb-1">Categoría × Área</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($competition->categoryAreas as $pair)
                            <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 text-gray-800 text-xs border border-gray-200">
                                <i class="fas fa-tags mr-1 text-gray-500"></i>
                                {{ optional($pair->categoria)->nombre ?? '—' }}
                                <span class="mx-1">×</span>
                                {{ optional($pair->area)->name ?? '—' }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Acciones integradas al contenido -->
                <div class="mt-6 flex justify-end gap-2">
                    @if($competition->state === 'activa')
                        <form class="swal-finalizar-competicion" action="{{ route('admin.competicion.updateState', ['id' => $competition->id, 'state' => 'completada']) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-md bg-blue-100 text-blue-700 hover:bg-blue-200 border border-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-300 transition shadow-sm">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                Finalizar competición
                            </button>
                        </form>
                        <form class="swal-cancelar-competicion" action="{{ route('admin.competicion.updateState', ['id' => $competition->id, 'state' => 'cancelada']) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-md bg-red-100 text-red-700 hover:bg-red-200 border border-red-200 focus:outline-none focus:ring-2 focus:ring-red-300 transition shadow-sm">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                Cancelar competición
                            </button>
                        </form>
                    @elseif($competition->state === 'completada')
                        <a href="{{ route('admin.evaluacion.premiacion.pdf', $competition) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border border-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-300 transition shadow-sm">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>
                            Imprimir reporte
                        </a>
                        <span class="text-xs text-blue-600 font-semibold">Competición finalizada</span>
                    @elseif($competition->state === 'cancelada')
                        <form class="swal-activar-competicion" action="{{ route('admin.competicion.updateState', ['id' => $competition->id, 'state' => 'activa']) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-md bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border border-emerald-200 focus:outline-none focus:ring-2 focus:ring-emerald-300 transition shadow-sm">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" /></svg>
                                Activar competición
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <!-- Eliminado footer externo separado -->
        </div>
        @empty
        <div class="rounded-lg border border-border bg-card">
            <div class="py-12">
                <p class="text-center text-muted-foreground">No hay competiciones registradas</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
 document.addEventListener('DOMContentLoaded', function(){
    function attachSwal(selector, opts){
        document.querySelectorAll(selector).forEach(form => {
            form.addEventListener('submit', function(e){
                e.preventDefault();
                Swal.fire(Object.assign({
                    icon: opts.icon || 'warning',
                    title: opts.title,
                    text: opts.text,
                    showCancelButton: true,
                    confirmButtonText: opts.confirmText,
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: opts.confirmColor || '#0C3E92',
                    cancelButtonColor: '#6c757d',
                    iconColor: opts.iconColor || '#091c47'
                }, opts.extra || {})).then(r => { if(r.isConfirmed){ form.submit(); } });
            });
        });
    }
    attachSwal('.swal-cancelar-competicion',{title:'¿Cancelar competición?', text:'Se marcará como cancelada y no podrá continuar.', confirmText:'Sí, cancelar', confirmColor:'#dc2626'});
    attachSwal('.swal-finalizar-competicion',{title:'¿Finalizar competición?', text:'Se marcará como completada y no se podrán volver a abrir fases.', confirmText:'Finalizar', confirmColor:'#2563eb'});
    attachSwal('.swal-activar-competicion',{title:'¿Activar competición?', text:'La competición volverá a estar activa.', confirmText:'Activar', confirmColor:'#059669'});
 });
</script>
@endpush
