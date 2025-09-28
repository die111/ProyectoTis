<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Phase;
use Illuminate\Http\Request;

class EtapaController extends Controller
{
    public function index()
    {
        $phases = Phase::orderBy('start_date')->get();
        return view('admin.etapas.index', compact('phases'));
    }

    public function create()
    {
        return view('admin.etapas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        Phase::create($request->all());
        return redirect()->route('admin.etapas.index')->with('success', 'Fase creada correctamente.');
    }

    public function edit($id)
    {
        $phase = Phase::findOrFail($id);
        return view('admin.etapas.edit', compact('phase'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $phase = Phase::findOrFail($id);
        $phase->update($request->all());
        return redirect()->route('admin.etapas.index')->with('success', 'Fase actualizada correctamente.');
    }

    public function destroy($id)
    {
        $phase = Phase::findOrFail($id);
        $phase->delete();
        return redirect()->route('admin.etapas.index')->with('success', 'Fase eliminada correctamente.');
    }

    //soft delete
    // public function destroy($id){
    //     $phase = Phase::findOrFail($id);
    //     $phase->is_active = false;
    //     $phase->save();
    //     return redirect()->route('admin.etapas.index')->with('success', 'Fase desactivada correctamente.');
    // }
}
