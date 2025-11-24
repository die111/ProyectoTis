<?php

namespace App\Http\Controllers\Admin;

use App\Models\Inscription;
use App\Models\Evaluation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * Controlador especializado para el cálculo y gestión de promedios grupales
 */
class PromedioGrupalController extends \App\Http\Controllers\Controller
{
    /**
     * Actualiza el promedio del grupo solo si TODOS los miembros están calificados
     * 
     * @param int $competicionId ID de la competición
     * @param string $nombreGrupo Nombre del grupo
     * @param int $fase Número de fase
     * @return array Resultado de la operación con información del promedio
     */
    public function actualizarPromedioGrupo($competicionId, $nombreGrupo, $fase)
    {
        // Obtener todas las inscripciones del grupo en esta fase
        $inscripcionesGrupo = Inscription::where('competition_id', $competicionId)
            ->where('name_grupo', $nombreGrupo)
            ->where('fase', $fase)
            ->with('evaluations')
            ->get();
        
        if ($inscripcionesGrupo->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No se encontraron inscripciones para este grupo',
                'promedio' => null
            ];
        }
        
        // Verificar si TODOS tienen evaluación con nota
        $todosCalificados = true;
        $sumaNotas = 0;
        $contadorNotas = 0;
        $notasIndividuales = [];
        
        foreach ($inscripcionesGrupo as $inscripcion) {
            $evaluacion = $inscripcion->evaluations->first();
            
            if (!$evaluacion || $evaluacion->nota === null) {
                $todosCalificados = false;
                break;
            }
            
            $sumaNotas += $evaluacion->nota;
            $contadorNotas++;
            $notasIndividuales[] = [
                'user_id' => $inscripcion->user_id,
                'nombre' => $inscripcion->user->name ?? 'Sin nombre',
                'nota' => $evaluacion->nota
            ];
        }
        
        // Solo actualizar si TODOS están calificados
        if (!$todosCalificados) {
            return [
                'success' => false,
                'message' => 'No todos los miembros del grupo están calificados',
                'total_miembros' => $inscripcionesGrupo->count(),
                'miembros_calificados' => $contadorNotas,
                'promedio' => null
            ];
        }
        
        if ($contadorNotas === 0) {
            return [
                'success' => false,
                'message' => 'No hay notas para calcular el promedio',
                'promedio' => null
            ];
        }
        
        $promedioGrupo = round($sumaNotas / $contadorNotas, 2);
        
