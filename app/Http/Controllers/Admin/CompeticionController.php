<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competicion;
use App\Models\Area;
use App\Models\Phase;
use App\Models\Level;



class CompeticionController extends Controller
{
    public function show($id)
    {
        $competicion = Competicion::with(['areas', 'phases', 'levels'])->findOrFail($id);
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
        $levelsCatalog = Level::all();
        $areasCatalog = Area::all();
        $fasesCatalog = Phase::all();
        return view('admin.competicion.create', compact('levelsCatalog', 'areasCatalog', 'fasesCatalog'));
    }
    public function json($id)
    {
        $competicion = Competicion::findOrFail($id);
        return response()->json($competicion);
    }
    public function index()
    {
        $competiciones = Competicion::with(['areas', 'phases', 'levels'])->paginate(10);
        $areasCatalog = Area::all();
        $fasesCatalog = Phase::all();
        $levelsCatalog = Level::all();
        return view('admin.competicion.index', compact('competiciones', 'areasCatalog', 'fasesCatalog', 'levelsCatalog'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'level_ids' => 'required|array',
            'level_ids.*' => 'exists:levels,id',
            'area_ids' => 'required|array',
            'area_ids.*' => 'exists:areas,id',
            'phases' => 'required|array',
            'phases.*.phase_id' => 'required|exists:phases,id',
            'phases.*.start_date' => 'required|date|after_or_equal:fechaInicio',
            'phases.*.end_date' => 'required|date|before_or_equal:fechaFin|after_or_equal:phases.*.start_date',
        ]);

        $competicion = Competicion::create([
            'name' => $request->name,
            'description' => $request->description,
            'fechaInicio' => $request->fechaInicio,
            'fechaFin' => $request->fechaFin,
            'state' => 'activa',
        ]);

        // Attach levels and areas (polymorphic)
        $competicion->levels()->sync($request->level_ids);
        $competicion->areas()->sync($request->area_ids);

        // Attach phases with dates
        $phaseData = [];
        foreach ($request->phases as $phase) {
            $phaseData[$phase['phase_id']] = [
                'start_date' => $phase['start_date'],
                'end_date' => $phase['end_date'],
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
    $competicion = Competicion::findOrFail($id);
    $areasCatalog = Area::all();
    $levelsCatalog = Level::all();
    $fasesCatalog = Phase::all();
    return view('admin.competicion.edit', compact('competicion', 'areasCatalog', 'levelsCatalog', 'fasesCatalog'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'nivel' => 'required|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'area_id' => 'required|exists:areas,id',
            'phase_id' => 'required|exists:phases,id',
        ]);
        $competicion = Competicion::findOrFail($id);
        $competicion->update($request->all());
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
