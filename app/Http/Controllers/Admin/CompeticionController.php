<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competicion;
use App\Models\Area;
use App\Models\Phase;
use App\Models\Categoria;
use App\Models\CompetitionCategoryArea;
use Illuminate\Support\Facades\Validator;



class CompeticionController extends Controller
{
    public function show($id)
    {
        $competicion = Competicion::with(['areas', 'phases', 'categorias'])->findOrFail($id);
        return view('admin.competicion.show', compact('competicion'));
    }
    /**
     * Actualiza el estado de una competición.
     */
    public function updateState($id, $state)
    {
        $validStates = ['activa', 'completada', 'cancelada'];
        if (!in_array($state, $validStates)) {
            return redirect()->back()->with('error', 'Estado no válido.');
        }
        $competicion = Competicion::findOrFail($id);
        $competicion->state = $state;
        $competicion->save();
        return redirect()->back()->with('success', 'Estado actualizado correctamente.');
    }
    public function create()
    {
        $areasCatalog = Area::where('is_active', true)->get();
        $fasesCatalog = Phase::where('is_active', true)->get();
        $categoriasCatalog = Categoria::where('is_active', true)->get();
        return view('admin.competicion.create', compact('areasCatalog', 'fasesCatalog', 'categoriasCatalog'));
    }
    public function json($id)
    {
        $competicion = Competicion::findOrFail($id);
        return response()->json($competicion);
    }
    public function index()
    {
        $competiciones = Competicion::with(['areas', 'phases', 'categorias', 'categoryAreas.categoria', 'categoryAreas.area'])->paginate(10);
        $areasCatalog = Area::where('is_active', true)->get();
        $fasesCatalog = Phase::where('is_active', true)->get();
        $categoriasCatalog = Categoria::where('is_active', true)->get();
        return view('admin.competicion.index', compact('competiciones', 'areasCatalog', 'fasesCatalog', 'categoriasCatalog'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'inscripcion_inicio' => 'nullable|date',
            'inscripcion_fin' => 'nullable|date|after_or_equal:inscripcion_inicio',
            'evaluacion_inicio' => 'nullable|date',
            'evaluacion_fin' => 'nullable|date|after_or_equal:evaluacion_inicio',
            'premiacion_inicio' => 'nullable|date',
            'premiacion_fin' => 'nullable|date|after_or_equal:premiacion_inicio',
            'categoria_ids' => 'nullable|array',
            'categoria_ids.*' => 'exists:categorias,id',
            'area_ids' => 'required_without:pairs|array',
            'area_ids.*' => 'exists:areas,id',
            'pairs' => 'nullable|array',
            'pairs.*.categoria_id' => 'required_with:pairs|exists:categorias,id',
            'pairs.*.area_id' => 'required_with:pairs|exists:areas,id',
            'phases' => 'required|array',
            'phases.*.phase_id' => 'required|exists:phases,id',
            'phases.*.start_date' => 'required|date|after_or_equal:fechaInicio',
            'phases.*.end_date' => 'required|date|before_or_equal:fechaFin|after_or_equal:phases.*.start_date',
            'phases.*.clasificados' => 'nullable|integer|min:1',
            'phases.*.color' => 'nullable|string|max:7',
            'phases.*.classification.type' => 'nullable|in:cupo,notas_altas',
            'phases.*.classification.cupo' => 'nullable|integer|min:1',
            'phases.*.classification.nota_minima' => 'nullable|numeric|min:0|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            $phases = $request->input('phases', []);
            foreach ($phases as $idx => $phase) {
                $type = data_get($phase, 'classification.type');
                if ($type === 'cupo') {
                    $cupo = data_get($phase, 'classification.cupo');
                    if (is_null($cupo) || $cupo === '' || (int)$cupo < 1) {
                        $validator->errors()->add("phases.$idx.classification.cupo", 'El cupo es requerido y debe ser mayor a 0 cuando el tipo es "Por cupo".');
                    }
                } elseif ($type === 'notas_altas') {
                    $nota = data_get($phase, 'classification.nota_minima');
                    if ($nota === null || $nota === '' || !is_numeric($nota) || $nota < 0 || $nota > 100) {
                        $validator->errors()->add("phases.$idx.classification.nota_minima", 'La nota mínima es requerida y debe estar entre 0 y 100 cuando el tipo es "Notas altas".');
                    }
                }
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $competicion = Competicion::create([
            'name' => $request->name,
            'description' => $request->description,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'inscripcion_inicio' => $request->inscripcion_inicio,
            'inscripcion_fin' => $request->inscripcion_fin,
            'evaluacion_inicio' => $request->evaluacion_inicio,
            'evaluacion_fin' => $request->evaluacion_fin,
            'premiacion_inicio' => $request->premiacion_inicio,
            'premiacion_fin' => $request->premiacion_fin,
            'state' => 'activa',
        ]);

        // Derivar listas finales desde pairs si existen
        $pairs = collect($request->pairs ?? []);
        $categoriaIdsFinal = $pairs->pluck('categoria_id')->filter()->unique()->values()->all();
        $areaIdsFinal = $pairs->pluck('area_id')->filter()->unique()->values()->all();
        if (empty($categoriaIdsFinal)) { $categoriaIdsFinal = $request->categoria_ids ?? []; }
        if (empty($areaIdsFinal)) { $areaIdsFinal = $request->area_ids ?? []; }

        // Sync polimórfico
        $competicion->categorias()->sync($categoriaIdsFinal);
        $competicion->areas()->sync($areaIdsFinal);

        // Guardar pares categoria-area
        CompetitionCategoryArea::where('competition_id', $competicion->id)->delete();
        $pairsToInsert = [];
        if ($pairs->isNotEmpty()) {
            foreach ($pairs as $p) {
                if (!empty($p['categoria_id']) && !empty($p['area_id'])) {
                    $pairsToInsert[] = [
                        'competition_id' => $competicion->id,
                        'categoria_id' => $p['categoria_id'],
                        'area_id' => $p['area_id'],
                    ];
                }
            }
        } else {
            // Fallback round-robin
            $idx = 0; $catCount = max(1, count($categoriaIdsFinal));
            foreach ($areaIdsFinal as $areaId) {
                $catId = $categoriaIdsFinal[$idx % $catCount] ?? null;
                if ($catId) {
                    $pairsToInsert[] = [
                        'competition_id' => $competicion->id,
                        'categoria_id' => $catId,
                        'area_id' => $areaId,
                    ];
                }
                $idx++;
            }
        }
        if (!empty($pairsToInsert)) {
            CompetitionCategoryArea::insert($pairsToInsert);
        }

        // Fases
        $phaseData = [];
        foreach ($request->phases as $phase) {
            $type = data_get($phase, 'classification.type');
            $cupo = $type === 'cupo' ? data_get($phase, 'classification.cupo') : null;
            $notaMin = $type === 'notas_altas' ? data_get($phase, 'classification.nota_minima') : null;

            $phaseData[$phase['phase_id']] = [
                'start_date' => $phase['start_date'],
                'end_date' => $phase['end_date'],
                'clasificados' => $phase['clasificados'] ?? null,
                'color' => $phase['color'] ?? null,
                'classification_type' => $type,
                'classification_cupo' => $cupo,
                'classification_nota_minima' => $notaMin,
            ];
        }
        $competicion->phases()->sync($phaseData);

        return redirect()->route('admin.competicion.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Fase creada exitosamente'
        ]);
    }

    public function edit($id)
    {
        $competicion = Competicion::with(['areas', 'phases', 'categorias'])->findOrFail($id);
        $areasCatalog = Area::where('is_active', true)->get();
        $fasesCatalog = Phase::where('is_active', true)->get();
        $categoriasCatalog = Categoria::where('is_active', true)->get();
        return view('admin.competicion.edit', compact('competicion', 'areasCatalog', 'fasesCatalog', 'categoriasCatalog'));
    }

    public function update(Request $request, $competicion)
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'inscripcion_inicio' => 'nullable|date',
            'inscripcion_fin' => 'nullable|date|after_or_equal:inscripcion_inicio',
            'evaluacion_inicio' => 'nullable|date',
            'evaluacion_fin' => 'nullable|date|after_or_equal:evaluacion_inicio',
            'premiacion_inicio' => 'nullable|date',
            'premiacion_fin' => 'nullable|date|after_or_equal:premiacion_inicio',
            'categoria_ids' => 'nullable|array',
            'categoria_ids.*' => 'exists:categorias,id',
            'area_ids' => 'required_without:pairs|array',
            'area_ids.*' => 'exists:areas,id',
            'pairs' => 'nullable|array',
            'pairs.*.categoria_id' => 'required_with:pairs|exists:categorias,id',
            'pairs.*.area_id' => 'required_with:pairs|exists:areas,id',
            'phases' => 'required|array',
            'phases.*.phase_id' => 'required|exists:phases,id',
            'phases.*.start_date' => 'required|date|after_or_equal:fechaInicio',
            'phases.*.end_date' => 'required|date|before_or_equal:fechaFin|after_or_equal:phases.*.start_date',
            'phases.*.clasificados' => 'nullable|integer|min:1',
            'phases.*.color' => 'nullable|string|max:7',
            'phases.*.classification.type' => 'nullable|in:cupo,notas_altas',
            'phases.*.classification.cupo' => 'nullable|integer|min:1',
            'phases.*.classification.nota_minima' => 'nullable|numeric|min:0|max:100',
        ];

        $validator = Validator::make($request->all(), $rules);
        $validator->after(function ($validator) use ($request) {
            $phases = $request->input('phases', []);
            foreach ($phases as $idx => $phase) {
                $type = data_get($phase, 'classification.type');
                if ($type === 'cupo') {
                    $cupo = data_get($phase, 'classification.cupo');
                    if (is_null($cupo) || $cupo === '' || (int)$cupo < 1) {
                        $validator->errors()->add("phases.$idx.classification.cupo", 'El cupo es requerido y debe ser mayor a 0 cuando el tipo es "Por cupo".');
                    }
                } elseif ($type === 'notas_altas') {
                    $nota = data_get($phase, 'classification.nota_minima');
                    if ($nota === null || $nota === '' || !is_numeric($nota) || $nota < 0 || $nota > 100) {
                        $validator->errors()->add("phases.$idx.classification.nota_minima", 'La nota mínima es requerida y debe estar entre 0 y 100 cuando el tipo es "Notas altas".');
                    }
                }
            }
        });
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Convertir a modelo si es ID
        if (!$competicion instanceof Competicion) {
            $competicion = Competicion::findOrFail($competicion);
        }
        
        $competicion->update([
            'name' => $request->name,
            'description' => $request->description,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'inscripcion_inicio' => $request->inscripcion_inicio,
            'inscripcion_fin' => $request->inscripcion_fin,
            'evaluacion_inicio' => $request->evaluacion_inicio,
            'evaluacion_fin' => $request->evaluacion_fin,
            'premiacion_inicio' => $request->premiacion_inicio,
            'premiacion_fin' => $request->premiacion_fin,
        ]);

        // Derivar listas finales desde pairs si existen
        $pairs = collect($request->pairs ?? []);
        $categoriaIdsFinal = $pairs->pluck('categoria_id')->filter()->unique()->values()->all();
        $areaIdsFinal = $pairs->pluck('area_id')->filter()->unique()->values()->all();
        if (empty($categoriaIdsFinal)) { $categoriaIdsFinal = $request->categoria_ids ?? []; }
        if (empty($areaIdsFinal)) { $areaIdsFinal = $request->area_ids ?? []; }

        // Sync polimórfico
        $competicion->categorias()->sync($categoriaIdsFinal);
        $competicion->areas()->sync($areaIdsFinal);

        // Actualizar pares categoria-area
        CompetitionCategoryArea::where('competition_id', $competicion->id)->delete();
        $pairsToInsert = [];
        if ($pairs->isNotEmpty()) {
            foreach ($pairs as $p) {
                if (!empty($p['categoria_id']) && !empty($p['area_id'])) {
                    $pairsToInsert[] = [
                        'competition_id' => $competicion->id,
                        'categoria_id' => $p['categoria_id'],
                        'area_id' => $p['area_id'],
                    ];
                }
            }
        } else {
            $idx = 0; $catCount = max(1, count($categoriaIdsFinal));
            foreach ($areaIdsFinal as $areaId) {
                $catId = $categoriaIdsFinal[$idx % $catCount] ?? null;
                if ($catId) {
                    $pairsToInsert[] = [
                        'competition_id' => $competicion->id,
                        'categoria_id' => $catId,
                        'area_id' => $areaId,
                    ];
                }
                $idx++;
            }
        }
        if (!empty($pairsToInsert)) {
            CompetitionCategoryArea::insert($pairsToInsert);
        }

        // Fases
        $phaseData = [];
        foreach ($request->phases as $phase) {
            $type = data_get($phase, 'classification.type');
            $cupo = $type === 'cupo' ? data_get($phase, 'classification.cupo') : null;
            $notaMin = $type === 'notas_altas' ? data_get($phase, 'classification.nota_minima') : null;

            $phaseData[$phase['phase_id']] = [
                'start_date' => $phase['start_date'],
                'end_date' => $phase['end_date'],
                'clasificados' => $phase['clasificados'] ?? null,
                'color' => $phase['color'] ?? null,
                'classification_type' => $type,
                'classification_cupo' => $cupo,
                'classification_nota_minima' => $notaMin,
            ];
        }
        $competicion->phases()->sync($phaseData);

        return redirect()->route('admin.competicion.index')
        ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Competición actualizada correctamente'
        ]);
    }

    public function destroy($id)
    {
        $competicion = Competicion::findOrFail($id);
        $competicion->delete();
        return redirect()->route('admin.competicion.index')
        ->with('swal_success', 'Competición eliminada correctamente');
    }
}