        // Actualizar el promedio para todos los miembros del grupo
        DB::beginTransaction();
        try {
            foreach ($inscripcionesGrupo as $inscripcion) {
                $evaluacion = $inscripcion->evaluations->first();
                if ($evaluacion) {
                    $evaluacion->promedio = $promedioGrupo;
                    $evaluacion->save();
                }
            }
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Promedio actualizado correctamente',
                'promedio' => $promedioGrupo,
                'total_miembros' => $contadorNotas,
                'notas_individuales' => $notasIndividuales
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Error al actualizar el promedio: ' . $e->getMessage(),
                'promedio' => null
            ];
        }
    }
    
    /**
     * Actualiza los promedios de múltiples grupos
     * 
     * @param array $grupos Array de grupos con formato: [['competicion_id' => 1, 'nombre' => 'Grupo A', 'fase' => 1], ...]
     * @return array Resumen de la operación
     */
    public function actualizarPromediosMultiples(array $grupos)
    {
        $resultados = [];
        $exitosos = 0;
        $fallidos = 0;
        
        foreach ($grupos as $grupo) {
            if (!isset($grupo['competicion_id']) || !isset($grupo['nombre']) || !isset($grupo['fase'])) {
                $fallidos++;
                $resultados[] = [
                    'grupo' => $grupo['nombre'] ?? 'Desconocido',
                    'success' => false,
                    'message' => 'Datos incompletos'
                ];
                continue;
            }
            
            $resultado = $this->actualizarPromedioGrupo(
                $grupo['competicion_id'],
                $grupo['nombre'],
                $grupo['fase']
            );
            
            if ($resultado['success']) {
                $exitosos++;
            } else {
                $fallidos++;
            }
            
            $resultados[] = array_merge($resultado, ['grupo' => $grupo['nombre']]);
        }
        
        return [
            'total' => count($grupos),
            'exitosos' => $exitosos,
            'fallidos' => $fallidos,
            'detalles' => $resultados
        ];
    }
    
    /**
     * Obtiene el promedio de un grupo sin actualizarlo en la base de datos
     * 
     * @param int $competicionId ID de la competición
     * @param string $nombreGrupo Nombre del grupo
     * @param int $fase Número de fase
     * @return array Información del promedio calculado
     */
    public function calcularPromedioGrupo($competicionId, $nombreGrupo, $fase)
    {
        $inscripcionesGrupo = Inscription::where('competition_id', $competicionId)
            ->where('name_grupo', $nombreGrupo)
            ->where('fase', $fase)
            ->with('evaluations')
            ->get();
        
        if ($inscripcionesGrupo->isEmpty()) {
            return [
                'existe' => false,
                'promedio' => null,
                'message' => 'Grupo no encontrado'
            ];
        }
        
        $todosCalificados = true;
        $sumaNotas = 0;
        $contadorNotas = 0;
        $notasIndividuales = [];
        
        foreach ($inscripcionesGrupo as $inscripcion) {
            $evaluacion = $inscripcion->evaluations->first();
            
            if (!$evaluacion || $evaluacion->nota === null) {
                $todosCalificados = false;
                $notasIndividuales[] = [
                    'user_id' => $inscripcion->user_id,
                    'nombre' => $inscripcion->user->name ?? 'Sin nombre',
                    'nota' => null,
                    'calificado' => false
                ];
            } else {
                $sumaNotas += $evaluacion->nota;
                $contadorNotas++;
                $notasIndividuales[] = [
                    'user_id' => $inscripcion->user_id,
                    'nombre' => $inscripcion->user->name ?? 'Sin nombre',
                    'nota' => $evaluacion->nota,
                    'calificado' => true
                ];
            }
        }
        
        return [
            'existe' => true,
            'nombre_grupo' => $nombreGrupo,
            'fase' => $fase,
            'todos_calificados' => $todosCalificados,
            'total_miembros' => $inscripcionesGrupo->count(),
            'miembros_calificados' => $contadorNotas,
            'promedio' => $todosCalificados && $contadorNotas > 0 ? round($sumaNotas / $contadorNotas, 2) : null,
            'notas_individuales' => $notasIndividuales
        ];
    }
    
    /**
     * Recalcula los promedios de todos los grupos de una competición y fase específica
     * 
     * @param int $competicionId ID de la competición
     * @param int $fase Número de fase
     * @return array Resumen de la operación
     */
    public function recalcularPromediosPorFase($competicionId, $fase)
    {
        // Obtener todos los grupos únicos de la fase
        $grupos = Inscription::where('competition_id', $competicionId)
            ->where('fase', $fase)
            ->where('categoria_id', 3) // Solo categoría grupal
            ->whereNotNull('name_grupo')
            ->where('name_grupo', '!=', 'N/A')
            ->distinct()
            ->pluck('name_grupo');
        
        if ($grupos->isEmpty()) {
            return [
                'success' => false,
                'message' => 'No se encontraron grupos en esta fase',
                'total' => 0
            ];
        }
        
        $gruposArray = $grupos->map(function($nombreGrupo) use ($competicionId, $fase) {
            return [
                'competicion_id' => $competicionId,
                'nombre' => $nombreGrupo,
                'fase' => $fase
            ];
        })->toArray();
        
        return $this->actualizarPromediosMultiples($gruposArray);
    }
    
    /**
     * Obtiene un reporte de todos los grupos con sus promedios en una fase
     * 
     * @param int $competicionId ID de la competición
     * @param int $fase Número de fase
     * @return array Lista de grupos con sus promedios
     */
    public function reportePromediosPorFase($competicionId, $fase)
    {
        $grupos = Inscription::where('competition_id', $competicionId)
            ->where('fase', $fase)
            ->where('categoria_id', 3)
            ->whereNotNull('name_grupo')
            ->where('name_grupo', '!=', 'N/A')
            ->distinct()
            ->pluck('name_grupo');
        
        $reporte = [];
        
        foreach ($grupos as $nombreGrupo) {
            $info = $this->calcularPromedioGrupo($competicionId, $nombreGrupo, $fase);
            $reporte[] = $info;
        }
        
        // Ordenar por promedio descendente
        usort($reporte, function($a, $b) {
            if ($a['promedio'] === null) return 1;
            if ($b['promedio'] === null) return -1;
            return $b['promedio'] <=> $a['promedio'];
        });
        
        return $reporte;
    }
    
    /**
     * Endpoint API para recalcular promedios de una fase
     * Puede ser llamado desde AJAX, rutas o comandos
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function recalcularPromedios(Request $request)
    {
        $request->validate([
            'competicion_id' => 'required|integer|exists:competicions,id',
            'fase' => 'required|integer|min:1'
        ]);
        
        $resultado = $this->recalcularPromediosPorFase(
            $request->competicion_id,
            $request->fase
        );
        
        return response()->json([
            'success' => isset($resultado['total']) && $resultado['total'] > 0,
            'data' => $resultado
        ]);
    }
    
    /**
     * Endpoint API para obtener reporte de promedios
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function obtenerReportePromedios(Request $request)
    {
        $request->validate([
            'competicion_id' => 'required|integer|exists:competicions,id',
            'fase' => 'required|integer|min:1'
        ]);
        
        $reporte = $this->reportePromediosPorFase(
            $request->competicion_id,
            $request->fase
        );
        
        return response()->json([
            'success' => true,
            'data' => $reporte,
            'total_grupos' => count($reporte)
        ]);
    }

    /**
     * Actualiza los promedios de todos los grupos basándose en datos enviados desde el frontend
     * 
     * @param Request $request
     * @param int $competicion ID de la competición
     * @param int $fase ID de la fase
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarTodosLosPromedios(Request $request, $competicion, $fase)
    {
        try {
            $request->validate([
                'promedios' => 'required|array',
                'promedios.*.nombre_grupo' => 'required|string',
                'promedios.*.inscripciones' => 'required|array',
                'promedios.*.promedio' => 'required|numeric|min:0|max:100'
            ]);
            
            DB::beginTransaction();
            
            $gruposActualizados = 0;
            $inscripcionesActualizadas = 0;
            
            foreach ($request->promedios as $grupoData) {
                $nombreGrupo = $grupoData['nombre_grupo'];
                $inscripcionesIds = $grupoData['inscripciones'];
                $promedio = $grupoData['promedio'];
                
                // Actualizar el promedio en todas las evaluaciones del grupo
                foreach ($inscripcionesIds as $inscripcionId) {
                    $evaluacion = Evaluation::where('inscription_id', $inscripcionId)->first();
                    
                    if ($evaluacion) {
                        $evaluacion->promedio = $promedio;
                        $evaluacion->save();
                        $inscripcionesActualizadas++;
                    }
                }
                
                $gruposActualizados++;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Se actualizaron {$gruposActualizados} grupos ({$inscripcionesActualizadas} estudiantes) exitosamente.",
                'grupos_actualizados' => $gruposActualizados,
                'inscripciones_actualizadas' => $inscripcionesActualizadas
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar los promedios: ' . $e->getMessage()
            ], 500);
        }
    }
}
