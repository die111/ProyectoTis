<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EtapaController extends Controller
{
    public function index()
    {
        $query = request('search');
        $phases = Phase::query()
            ->when($query, function($q) use ($query) {
                $search = mb_strtolower($query);
                $q->whereRaw('LOWER(name) LIKE ?', ["%$search%"])
                  ->orWhereRaw('LOWER(description) LIKE ?', ["%$search%"]);
            })
            ->orderBy('name')
            ->paginate(10);
        
        // Agregar información de uso para cada fase
        foreach ($phases as $phase) {
            $phase->in_use = $phase->isInUse();
        }
        
        return view('admin.etapas.index', compact('phases', 'query'));
    }

    public function create()
    {
        return view('admin.etapas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20|unique:phases,name',
            'description' => 'nullable|string|max:30',
        ]);
        Phase::create($request->only(['name', 'description']));
        return redirect()->route('admin.phases.index')->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Fase creada exitosamente'
        ]);
    }

    public function edit($id)
    {
        $phase = Phase::findOrFail($id);
        return view('admin.etapas.edit', compact('phase'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:20|unique:phases,name,' . $id,
            'description' => 'nullable|string|max:30',
        ]);
        $phase = Phase::findOrFail($id);
        $phase->update($request->only(['name', 'description']));
       
         return redirect()->route('admin.phases.index')->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Fase actualizada correctamente.'
        ]);
    }

    public function destroy($id)
    {
        $phase = Phase::findOrFail($id);

        DB::transaction(function () use ($phase, $id) {
            // Obtener todas las competiciones donde está asociada esta fase (vía tabla pivot)
            $links = DB::table('competition_phase')
                ->where('phase_id', $id)
                ->orderBy('id')
                ->get();

            foreach ($links as $link) {
                $competitionId = $link->competition_id ?? $link->id_competition ?? null;
                if (!$competitionId) continue;

                // Determinar ordinal eliminado dentro de la competición usando el id del pivot como orden
                $removedOrdinal = DB::table('competition_phase')
                    ->where(function($q) use ($competitionId) {
                        $q->where('competition_id', $competitionId)->orWhere('id_competition', $competitionId);
                    })
                    ->where('id', '<=', $link->id)
                    ->count();

                // Compactar numeración de inscripciones: fase > removedOrdinal => fase - 1
                DB::table('inscriptions')
                    ->where('competition_id', $competitionId)
                    ->where('fase', '>', $removedOrdinal)
                    ->decrement('fase');
            }

            // Desactivar la fase globalmente
            $phase->is_active = false;
            $phase->save();
        });

        return redirect()->route('admin.phases.index')
            ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Fase deshabilitada y numeración sincronizada correctamente.'
        ]);
    }

    public function habilitar($id)
    {
        $phase = Phase::findOrFail($id);
        $phase->is_active = true;
        $phase->save();
        return redirect()->route('admin.phases.index')
            ->with([
            'swal_custom' => true,
            'swal_title' => '¡Éxito!',
            'swal_icon' => 'success',
            'swal_text' => 'Fase activado correctamente.'
        ]);
    }
}
