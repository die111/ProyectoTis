@extends('layouts.app')
@section('title', 'Inscripción a Competencias')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Inscripción a Competencias</h1>
        <p class="text-gray-600">Inscríbete a las competencias activas disponibles</p>
    </div>

    <!-- Pestañas -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="flex -mb-px space-x-8" aria-label="Tabs">
            <button id="tabCompetencias" class="tab-button active border-b-2 border-blue-500 py-4 px-1 text-sm font-medium text-blue-600">
                Competencias Disponibles
            </button>
            <button id="tabMisInscripciones" class="tab-button border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300">
                Mis Inscripciones
            </button>
        </nav>
    </div>

    <!-- Contenido: Competencias Disponibles -->
    <div id="contentCompetencias" class="tab-content">
        @if($competenciasActivas->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <i class="fas fa-info-circle text-yellow-500 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-yellow-800 mb-2">No hay competencias activas</h3>
                <p class="text-yellow-700">Por el momento no hay competencias disponibles para inscripción.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($competenciasActivas as $competencia)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                            <h3 class="text-xl font-bold text-white mb-1">{{ $competencia->name }}</h3>
                            <p class="text-blue-100 text-sm">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                {{ $competencia->fechaInicio->format('d/m/Y') }} - {{ $competencia->fechaFin->format('d/m/Y') }}
                            </p>
                        </div>
                        
                        <div class="p-4">
                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($competencia->description, 100) }}</p>
                            
                            @if($competencia->area)
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-1"></i>
                                        {{ $competencia->area->name }}
                                    </span>
                                </div>
                            @endif

                            @if($competencia->phases->isNotEmpty())
                                <div class="mb-4">
                                    <p class="text-xs font-semibold text-gray-700 mb-1">Fases:</p>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($competencia->phases->take(3) as $phase)
                                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                {{ $phase->name }}
                                            </span>
                                        @endforeach
                                        @if($competencia->phases->count() > 3)
                                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                +{{ $competencia->phases->count() - 3 }} más
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <button onclick="window.location.href='{{ route('estudiante.inscripcion.create', $competencia->id) }}'" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors duration-200">
                                <i class="fas fa-file-signature mr-2"></i>
                                Inscribirse
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Contenido: Mis Inscripciones -->
    <div id="contentMisInscripciones" class="tab-content hidden">
        @if($misInscripciones->isEmpty())
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                <i class="fas fa-clipboard-list text-gray-400 text-4xl mb-3"></i>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">No tienes inscripciones</h3>
                <p class="text-gray-600">Aún no te has inscrito a ninguna competencia.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Competencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Área</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nivel</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Inscripción</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($misInscripciones as $inscripcion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $inscripcion->competition->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $inscripcion->area->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $inscripcion->level->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($inscripcion->estado === 'pendiente')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    @elseif($inscripcion->estado === 'confirmada')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Confirmada
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Rechazada
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $inscripcion->created_at->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .tab-button.active {
        border-color: #3B82F6;
        color: #3B82F6;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Cambio de pestañas
    document.getElementById('tabCompetencias').addEventListener('click', function() {
        document.getElementById('contentCompetencias').classList.remove('hidden');
        document.getElementById('contentMisInscripciones').classList.add('hidden');
        this.classList.add('active', 'border-blue-500', 'text-blue-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tabMisInscripciones').classList.remove('active', 'border-blue-500', 'text-blue-600');
        document.getElementById('tabMisInscripciones').classList.add('border-transparent', 'text-gray-500');
    });

    document.getElementById('tabMisInscripciones').addEventListener('click', function() {
        document.getElementById('contentMisInscripciones').classList.remove('hidden');
        document.getElementById('contentCompetencias').classList.add('hidden');
        this.classList.add('active', 'border-blue-500', 'text-blue-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tabCompetencias').classList.remove('active', 'border-blue-500', 'text-blue-600');
        document.getElementById('tabCompetencias').classList.add('border-transparent', 'text-gray-500');
    });

    // Mostrar mensajes de sesión (si existen)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#3B82F6'
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonColor: '#EF4444'
        });
    @endif
</script>
@endpush
