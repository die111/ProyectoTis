<?php

namespace App\Http\Controllers\Admin;

use App\Models\{
    Competicion,
    Inscription,
    Evaluation,
    Categoria,
    Area
};
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CalificacionGrupalController extends \App\Http\Controllers\Controller
{
    /**
     * Vista de calificación grupal (calificar múltiples estudiantes a la vez)
     */
    public function calificarGrupal(Competicion $competicion, $faseId)
    {
        $fase = $competicion->phases()->findOrFail($faseId);
        $todasLasFases = $competicion->phases()->orderBy('competition_phase.id')->get();
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
        $categorias = Categoria::where('is_active', true)->get();
        $areas = Area::where('is_active', true)->get();
        $query = Inscription::with(['user', 'area', 'categoria', 'evaluations']);
        $query->where('competition_id', $competicion->id);
        $query->where('fase', $numeroFase);
        $query->where('categoria_id', 3);
        $query->where('estado', 'confirmada');
        // Excluir estudiantes con estado pendiente
        $query->where('estado', '!=', 'pendiente');
        if (request('estado_activo') === 'inactivo') { $query->where('is_active', false); }
        elseif (request('estado_activo') === 'todos') { /* no-op */ }
        else { $query->where('is_active', true); }
        if (request('categoria')) { $query->where('categoria_id', request('categoria')); }
        if (request('area')) { $query->where('area_id', request('area')); }
        if (request('search')) {
            $search = $this->removeAccents(request('search'));
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($subQ) use ($search) {
                    $subQ->whereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u')) LIKE ?", ['%' . strtolower($search) . '%'])
                        ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(last_name_father, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u')) LIKE ?", ['%' . strtolower($search) . '%'])
                        ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(last_name_mother, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u')) LIKE ?", ['%' . strtolower($search) . '%'])
                        ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(school, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u')) LIKE ?", ['%' . strtolower($search) . '%'])
                        ->orWhere('ci', 'like', "%{$search}%");
                })
                ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(name_grupo, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u')) LIKE ?", ['%' . strtolower($search) . '%']);
            });
        }
        $query->orderBy('area_id', 'asc')->orderBy('name_grupo', 'asc');
        $estudiantes = $query->paginate(20)->appends(request()->query());
        return view('admin.evaluacion.calificar-grupal', compact('fase', 'competicion', 'categorias', 'areas', 'estudiantes', 'numeroFase'));
    }

    private function removeAccents($string)
    {
        $unwanted_array = [
            'á'=>'a', 'Á'=>'a', 'à'=>'a', 'À'=>'a', 'ã'=>'a', 'Ã'=>'a', 'â'=>'a', 'Â'=>'a',
            'é'=>'e', 'É'=>'e', 'è'=>'e', 'È'=>'e', 'ê'=>'e', 'Ê'=>'e',
            'í'=>'i', 'Í'=>'i', 'ì'=>'i', 'Ì'=>'i', 'î'=>'i', 'Î'=>'i',
            'ó'=>'o', 'Ó'=>'o', 'ò'=>'o', 'Ò'=>'o', 'õ'=>'o', 'Õ'=>'o', 'ô'=>'o', 'Ô'=>'o',
            'ú'=>'u', 'Ú'=>'u', 'ù'=>'u', 'Ù'=>'u', 'û'=>'u', 'Û'=>'u',
            'ñ'=>'n', 'Ñ'=>'n'
        ];
        return strtr($string, $unwanted_array);
    }

    public function guardarCalificacionesGrupales(Request $request, Competicion $competicion, $faseId)
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
                if (!isset($datos['puntaje']) || $datos['puntaje'] === '' || $datos['puntaje'] === null) {
                    continue;
                }
                $inscripcion = Inscription::where('id', $inscripcionId)
                    ->where('competition_id', $competicion->id)
                    ->where('categoria_id', 3)
                    ->first();
                if ($inscripcion) {
                    $evaluacionData = [
                        'evaluator_id' => $evaluadorId,
                        'nota' => $datos['puntaje'],
                        'observaciones_evaluador' => $datos['observaciones'] ?? null,
                        'estado' => $this->determinarEstadoGrupal($datos['puntaje']),
                        'is_active' => true,
                    ];
                    Evaluation::updateOrCreate(
                        ['inscription_id' => $inscripcionId],
                        $evaluacionData
                    );
                    $calificacionesGuardadas++;
                }
            }
            DB::commit();
            if ($calificacionesGuardadas > 0) {
                return redirect()->back()->with('success', "Se guardaron {$calificacionesGuardadas} calificaciones grupales exitosamente.");
            } else {
                return redirect()->back()->with('error', 'No se guardó ninguna calificación grupal. Verifica que hayas ingresado un puntaje válido.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al guardar las calificaciones grupales: ' . $e->getMessage());
        }
    }

    private function determinarEstadoGrupal($nota)
    {
        if ($nota >= 70) {
            return 'clasificado';
        } elseif ($nota >= 60) {
            return 'no_clasificado';
        } else {
            return 'desclasificado';
        }
    }

    /**
     * Clasificar estudiantes grupales por cupo
     */
    public function clasificarPorCupoGrupal(Request $request, Competicion $competicion, $faseId)
    {
        $request->validate([
            'cupo' => 'required|integer|min:1',
        ]);
        $fase = $competicion->phases()->findOrFail($faseId);
        $cupo = (int) $request->cupo;
        try {
            DB::beginTransaction();
            $mejoresEvaluaciones = Evaluation::where('is_active', true)
                ->whereHas('inscription', function($q) use ($competicion) {
                    $q->where('competition_id', $competicion->id)
                      ->where('categoria_id', 3);
                })
                ->with('inscription')
                ->orderBy('nota', 'DESC')
                ->take($cupo)
                ->get();
            if ($mejoresEvaluaciones->isEmpty()) {
                DB::rollback();
                return redirect()->back()->with('error', 'No se encontraron evaluaciones grupales para clasificar.');
            }
            $clasificados = 0;
            foreach ($mejoresEvaluaciones as $evaluacion) {
                $inscripcionOriginal = $evaluacion->inscription;
                $faseActual = $inscripcionOriginal->fase ?? 1;
                $faseSiguiente = $faseActual + 1;
                $yaExiste = Inscription::where('user_id', $inscripcionOriginal->user_id)
                    ->where('competition_id', $competicion->id)
                    ->where('area_id', $inscripcionOriginal->area_id)
                    ->where('categoria_id', 3)
                    ->where('fase', $faseSiguiente)
                    ->exists();
                if (!$yaExiste) {
                    Inscription::create([
                        'competition_id' => $inscripcionOriginal->competition_id,
                        'user_id' => $inscripcionOriginal->user_id,
                        'area_id' => $inscripcionOriginal->area_id,
                        'categoria_id' => 3,
                        'estado' => 'confirmada',
                        'is_active' => true,
                        'fase' => $faseSiguiente,
                        'name_grupo' => $inscripcionOriginal->name_grupo ?? 'N/A',
                    ]);
                    $clasificados++;
                }
            }
            DB::commit();
            return redirect()->back()->with('success', "Se clasificaron {$clasificados} grupos a la fase {$faseSiguiente}.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al clasificar grupos: ' . $e->getMessage());
        }
    }

    /**
     * Clasificar estudiantes grupales por notas altas
     */
    public function clasificarPorNotasAltasGrupal(Request $request, Competicion $competicion, $faseId)
    {
        $request->validate([
            'nota_minima' => 'required|numeric|min:0|max:100',
        ]);
        $fase = $competicion->phases()->findOrFail($faseId);
        $notaMinima = (float) $request->nota_minima;
        try {
            DB::beginTransaction();
            $evaluacionesClasificadas = Evaluation::where('is_active', true)
                ->where('nota', '>=', $notaMinima)
                ->whereHas('inscription', function($q) use ($competicion) {
                    $q->where('competition_id', $competicion->id)
                      ->where('categoria_id', 3);
                })
                ->with('inscription')
                ->orderBy('nota', 'DESC')
                ->get();
            if ($evaluacionesClasificadas->isEmpty()) {
                DB::rollback();
                return redirect()->back()->with('error', 'No se encontraron grupos con nota mayor o igual a ' . $notaMinima);
            }
            $clasificados = 0;
            foreach ($evaluacionesClasificadas as $evaluacion) {
                $inscripcionOriginal = $evaluacion->inscription;
                $faseActual = $inscripcionOriginal->fase ?? 1;
                $faseSiguiente = $faseActual + 1;
                $yaExiste = Inscription::where('user_id', $inscripcionOriginal->user_id)
                    ->where('competition_id', $competicion->id)
                    ->where('area_id', $inscripcionOriginal->area_id)
                    ->where('categoria_id', 3)
                    ->where('fase', $faseSiguiente)
                    ->exists();
                if (!$yaExiste) {
                    Inscription::create([
                        'competition_id' => $inscripcionOriginal->competition_id,
                        'user_id' => $inscripcionOriginal->user_id,
                        'area_id' => $inscripcionOriginal->area_id,
                        'categoria_id' => 3,
                        'estado' => 'confirmada',
                        'is_active' => true,
                        'fase' => $faseSiguiente,
                        'name_grupo' => $inscripcionOriginal->name_grupo ?? 'N/A',
                    ]);
                    $clasificados++;
                }
            }
            DB::commit();
            return redirect()->back()->with('success', "Se clasificaron {$clasificados} grupos con nota >= {$notaMinima} a la fase {$faseSiguiente}.");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al clasificar grupos: ' . $e->getMessage());
        }
    }

    /**
     * Guardar nota grupal y promedio para todos los miembros del grupo
     */
    public function evaluarGrupo(Request $request, $competicionId, $faseId)
    {
        $request->validate([
            'nota_grupal' => 'required|numeric|min:0|max:100',
            'grupo' => 'required',
            'area' => 'required',
        ]);
        $notaGrupal = $request->input('nota_grupal');
        $nombreGrupo = $request->input('grupo');
        $areaNombre = $request->input('area');
        // Buscar el área
        $area = Area::where('name', $areaNombre)->first();
        if (!$area) {
            return back()->with('error', 'Área no encontrada');
        }
        // Buscar inscripciones del grupo en la fase y área
        $inscripciones = Inscription::where('competition_id', $competicionId)
            ->where('fase', $faseId)
            ->where('categoria_id', 3)
            ->where('area_id', $area->id)
            ->where('name_grupo', $nombreGrupo)
            ->get();
        if ($inscripciones->isEmpty()) {
            return back()->with('error', 'No se encontraron inscripciones para el grupo');
        }
        // Guardar nota y promedio en evaluations
        foreach ($inscripciones as $inscripcion) {
            $evaluacion = Evaluation::firstOrNew([
                'inscription_id' => $inscripcion->id
            ]);
            $evaluacion->nota = $notaGrupal;
            $evaluacion->promedio = $notaGrupal;
            $evaluacion->is_active = true;
            $evaluacion->evaluator_id = \Illuminate\Support\Facades\Auth::id();
            $evaluacion->estado = $this->determinarEstadoGrupal($notaGrupal);
            $evaluacion->save();
        }
        return back()->with('success', 'Nota grupal guardada correctamente para todos los miembros del grupo.');
    }

    /**
     * Guardar observación individual por estudiante (AJAX)
     */
    public function guardarObservacion(Request $request, $competicionId, $faseId, $estudianteId)
    {
        $request->validate([
            'observacion' => 'nullable|string|max:1000'
        ]);
        try {
            // Buscar inscripción del estudiante en la competición y fase
            $inscripcion = Inscription::where('id', $estudianteId)
                ->where('competition_id', $competicionId)
                ->where('fase', $faseId)
                ->where('categoria_id', 3)
                ->first();
            if (!$inscripcion) {
                return response()->json(['message' => 'Inscripción no encontrada'], 404);
            }
            // Buscar evaluación
            $evaluacion = Evaluation::firstOrNew([
                'inscription_id' => $inscripcion->id
            ]);
            $evaluacion->observaciones_evaluador = $request->input('observacion');
            $evaluacion->is_active = true;
            $evaluacion->evaluator_id = \Illuminate\Support\Facades\Auth::id();
            // Si ya tiene nota, mantenerla
            if ($evaluacion->nota === null) {
                $evaluacion->nota = null;
            }
            // Estado: mantener si existe
            if ($evaluacion->estado === null) {
                $evaluacion->estado = 'no_clasificado';
            }
            $evaluacion->save();
            return response()->json(['message' => 'Observación guardada correctamente']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al guardar la observación: ' . $e->getMessage()], 500);
        }
    }
    // Puedes agregar aquí otros métodos grupales que sean necesarios
}
