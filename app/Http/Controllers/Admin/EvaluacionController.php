<?php

namespace App\Http\Controllers\Admin;


use App\Models\{
    Competicion, 
    Etapa, 
    Inscription, 
    Evaluation, 
    EvaluationLog,
    Medal,
    Winer
};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class EvaluacionController extends \App\Http\Controllers\Controller
{
    // Constantes para estados
    const ETAPA_CLASIFICATORIA = 1;
    const ETAPA_FINAL = 2;
    
    const ESTADO_CLASIFICADO = 'clasificado';
    const ESTADO_NO_CLASIFICADO = 'no_clasificado';
    const ESTADO_DESCLASIFICADO = 'desclasificado';

    public function index()
    {
        $competiciones = Competicion::with(['area', 'phases'])
            ->orderBy('fechaInicio', 'desc')
            ->get();
            
        return view('admin.evaluacion.index', compact('competiciones'));
    }
    /**
     * Cargar inscripciones desde CSV
     */
    public function cargarInscripcionesDesdeCSV($competicionId, $archivo)
    {
        return DB::transaction(function () use ($competicionId, $archivo) {
            $ruta = $archivo->store('temp');
            $rutaCompleta = Storage::path($ruta);
            
            $inscripcionesCargadas = 0;
            $filas = array_map('str_getcsv', file($rutaCompleta));
            $encabezados = array_shift($filas);
            
            foreach ($filas as $fila) {
                $datos = array_combine($encabezados, $fila);
                
                // Buscar o crear usuario (olimpista)
                $usuario = $this->buscarOCrearOlimpista($datos);
                
                // Crear inscripción
                Inscription::create([
                    'competition_id' => $competicionId,
                    'user_id' => $usuario->id,
                    'area_id' => $this->obtenerAreaId($datos['area']),
                    'level_id' => $this->obtenerNivelId($datos['nivel']),
                    'estado' => 'confirmada',
                    'es_grupal' => $datos['es_grupal'] ?? false,
                    'grupo_nombre' => $datos['grupo_nombre'] ?? null,
                ]);
                
                $inscripcionesCargadas++;
            }
            
            Storage::delete($ruta);
            return $inscripcionesCargadas;
        });
    }

    /**
     * Obtener lista para evaluación
     */
    public function obtenerListaEvaluacion($competicionId, $stageId, $areaId, $nivelId, $filtros = [])
    {
        $query = Inscription::with(['user', 'area', 'level'])
            ->where('competition_id', $competicionId)
            ->where('area_id', $areaId)
            ->where('level_id', $nivelId)
            ->where('estado', 'confirmada')
            ->where('is_active', true);
        
        // Cargar evaluación existente si existe
        $query->with(['evaluations' => function($q) use ($stageId) {
            $q->where('stage_id', $stageId);
        }]);
        
        // Aplicar filtros
        if (isset($filtros['departamento'])) {
            $query->whereHas('user', function($q) use ($filtros) {
                $q->where('school', 'LIKE', "%{$filtros['departamento']}%");
            });
        }
        
        return $query->get()->map(function($inscripcion) use ($stageId) {
            $evaluacion = $inscripcion->evaluations->first();
            
            return [
                'inscripcion_id' => $inscripcion->id,
                'user_id' => $inscripcion->user_id,
                'nombre_completo' => $inscripcion->user->name . ' ' . $inscripcion->user->last_name_father,
                'documento_identidad' => $inscripcion->user->ci,
                'unidad_educativa' => $inscripcion->user->school,
                'area' => $inscripcion->area->name,
                'nivel' => $inscripcion->level->nombre,
                'evaluacion_id' => $evaluacion?->id,
                'nota' => $evaluacion?->nota,
                'estado_evaluacion' => $evaluacion?->estado,
                'descripcion_conceptual' => $evaluacion?->descripcion_conceptual,
                'cumple_etica' => $evaluacion?->cumple_etica,
            ];
        });
    }

    /**
     * Registrar o actualizar evaluación
     */
    public function registrarEvaluacion($datos, $evaluadorId)
    {
        return DB::transaction(function () use ($datos, $evaluadorId) {
            // Validar permisos del evaluador
            if (!$this->tienePermisosEvaluacion($evaluadorId, $datos['area_id'])) {
                throw new \Exception('No tiene permisos para evaluar en esta área');
            }
            
            // Determinar estado de evaluación
            $estado = $this->determinarEstadoEvaluacion(
                $datos['nota'], 
                $datos['cumple_etica']
            );
            
            $evaluacionData = [
                'stage_id' => $datos['stage_id'],
                'evaluator_id' => $evaluadorId,
                'nota' => $datos['nota'],
                'estado' => $estado,
                'descripcion_conceptual' => $datos['descripcion_conceptual'],
                'cumple_etica' => $datos['cumple_etica'],
                'observaciones_evaluador' => $datos['observaciones_evaluador'] ?? null,
            ];
            
            // Buscar evaluación existente
            $evaluacionExistente = Evaluation::where('inscription_id', $datos['inscription_id'])
                ->where('stage_id', $datos['stage_id'])
                ->first();
            
            if ($evaluacionExistente) {
                // Registrar cambio en log
                $this->registrarCambioEvaluacion($evaluacionExistente, $evaluacionData, $evaluadorId);
                
                // Actualizar evaluación
                $evaluacionExistente->update($evaluacionData);
                return $evaluacionExistente;
            } else {
                // Crear nueva evaluación
                $evaluacionData['inscription_id'] = $datos['inscription_id'];
                return Evaluation::create($evaluacionData);
            }
        });
    }

    /**
     * Determinar estado de evaluación
     */
    private function determinarEstadoEvaluacion($nota, $cumpleEtica)
    {
        if (!$cumpleEtica) {
            return self::ESTADO_DESCLASIFICADO;
        }
        
        $notaAprobacion = 51.0; // Puede ser parametrizable
        
        return $nota >= $notaAprobacion 
            ? self::ESTADO_CLASIFICADO 
            : self::ESTADO_NO_CLASIFICADO;
    }

    /**
     * Registrar cambio en log
     */
    private function registrarCambioEvaluacion($evaluacionAnterior, $nuevaData, $usuarioId)
    {
        EvaluationLog::create([
            'evaluation_id' => $evaluacionAnterior->id,
            'nota_anterior' => $evaluacionAnterior->nota,
            'nota_nueva' => $nuevaData['nota'],
            'estado_anterior' => $evaluacionAnterior->estado,
            'estado_nuevo' => $nuevaData['estado'],
            'user_id' => $usuarioId,
            'motivo' => 'Actualización de evaluación'
        ]);
    }

    /**
     * Finalizar etapa de evaluación
     */
    public function finalizarEtapa($competicionId, $stageId, $responsableId)
    {
        return DB::transaction(function () use ($competicionId, $stageId, $responsableId) {
            // Validar que sea responsable del área
            if (!$this->esResponsableArea($responsableId, $competicionId)) {
                throw new \Exception('No tiene permisos para finalizar esta etapa');
            }
            
            // Verificar que todas las inscripciones tengan evaluación
            $inscripcionesPendientes = Inscription::where('competition_id', $competicionId)
                ->where('estado', 'confirmada')
                ->where('is_active', true)
                ->whereDoesntHave('evaluations', function($q) use ($stageId) {
                    $q->where('stage_id', $stageId);
                })
                ->count();
            
            if ($inscripcionesPendientes > 0) {
                throw new \Exception("Hay {$inscripcionesPendientes} evaluaciones pendientes");
            }
            
            // Actualizar estado del stage
            $stage = Etapa::find($stageId);
            // Aquí podrías agregar un campo 'estado' a la tabla stages
            
            // Si es etapa de clasificación, preparar para la final
            if ($this->esEtapaClasificatoria($stageId)) {
                $this->prepararClasificadosParaFinal($competicionId, $stageId);
            }
            
            return true;
        });
    }

    /**
     * Preparar clasificados para etapa final
     */
    private function prepararClasificadosParaFinal($competicionId, $stageId)
    {
        $clasificados = Evaluation::whereHas('stage', function($q) use ($competicionId, $stageId) {
                $q->where('id_competition', $competicionId)
                  ->where('id', $stageId);
            })
            ->where('estado', self::ESTADO_CLASIFICADO)
            ->where('is_active', true)
            ->with('inscription')
            ->get();
        
        // Aquí podrías crear registros en una tabla de clasificados_final
        // o marcar las inscripciones como clasificadas
        foreach ($clasificados as $clasificado) {
            // Lógica para manejar clasificados
        }
    }

    /**
     * Generar reportes
     */
    public function generarReporte($competicionId, $stageId, $tipoReporte)
    {
    $stage = Etapa::findOrFail($stageId);
        
        $query = Evaluation::with(['inscription.user', 'inscription.area', 'inscription.level'])
            ->where('stage_id', $stageId)
            ->where('is_active', true);
        
        switch ($tipoReporte) {
            case 'clasificados':
                $query->where('estado', self::ESTADO_CLASIFICADO);
                break;
                
            case 'no_clasificados':
                $query->where('estado', self::ESTADO_NO_CLASIFICADO);
                break;
                
            case 'desclasificados':
                $query->where('estado', self::ESTADO_DESCLASIFICADO);
                break;
                
            case 'premiados':
                return $this->generarReportePremiados($competicionId);
                
            default:
                throw new \Exception('Tipo de reporte no válido');
        }
        
        return $query->get()->map(function($evaluacion) {
            return [
                'nombre_completo' => $evaluacion->inscription->user->name . ' ' . 
                                   $evaluacion->inscription->user->last_name_father,
                'documento_identidad' => $evaluacion->inscription->user->ci,
                'unidad_educativa' => $evaluacion->inscription->user->school,
                'departamento' => $evaluacion->inscription->user->school, // Ajustar según tu estructura
                'area' => $evaluacion->inscription->area->name,
                'nivel' => $evaluacion->inscription->level->nombre,
                'nota' => $evaluacion->nota,
                'descripcion_conceptual' => $evaluacion->descripcion_conceptual,
                'estado' => $evaluacion->estado,
            ];
        });
    }

    /**
     * Generar reporte de premiados con medallero
     */
    public function generarReportePremiados($competicionId)
    {
        $premiados = [];
        
        // Obtener todas las áreas y niveles
        $areas = \App\Models\Area::where('is_active', true)->get();
        $niveles = \App\Models\Level::all();
        
        foreach ($areas as $area) {
            foreach ($niveles as $nivel) {
                $configMedallero = Medal::where('competition_id', $competicionId)
                    ->where('area_id', $area->id)
                    ->where('level_id', $nivel->id)
                    ->first();
                
                if (!$configMedallero) continue;
                
                $evaluaciones = Evaluation::with(['inscription.user'])
                    ->whereHas('inscription', function($q) use ($competicionId, $area, $nivel) {
                        $q->where('competition_id', $competicionId)
                          ->where('area_id', $area->id)
                          ->where('level_id', $nivel->id);
                    })
                    ->where('estado', self::ESTADO_CLASIFICADO)
                    ->where('is_active', true)
                    ->orderBy('nota', 'DESC')
                    ->get();
                
                $posicion = 1;
                foreach ($evaluaciones as $index => $evaluacion) {
                    $premio = $this->determinarPremio($posicion, $configMedallero);
                    if ($premio) {
                        $premiados[] = [
                            'nombre_completo' => $evaluacion->inscription->user->name . ' ' . 
                                               $evaluacion->inscription->user->last_name_father,
                            'unidad_educativa' => $evaluacion->inscription->user->school,
                            'area' => $area->name,
                            'nivel' => $nivel->nombre,
                            'nota' => $evaluacion->nota,
                            'premio' => $premio,
                            'posicion' => $posicion,
                        ];
                        
                        // Registrar en winners si no existe
                        $this->registrarGanador($evaluacion->id, $premio, $posicion);
                    }
                    $posicion++;
                }
            }
        }
        
        return $premiados;
    }

    /**
     * Determinar premio según posición y configuración
     */
    private function determinarPremio($posicion, $configMedallero)
    {
        if ($posicion <= $configMedallero->oro) return 'oro';
        if ($posicion <= $configMedallero->oro + $configMedallero->plata) return 'plata';
        if ($posicion <= $configMedallero->oro + $configMedallero->plata + $configMedallero->bronce) return 'bronce';
        if ($posicion <= $configMedallero->oro + $configMedallero->plata + $configMedallero->bronce + $configMedallero->menciones_honor) {
            return 'mencion_honor';
        }
        
        return null;
    }

    /**
     * Registrar ganador
     */
    private function registrarGanador($evaluationId, $premio, $posicion)
    {
        \App\Models\Winer::firstOrCreate(
            [
                'evaluation_id' => $evaluationId,
                'premio' => $premio
            ],
            [
                'posicion' => $posicion
            ]
        );
    }

    /**
     * Buscar o crear usuario (olimpista) a partir de los datos del CSV
     */
    private function buscarOCrearOlimpista($datos)
    {
        // Ajusta los campos según tu estructura de User
        return \App\Models\User::firstOrCreate(
            [
                'ci' => $datos['ci']
            ],
            [
                'name' => $datos['nombre'],
                'last_name_father' => $datos['apellido_paterno'] ?? '',
                'last_name_mother' => $datos['apellido_materno'] ?? '',
                'school' => $datos['unidad_educativa'] ?? '',
                'email' => $datos['email'] ?? null,
            ]
        );
    }

    /**
     * Obtener el ID del área a partir del nombre
     */
    private function obtenerAreaId($nombreArea)
    {
        $area = \App\Models\Area::where('name', $nombreArea)->first();
        return $area ? $area->id : null;
    }

    /**
     * Obtener el ID del nivel a partir del nombre
     */
    private function obtenerNivelId($nombreNivel)
    {
        $nivel = \App\Models\Level::where('nombre', $nombreNivel)->first();
        return $nivel ? $nivel->id : null;
    }

    /**
     * Mostrar las fases de una competición específica
     */
    public function showFases(Competicion $competicion)
    {
        // Cargar las fases de la competición ordenadas por fechas
        $fases = $competicion->phases()
            ->orderBy('start_date', 'asc')
            ->orderBy('end_date', 'asc')
            ->get();
        
        // Preparar datos para la tarjeta de Premiación siguiendo la lógica de fases
        $clasificados = collect();
        $premiacion = [
            'start_date' => $competicion->premiacion_inicio,
            'end_date' => $competicion->premiacion_fin,
        ];
        $totalFases = $fases->count();

        if ($totalFases > 0) {
            $numeroFaseAnterior = $totalFases; // última fase real listada

            // Traer evaluaciones clasificadas de la última fase para esta competición
            $clasificados = \App\Models\Evaluation::with(['inscription.user'])
                ->where('estado', self::ESTADO_CLASIFICADO)
                ->whereHas('inscription', function ($q) use ($competicion, $numeroFaseAnterior) {
                    $q->where('competition_id', $competicion->id)
                      ->where('is_active', true)
                      ->where('estado', 'confirmada')
                      ->where('fase', $numeroFaseAnterior);
                })
                ->orderByDesc('nota')
                ->get()
                ->map(function ($ev) {
                    return (object) [
                        'name' => trim(($ev->inscription->user->name ?? '') . ' ' . ($ev->inscription->user->last_name_father ?? '')),
                        'full_name' => trim(($ev->inscription->user->name ?? '') . ' ' . ($ev->inscription->user->last_name_father ?? '')),
                        'email' => $ev->inscription->user->email ?? null,
                        'score' => $ev->nota,
                    ];
                });
        }
        
        return view('admin.evaluacion.fases', compact('competicion', 'fases', 'clasificados', 'premiacion'));
    }
    
    /**
     * Gestionar estudiantes de una fase específica en una competición específica
     */
    public function gestionarEstudiantes(Competicion $competicion, $faseId)
    {
        $fase = \App\Models\Phase::findOrFail($faseId);
        $faseEnCompeticion = $competicion->phases()->where('phase_id', $faseId)->first();
        if (!$faseEnCompeticion) {
            abort(404, 'La fase no está asociada a esta competición');
        }
        $todasLasFases = $competicion->phases()->orderBy('competition_phase.id')->get();

        // Leer ?fase_n o ?fase
        $numeroFase = (int) request('fase_n', request('fase'));
        if ($numeroFase <= 0) {
            foreach ($todasLasFases as $index => $f) {
                if ($f->id == $faseId) { $numeroFase = $index + 1; break; }
            }
            if ($numeroFase <= 0) { $numeroFase = 1; }
        } else {
            $maxFases = max(1, $todasLasFases->count());
            if ($numeroFase > $maxFases) { $numeroFase = $maxFases; }
        }

        $categorias = \App\Models\Categoria::where('is_active', true)->get();
        $areas = \App\Models\Area::where('is_active', true)->get();
        $query = \App\Models\Inscription::with(['user', 'area', 'categoria']);
        $query->where('competition_id', $competicion->id);
        $query->where('fase', $numeroFase);
        $query->where('estado', 'confirmada');
        if (request('estado_activo') === 'inactivo') { $query->where('is_active', false); }
        elseif (request('estado_activo') === 'todos') { /* no-op */ }
        else { $query->where('is_active', true); }
        if (request('categoria')) { $query->where('categoria_id', request('categoria')); }
        if (request('area')) { $query->where('area_id', request('area')); }
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name_father', 'like', "%{$search}%")
                  ->orWhere('last_name_mother', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%");
            });
        }
        $estudiantes = $query->paginate(10)->appends(request()->query());
        return view('admin.evaluacion.estudiantes', compact('fase', 'competicion', 'categorias', 'areas', 'estudiantes', 'numeroFase'));
    }

    public function calificar(Competicion $competicion, $faseId)
    {
        $fase = $competicion->phases()->findOrFail($faseId);
        $todasLasFases = $competicion->phases()->orderBy('competition_phase.id')->get();
        
        // Leer ?fase_n o ?fase
        $numeroFase = (int) request('fase_n', request('fase'));
        if ($numeroFase <= 0) {
            foreach ($todasLasFases as $index => $f) {
                if ($f->id == $faseId) { $numeroFase = $index + 1; break; }
            }
            if ($numeroFase <= 0) { $numeroFase = 1; }
        } else {
            $maxFases = max(1, $todasLasFases->count());
            if ($numeroFase > $maxFases) { $numeroFase = $maxFases; }
        }

        $categorias = \App\Models\Categoria::where('is_active', true)->get();
        $areas = \App\Models\Area::where('is_active', true)->get();
        $query = Inscription::with(['user', 'area', 'categoria']);
        $query->where('competition_id', $competicion->id);
        $query->where('fase', $numeroFase);
        if (request('estado_activo') === 'inactivo') { $query->where('is_active', false); }
        elseif (request('estado_activo') === 'todos') { /* no-op */ }
        else { $query->where('is_active', true); }
        if (request('area')) { $query->where('area_id', request('area')); }
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name_father', 'like', "%{$search}%")
                  ->orWhere('last_name_mother', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%")
                  ->orWhere('ci', 'like', "%{$search}%");
            });
        }
        $sortBy = request('sort_by', 'nombre');
        $sortOrder = request('sort_order', 'asc');
        
        // Cargar evaluaciones sin filtrar por stage_id
        $estudiantes = $query->with('evaluations')->get();
        
        if ($sortBy === 'nombre') {
            $estudiantes = $sortOrder === 'asc' 
                ? $estudiantes->sortBy(fn($e) => $e->user->name . ' ' . $e->user->last_name_father)
                : $estudiantes->sortByDesc(fn($e) => $e->user->name . ' ' . $e->user->last_name_father);
        } elseif ($sortBy === 'nota') {
            $estudiantes = $sortOrder === 'desc'
                ? $estudiantes->sortByDesc(fn($e) => optional($e->evaluations->first())->nota ?? -1)
                : $estudiantes->sortBy(fn($e) => optional($e->evaluations->first())->nota ?? 999);
        }
        $estudiantes = $estudiantes->values();
        return view('admin.evaluacion.calificar', compact('competicion', 'fase', 'estudiantes', 'areas', 'categorias', 'numeroFase'));
    }

    public function guardarCalificaciones(Request $request, Competicion $competicion, $faseId)
    {
        $request->validate([
            'calificaciones' => 'required|array',
            'calificaciones.*.puntaje' => 'nullable|numeric|min:0|max:100',
            'calificaciones.*.observaciones' => 'nullable|string|max:1000'
        ]);

        $fase = $competicion->phases()->findOrFail($faseId);
        $evaluadorId = \Illuminate\Support\Facades\Auth::id();
        $calificacionesGuardadas = 0;

        try {
            DB::beginTransaction();

            foreach ($request->calificaciones as $inscripcionId => $datos) {
                // Verificar que el puntaje no esté vacío
                if (!isset($datos['puntaje']) || $datos['puntaje'] === '' || $datos['puntaje'] === null) {
                    continue; // Saltar esta inscripción si no hay puntaje
                }

                $inscripcion = Inscription::where('id', $inscripcionId)
                    ->where('competition_id', $competicion->id)
                    ->first();

                if ($inscripcion) {
                    // Crear o actualizar evaluación sin usar stage_id
                    Evaluation::updateOrCreate(
                        ['inscription_id' => $inscripcionId],
                        [
                            'evaluator_id' => $evaluadorId,
                            'nota' => $datos['puntaje'],
                            'observaciones_evaluador' => $datos['observaciones'] ?? null,
                            'estado' => $this->determinarEstado($datos['puntaje']),
                            'is_active' => true,
                        ]
                    );

                    $calificacionesGuardadas++;
                }
            }

            DB::commit();

            if ($calificacionesGuardadas > 0) {
                return redirect()->back()->with('success', "Se guardaron {$calificacionesGuardadas} calificaciones exitosamente.");
            } else {
                return redirect()->back()->with('error', 'No se guardó ninguna calificación. Verifica que hayas ingresado un puntaje válido.');
            }

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al guardar las calificaciones: ' . $e->getMessage());
        }
    }

    private function determinarEstado($nota)
    {
        // Lógica para determinar el estado basado en la nota
        if ($nota >= 70) {
            return 'clasificado';
        } elseif ($nota >= 60) {
            return 'no_clasificado';
        } else {
            return 'desclasificado';
        }
    }

    /**
     * Clasificar estudiantes por cupo
     * Toma las N mejores notas y crea nuevas inscripciones con fase incrementada
     */
    public function clasificarPorCupo(Request $request, Competicion $competicion, $faseId)
    {
        $request->validate([
            'cupo' => 'required|integer|min:1',
        ]);

        $fase = $competicion->phases()->findOrFail($faseId);
        $cupo = (int) $request->cupo;

        try {
            DB::beginTransaction();

            // Obtener las N mejores notas de la fase actual (sin usar stage_id)
            $mejoresEvaluaciones = Evaluation::where('is_active', true)
                ->whereHas('inscription', function($q) use ($competicion) {
                    $q->where('competition_id', $competicion->id);
                })
                ->with('inscription')
                ->orderBy('nota', 'DESC')
                ->take($cupo)
                ->get();

            if ($mejoresEvaluaciones->isEmpty()) {
                DB::rollback();
                return redirect()->back()->with('error', 'No se encontraron evaluaciones para clasificar.');
            }

            $clasificados = 0;

            foreach ($mejoresEvaluaciones as $evaluacion) {
                $inscripcionOriginal = $evaluacion->inscription;
                
                $faseActual = $inscripcionOriginal->fase ?? 1;
                $faseSiguiente = $faseActual + 1;
                
                $yaExiste = Inscription::where('user_id', $inscripcionOriginal->user_id)
                    ->where('competition_id', $competicion->id)
                    ->where('area_id', $inscripcionOriginal->area_id)
                    ->where('categoria_id', $inscripcionOriginal->categoria_id)
                    ->where('fase', $faseSiguiente)
                    ->exists();
                
                if (!$yaExiste) {
                    Inscription::create([
                        'competition_id' => $inscripcionOriginal->competition_id,
                        'user_id' => $inscripcionOriginal->user_id,
                        'area_id' => $inscripcionOriginal->area_id,
                        'categoria_id' => $inscripcionOriginal->categoria_id,
                        'estado' => 'confirmada',
                        'is_active' => true,
                        'fase' => $faseSiguiente,
                        'grupo_nombre' => $inscripcionOriginal->grupo_nombre,
                    ]);
                    
                    $clasificados++;
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Se clasificaron {$clasificados} estudiantes a la fase {$faseSiguiente}.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al clasificar estudiantes: ' . $e->getMessage());
        }
    }

    /**
     * Clasificar estudiantes por notas altas
     * Toma todos los estudiantes con nota mayor o igual a la nota mínima
     */
    public function clasificarPorNotasAltas(Request $request, Competicion $competicion, $faseId)
    {
        $request->validate([
            'nota_minima' => 'required|numeric|min:0|max:100',
        ]);

        $fase = $competicion->phases()->findOrFail($faseId);
        $notaMinima = (float) $request->nota_minima;

        try {
            DB::beginTransaction();

            // Obtener todas las evaluaciones con nota mayor o igual a la nota mínima (sin stage_id)
            $evaluacionesClasificadas = Evaluation::where('is_active', true)
                ->where('nota', '>=', $notaMinima)
                ->whereHas('inscription', function($q) use ($competicion) {
                    $q->where('competition_id', $competicion->id);
                })
                ->with('inscription')
                ->orderBy('nota', 'DESC')
                ->get();

            if ($evaluacionesClasificadas->isEmpty()) {
                DB::rollback();
                return redirect()->back()->with('error', 'No se encontraron estudiantes con nota mayor o igual a ' . $notaMinima);
            }

            $clasificados = 0;

            foreach ($evaluacionesClasificadas as $evaluacion) {
                $inscripcionOriginal = $evaluacion->inscription;
                
                $faseActual = $inscripcionOriginal->fase ?? 1;
                $faseSiguiente = $faseActual + 1;
                
                $yaExiste = Inscription::where('user_id', $inscripcionOriginal->user_id)
                    ->where('competition_id', $competicion->id)
                    ->where('area_id', $inscripcionOriginal->area_id)
                    ->where('categoria_id', $inscripcionOriginal->categoria_id)
                    ->where('fase', $faseSiguiente)
                    ->exists();
                
                if (!$yaExiste) {
                    Inscription::create([
                        'competition_id' => $inscripcionOriginal->competition_id,
                        'user_id' => $inscripcionOriginal->user_id,
                        'area_id' => $inscripcionOriginal->area_id,
                        'categoria_id' => $inscripcionOriginal->categoria_id,
                        'estado' => 'confirmada',
                        'is_active' => true,
                        'fase' => $faseSiguiente,
                        'grupo_nombre' => $inscripcionOriginal->grupo_nombre,
                    ]);
                    
                    $clasificados++;
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Se clasificaron {$clasificados} estudiantes con nota >= {$notaMinima} a la fase {$faseSiguiente}.");

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al clasificar estudiantes: ' . $e->getMessage());
        }
    }

    /**
     * Obtiene o crea un registro en 'stages' para la combinación competición-fase
     * y retorna su ID para ser usado como foreign key en evaluations.stage_id
     */
    private function getOrCreateStageId(Competicion $competicion, \App\Models\Phase $fase): int
    {
        // Buscar por competición y nombre de fase
        $stage = DB::table('stages')
            ->where('id_competition', $competicion->id)
            ->where('nombre', $fase->name)
            ->first();

        if ($stage) {
            return (int) $stage->id;
        }

        // Usar fechas del pivot si existen, de lo contrario asignar por defecto
        $fechaInicio = $fase->pivot->start_date ?? now();
        $fechaFin = $fase->pivot->end_date ?? now()->copy()->addDays(7);

        return (int) DB::table('stages')->insertGetId([
            'nombre' => $fase->name,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin,
            'id_competition' => $competicion->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function finalizarFase(Request $request, Competicion $competicion, $faseId)
    {
        $fase = $competicion->phases()->where('phases.id', $faseId)->firstOrFail();

        $tipo = $fase->pivot->classification_type;
        $notaMinima = $fase->pivot->classification_nota_minima;
        $cupo = $fase->pivot->classification_cupo;

        if (!$tipo) {
            return redirect()->back()->with('error', 'No hay un tipo de clasificación configurado para esta fase.');
        }

        try {
            DB::beginTransaction();

            // Obtener número de fase desde el query param o calcularlo
            $numeroFase = (int) request('fase_n', request('fase'));
            
            if ($numeroFase <= 0) {
                // Determinar fase actual ordenando por fechas (igual que en showFases)
                $todasLasFases = $competicion->phases()
                    ->orderBy('start_date', 'asc')
                    ->orderBy('end_date', 'asc')
                    ->get();
                    
                foreach ($todasLasFases as $index => $f) {
                    if ($f->id == $faseId) {
                        $numeroFase = $index + 1;
                        break;
                    }
                }
                
                if ($numeroFase <= 0) {
                    $numeroFase = 1;
                }
            }

            $evaluacionesQuery = Evaluation::where('is_active', true)
                ->whereHas('inscription', function($q) use ($competicion, $numeroFase) {
                    $q->where('competition_id', $competicion->id)
                      ->where('fase', $numeroFase);
                })
                ->with('inscription')
                ->orderBy('nota', 'DESC');

            $evaluaciones = collect();

            if ($tipo === 'notas_altas') {
                if ($notaMinima === null) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'No se configuró la nota mínima para esta fase.');
                }
                $evaluaciones = (clone $evaluacionesQuery)
                    ->where('nota', '>=', $notaMinima)
                    ->get();
            } elseif ($tipo === 'cupo') {
                if (empty($cupo) || (int)$cupo < 1) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'No se configuró un cupo válido para esta fase.');
                }
                $top = (clone $evaluacionesQuery)->take((int)$cupo)->get();
                if ($top->isEmpty()) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'No se encontraron evaluaciones para clasificar.');
                }
                $evaluaciones = $top;
                $ultimaNota = optional($top->last())->nota;
                if ($ultimaNota !== null) {
                    $empatados = (clone $evaluacionesQuery)
                        ->where('nota', '=', $ultimaNota)
                        ->get();
                    $evaluaciones = $evaluaciones->merge($empatados)
                        ->unique('inscription_id')
                        ->values();
                }
            }

            if ($evaluaciones->isEmpty()) {
                DB::rollBack();
                return redirect()->back()->with('error', 'No hay estudiantes que cumplan los criterios de clasificación.');
            }

            $clasificados = 0;
            $faseSiguiente = null;

            foreach ($evaluaciones as $evaluacion) {
                $inscripcionOriginal = $evaluacion->inscription;
                $faseActual = $inscripcionOriginal->fase ?? 1;
                $faseSiguiente = $faseActual + 1;

                $yaExiste = Inscription::where('user_id', $inscripcionOriginal->user_id)
                    ->where('competition_id', $competicion->id)
                    ->where('area_id', $inscripcionOriginal->area_id)
                    ->where('categoria_id', $inscripcionOriginal->categoria_id)
                    ->where('fase', $faseSiguiente)
                    ->exists();

                if (!$yaExiste) {
                    Inscription::create([
                        'competition_id' => $inscripcionOriginal->competition_id,
                        'user_id' => $inscripcionOriginal->user_id,
                        'area_id' => $inscripcionOriginal->area_id,
                        'categoria_id' => $inscripcionOriginal->categoria_id,
                        'estado' => 'confirmada',
                        'is_active' => true,
                        'fase' => $faseSiguiente,
                        'grupo_nombre' => $inscripcionOriginal->grupo_nombre,
                    ]);
                    $clasificados++;
                }
            }

            DB::commit();

            $mensaje = $tipo === 'notas_altas'
                ? "Clasificados {$clasificados} estudiantes con nota >= {$notaMinima} a la fase {$faseSiguiente}."
                : "Clasificados {$clasificados} mejores puntajes (incluye empates) a la fase {$faseSiguiente}.";

            return redirect()->back()->with('success', $mensaje);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al finalizar la fase: ' . $e->getMessage());
        }
    }

    /**
     * Página de Premiación (medallero) por competición
     */
    public function premiacion(\App\Models\Competicion $competicion)
    {
        // Intentar obtener premiados desde el medallero configurado
        $premiados = collect($this->generarReportePremiados($competicion->id));

        // Si no hay premiados del medallero, mostrar todos los clasificados de la última fase
        if ($premiados->isEmpty()) {
            $totalFases = $competicion->phases()->count();
            if ($totalFases > 0) {
                $numeroFaseAnterior = $totalFases;

                // Obtener clasificados ordenados por nota descendente
                $clasificados = \App\Models\Evaluation::with(['inscription.user', 'inscription.area', 'inscription.categoria'])
                    ->where('estado', self::ESTADO_CLASIFICADO)
                    ->whereHas('inscription', function ($q) use ($competicion, $numeroFaseAnterior) {
                        $q->where('competition_id', $competicion->id)
                          ->where('is_active', true)
                          ->where('estado', 'confirmada')
                          ->where('fase', $numeroFaseAnterior);
                    })
                    ->orderByDesc('nota')
                    ->get();

                // Agrupar por área y categoría, luego asignar posiciones y premios
                $premiadosRaw = $clasificados->groupBy(function ($ev) {
                    $area = $ev->inscription->area->name ?? 'Sin área';
                    $nivel = $ev->inscription->categoria->nombre ?? 'Sin nivel';
                    return $area . '|' . $nivel;
                })->flatMap(function ($grupo) {
                    // Ordenar por nota descendente dentro del grupo
                    $ordenados = $grupo->sortByDesc('nota')->values();
                    
                    return $ordenados->map(function ($ev, $index) {
                        $posicion = $index + 1;
                        
                        // Asignar premio según posición
                        $premio = match($posicion) {
                            1 => 'oro',
                            2 => 'plata',
                            3 => 'bronce',
                            default => 'mencion_honor'
                        };

                        return [
                            'posicion' => $posicion,
                            'nombre_completo' => trim(($ev->inscription->user->name ?? '') . ' ' . ($ev->inscription->user->last_name_father ?? '') . ' ' . ($ev->inscription->user->last_name_mother ?? '')),
                            'unidad_educativa' => $ev->inscription->user->school ?? 'No especificada',
                            'area' => $ev->inscription->area->name ?? 'Sin área',
                            'nivel' => $ev->inscription->categoria->nombre ?? 'Sin nivel',
                            'nota' => $ev->nota,
                            'premio' => $premio,
                        ];
                    });
                });

                $premiados = $premiadosRaw;
            }
        }

        // Agrupar por Área y Nivel para la vista
        $premiadosGrouped = $premiados->groupBy(function ($p) {
            return ($p['area'] ?? 'Área') . ' | ' . ($p['nivel'] ?? 'Nivel');
        })->map(function ($items) {
            return $items->sortBy('posicion')->values();
        });

        return view('admin.evaluacion.premiacion', [
            'competicion' => $competicion,
            'premiados' => $premiados,
            'premiadosGrouped' => $premiadosGrouped,
        ]);
    }

    /**
     * Generar PDF de premiación por grupo (área y nivel)
     */
    public function generarPdfPremiacion(Request $request, $competicionId)
    {
        $competicion = Competicion::findOrFail($competicionId);
        $area = $request->query('area');
        $nivel = $request->query('nivel');

        // Obtener clasificados de la última fase
        $totalFases = $competicion->phases()->count();
        if ($totalFases == 0) {
            return response()->json(['error' => 'No hay fases configuradas'], 404);
        }

        $numeroFaseAnterior = $totalFases;

        // Obtener clasificados del grupo específico
        $clasificados = \App\Models\Evaluation::with(['inscription.user', 'inscription.area', 'inscription.categoria'])
            ->where('estado', self::ESTADO_CLASIFICADO)
            ->whereHas('inscription', function ($q) use ($competicion, $numeroFaseAnterior, $area, $nivel) {
                $q->where('competition_id', $competicion->id)
                  ->where('is_active', true)
                  ->where('estado', 'confirmada')
                  ->where('fase', $numeroFaseAnterior);
                
                if ($area) {
                    $q->whereHas('area', function($qa) use ($area) {
                        $qa->where('name', $area);
                    });
                }
                
                if ($nivel) {
                    $q->whereHas('categoria', function($qc) use ($nivel) {
                        $qc->where('nombre', $nivel);
                    });
                }
            })
            ->orderByDesc('nota')
            ->get();

        // Asignar posiciones y premios
        $premiados = $clasificados->map(function ($ev, $index) use ($area, $nivel) {
            $posicion = $index + 1;
            
            $premio = match($posicion) {
                1 => 'oro',
                2 => 'plata',
                3 => 'bronce',
                default => 'mencion_honor'
            };

            return [
                'posicion' => $posicion,
                'nombre_completo' => trim(($ev->inscription->user->name ?? '') . ' ' . ($ev->inscription->user->last_name_father ?? '') . ' ' . ($ev->inscription->user->last_name_mother ?? '')),
                'unidad_educativa' => $ev->inscription->user->school ?? 'No especificada',
                'area' => $area ?? ($ev->inscription->area->name ?? 'Sin área'),
                'nivel' => $nivel ?? ($ev->inscription->categoria->nombre ?? 'Sin nivel'),
                'nota' => $ev->nota,
                'premio' => $premio,
            ];
        });

        // Generar PDF
        $pdf = Pdf::loadView('admin.evaluacion.pdf.premiacion', [
            'competicion' => $competicion,
            'premiados' => $premiados,
            'area' => $area ?? 'Todas las áreas',
            'nivel' => $nivel ?? 'Todos los niveles',
            'grupo' => ($area ?? 'Todas') . ' | ' . ($nivel ?? 'Todos'),
        ])->setPaper('letter', 'portrait');

        return $pdf->stream("premiacion_{$competicion->id}_{$area}_{$nivel}.pdf");
    }

    // Métodos auxiliares
    private function tienePermisosEvaluacion($evaluadorId, $areaId)
    {
        // Lógica para validar permisos del evaluador
        return true; // Implementar según tu lógica de roles
    }
    
    private function esResponsableArea($usuarioId, $competicionId)
    {
        // Lógica para validar si es responsable
        return true; // Implementar según tu lógica
    }
    
    private function esEtapaClasificatoria($stageId)
    {
        // Determinar si es etapa de clasificación
        return true; // Implementar según tu lógica
    }

    // Generar PDF de inscritos
    public function generarPdfInscritos(Request $request, $competicion, $fase)
    {
        // Obtener filtros
        $categoria = $request->input('categoria');
        $area = $request->input('area');
        $estado_activo = $request->input('estado_activo', 'activo');
        $search = $request->input('search');
        $numeroFase = $request->input('fase_n');

        // Obtener datos igual que en gestionarEstudiantes
        $competicion = \App\Models\Competicion::findOrFail($competicion);
        $faseObj = \App\Models\Phase::findOrFail($fase);
        $faseEnCompeticion = $competicion->phases()->where('phase_id', $faseObj->id)->first();
        $todasLasFases = $competicion->phases()->orderBy('competition_phase.id')->get();
        $numeroFase = (int) $request->input('fase_n', $request->input('fase'));
        if ($numeroFase <= 0) {
            foreach ($todasLasFases as $index => $f) {
                if ($f->id == $faseObj->id) { $numeroFase = $index + 1; break; }
            }
            if ($numeroFase <= 0) { $numeroFase = 1; }
        } else {
            $maxFases = max(1, $todasLasFases->count());
            if ($numeroFase > $maxFases) { $numeroFase = $maxFases; }
        }
        $categorias = \App\Models\Categoria::where('is_active', true)->get();
        $areas = \App\Models\Area::where('is_active', true)->get();
        $query = \App\Models\Inscription::with(['user', 'area', 'categoria']);
        $query->where('competition_id', $competicion->id);
        $query->where('fase', $numeroFase);
        if ($estado_activo === 'inactivo') { $query->where('is_active', false); }
        elseif ($estado_activo === 'todos') { /* no-op */ }
        else { $query->where('is_active', true); }
        if ($categoria) { $query->where('categoria_id', $categoria); }
        if ($area) { $query->where('area_id', $area); }
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name_father', 'like', "%{$search}%")
                  ->orWhere('last_name_mother', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%");
            });
        }
        $estudiantes = $query->get();
        // Generar PDF
        $pdf = Pdf::loadView('admin.evaluacion.pdf.inscritos', compact('competicion', 'faseObj', 'categorias', 'areas', 'estudiantes', 'numeroFase'));
        return $pdf->stream('lista_inscritos.pdf');
    }

    // Generar PDF de clasificados (siguiente fase)
    public function generarPdfClasificados(Request $request, $competicion, $fase)
    {
        // Filtros similares para mantener consistencia visual
        $categoria = $request->input('categoria');
        $area = $request->input('area');
        $estado_activo = $request->input('estado_activo', 'activo');
        $search = $request->input('search');
        $numeroFaseActual = (int) $request->input('fase_n', $request->input('fase'));

        $competicion = \App\Models\Competicion::findOrFail($competicion);
        $faseObj = \App\Models\Phase::findOrFail($fase);

        // Calcular número de fase si no viene correcto
        $todasLasFases = $competicion->phases()->orderBy('competition_phase.id')->get();
        if ($numeroFaseActual <= 0) {
            foreach ($todasLasFases as $index => $f) {
                if ($f->id == $faseObj->id) { $numeroFaseActual = $index + 1; break; }
            }
            if ($numeroFaseActual <= 0) { $numeroFaseActual = 1; }
        } else {
            $maxFases = max(1, $todasLasFases->count());
            if ($numeroFaseActual > $maxFases) { $numeroFaseActual = $maxFases; }
        }

        $numeroFaseSiguiente = $numeroFaseActual + 1;

        // Traer inscripciones de la siguiente fase como "clasificados"
        $query = \App\Models\Inscription::with(['user', 'area', 'categoria', 'evaluations'])
            ->where('competition_id', $competicion->id)
            ->where('fase', $numeroFaseSiguiente);

        if ($estado_activo === 'inactivo') { $query->where('is_active', false); }
        elseif ($estado_activo === 'todos') { /* no-op */ }
        else { $query->where('is_active', true); }
        if ($categoria) { $query->where('categoria_id', $categoria); }
        if ($area) { $query->where('area_id', $area); }
        if ($search) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name_father', 'like', "%{$search}%")
                  ->orWhere('last_name_mother', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%");
            });
        }

        $clasificados = $query->get();

        // Obtener inscripciones de la fase actual (anterior a la siguiente) para los mismos usuarios con sus evaluaciones
        $inscripcionesPreviasKeyed = \App\Models\Inscription::with(['evaluations'])
            ->where('competition_id', $competicion->id)
            ->where('fase', $numeroFaseActual)
            ->whereIn('user_id', $clasificados->pluck('user_id'))
            ->get()
            ->keyBy('user_id');

        $pdf = Pdf::loadView('admin.evaluacion.pdf.clasificados', [
            'competicion' => $competicion,
            'faseObj' => $faseObj,
            'numeroFaseActual' => $numeroFaseActual,
            'numeroFaseSiguiente' => $numeroFaseSiguiente,
            'estudiantes' => $clasificados,
            'inscripcionesPreviasKeyed' => $inscripcionesPreviasKeyed,
        ]);
        return $pdf->stream('lista_clasificados.pdf');
    }
}