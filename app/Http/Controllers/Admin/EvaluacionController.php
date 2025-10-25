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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        // Cargar las fases de la competición
        $fases = $competicion->phases()->get();
        
        return view('admin.evaluacion.fases', compact('competicion', 'fases'));
    }
    
    /**
     * Gestionar estudiantes de una fase específica en una competición específica
     */
    public function gestionarEstudiantes(Competicion $competicion, $faseId)
    {
        $fase = \App\Models\Phase::findOrFail($faseId);
        
        // Verificar que la fase esté asociada a la competición
        $faseEnCompeticion = $competicion->phases()->where('phase_id', $faseId)->first();
        if (!$faseEnCompeticion) {
            abort(404, 'La fase no está asociada a esta competición');
        }
        
        // Obtener todas las categorías y áreas para los filtros
        $categorias = \App\Models\Categoria::where('is_active', true)->get();
        $areas = \App\Models\Area::where('is_active', true)->get();
        
        // Construir query para estudiantes con filtros
        $query = \App\Models\Inscription::with(['user', 'area', 'level']);
        
        // Filtrar por la competición específica
        $query->where('competition_id', $competicion->id);
        
        // Aplicar filtros si existen
        if (request('categoria')) {
            // Por ahora omitimos el filtro por categoría hasta que se establezca la relación
            // $query->whereHas('level', function($q) {
            //     $q->where('categoria_id', request('categoria'));
            // });
        }
        
        if (request('area')) {
            $query->where('area_id', request('area'));
        }
        
        if (request('search')) {
            $search = request('search');
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('last_name_father', 'like', "%{$search}%")
                  ->orWhere('last_name_mother', 'like', "%{$search}%")
                  ->orWhere('school', 'like', "%{$search}%");
            });
        }
        
        $estudiantes = $query->paginate(10);
        
        return view('admin.evaluacion.estudiantes', compact('fase', 'competicion', 'categorias', 'areas', 'estudiantes'));
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
}